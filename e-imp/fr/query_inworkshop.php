<script language="php">
  session_start();      
  if (!isset($_SESSION["uid"])) {
    $URL="/"; 
	echo "<script type='text/javascript'>location.replace('$URL');</script>"; } 	
  else { 
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <meta name="author" content="Edmund" />
  <title>I-ConS | Preview - In Event</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/common.css" />   
  <link rel="stylesheet" type="text/css" href="../asset/css/print-roboto.css" />   
  <style>
  .w3-text-grey{font-size:13px;color:#757575!important}
  </style>
</head>

<body> 
 <div class="w3-container w3-responsive" style="margin:0 auto">
  <div style="padding:10px 10px 15px 15px;border:0">
    <img src="../asset/img/pt-imp.png" >
  </div> 			   
   
<?php
  include("../asset/libs/db.php"); 
  include("../asset/libs/common.php");
  if(isset($_POST['location'])) {
    $filter = $_POST['tglIn'];
	$filter2 = $_POST['tglIn2'];
	$loc=$_POST['location'];
?>

  <h3>Workshop - Move In Report, Location: <?php echo $loc?>, Range Date: <?php echo $filter?> Until <?php echo $filter2?></h4>
  <table class="style-table">
   <thead>	
	<tr>
	  <th>Index</th>
      <th colspan="3" style="text-align:center">Container No.</th>
	  <th style="text-align:center">Size/Type/Height</th>
	  <th>Mnfr.</th>
	  <th>Const.</th>
	  <th>Port In</th>
	  <th>Hamp. In</th>
      <th>Survey</th>
      <th>C/R</th>
      <th>C/C</th>
      <th>Ex. Vessel Voyage</th>
	  <th>Shipping Line</th>
	  <th>User</th>
    </tr>
   </thead>
   <tbody>
          
<?php
        $query = "Select a.NoContainer, Format(gateIn,'yyyy-MM-dd') As DTMIn, JamIn, TruckingIn, VehicleInNumber, isPending, 
	              b.principle, b.consignee, b.vessel, a.isCleaning, a.isRepair,
		          c.Mnfr, c.Size, c.Type, c.Height, c.Constr, a.workshopID, Format(GIPort,'yyyy-MM-dd') As GIPort,
				  Format(CRDate, 'yyyy-MM-dd') As CR, Format(CCleaning,'yyyy-MM-dd') As CCleaning, Format(tanggalSurvey,'yyyy-MM-dd') As Survey
                  From containerJournal a 
			      Inner Join tabBookingHeader b On b.bookID=a.bookInID
			      Inner Join containerLog c On c.ContainerNo=a.NoContainer
			      Where (a.gateIn Between '$filter' And '$filter2') And a.workshopID='$loc'
                  Order By b.principle, c.Size, c.Height, a.gateIn ";
	    $result = mssql_query($query);
	    $numRows = mssql_num_rows($result);
	    $location = '';
        
        $design="";		
		$index=0;
	    if($numRows <= 0) { $design=$design.'<tr><td colspan="14" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; } 
		else {
	      while($arr=mssql_fetch_array($result)) {
			$index++;  
		    $sizeTypeHeight = $arr[12].'/'.$arr[13].'/'.$arr[14];
		    $principle = haveCustomerName($arr['principle']);
		    $consignee = haveCustomerName($arr['consignee']);
			
			$survey=str_replace(" ","",$arr['Survey']);
 		    if($survey=="") { $design=$design.'<tr style="color:Red;">'; }
		    else { $design=$design.'<tr style="color:Blue;">'; }
			
		    $design=$design.'<td>'.$index.'.</td>
	                         <td style="text-align:center">'.substr($arr["NoContainer"],0,4).'</td>
						     <td style="text-align:center">'.substr($arr["NoContainer"],4,6).'</td>
						     <td style="text-align:center">'.substr($arr["NoContainer"],10,1).'</td>
	                         <td style="text-align:center">'.$sizeTypeHeight.'</td>
		 		             <td>'.$arr[11].'</td>
	 			             <td>'.$arr[15].'</td>
				             <td>'.$arr['GIPort'].'</td>
				             <td>'.$arr['DTMIn'].'</td>							  
				             <td>'.$arr['Survey'].'</td>							  							  
				             <td>'.$arr['CR'].'</td>							  
				             <td>'.$arr['CCleaning'].'</td>							  
		                     <td>'.$arr[8].'</td>
                             <td>'.$principle.'</td>
                             <td>'.$consignee.'</td>
							 </tr>';					  					    
	      }
	      mssql_free_result($result);
	      mssql_close($dbSQL);
		  
		  echo $design;
		}
      }		
   mssql_close($dbSQL);	     
?>
   </tbody>    
  </table>
 
 </div>
 <div class="height-10"></div>
</body>
</html>

<?php  
  }
?>