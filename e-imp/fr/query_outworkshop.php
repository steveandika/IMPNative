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
  <title>I-ConS | Preview - Out Event</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/common.css" />   
  <link rel="stylesheet" type="text/css" href="../asset/css/print-roboto.css" />   
  <style>
  .w3-text-grey{font-size:13px;color:#757575!important}
  </style>

</head>

<body> 
 <div class="w3-container w3-responsive">
  <div style="padding:10px 10px 15px 15px;border:0">  
   <img src="../asset/img/pt-imp.png" >
  </div> 			   

<?php
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  if(isset($_POST["location"])) {
    $keywrd=$_POST['tglOut'];
	$keywrd2=$_POST['tglOut2'];
	$loc=$_POST['location'];
	
    $query = "Select a.NoContainer, Format(gateIn,'yyyy-MM-dd') As DTMIn, JamIn, TruckingOut, VehicleOutNumber, isPending, 
	                 b.principle, b.consignee, b.vessel, a.isCleaning, a.isRepair,
	                 c.Mnfr, c.Size, c.Type, c.Height, c.Constr, a.workshopID, d.locationDesc, Format(gateOut, 'yyyy-MM-dd') As DTMOut, Format(GIPort,'yyyy-MM-dd') As GIPort,
					 Format(CRDate, 'yyyy-MM-dd') As CR, Format(CCleaning,'yyyy-MM-dd') As CCleaning, Format(tanggalSurvey,'yyyy-MM-dd') As Survey
              From containerJournal a 
		      Inner Join tabBookingHeader b On b.bookID = a.bookInID
			  Inner Join containerLog c On c.ContainerNo = a.NoContainer
			  Left Join m_Location d On d.locationID = a.workshopID 
			  Where (gateOut Between '$keywrd' And '$keywrd2') And a.workshopID='$loc'
			  Order By b.principle, c.Size,c.Height, a.gateOut "; 
	$result = mssql_query($query);
?>

  <h3>Workshop - Move Out Report, Location: <?php echo $loc?>, Range Date: <?php echo $keywrd?> Until <?php echo $keywrd2?></h3>
  <table class="style-table">
   <thead>
	<tr>
	  <th>Index</th>
      <th colspan="3" style="text-align:center">Container No.</th>
	  <th style="text-align:center">Size/Type/Height</th>
	  <th>Mnfr.</th>
	  <th>Constr</th>
	  <th>Port In</th>
      <th>Hamp. In</th>      
	  <th>Survey</th>
	  <th>C/R</th>
	  <th>C/C</th>
	  <th>Hamp. Out</th>
	  <th>Shipping Line</th>	  
    </tr></thead><tbody>
	
<?php
    $indexKe = 0;
	$design="";
    if(mssql_num_rows($result) <= 0) { echo '<tr><td colspan="9" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; } 
	else {
	 while($arr=mssql_fetch_array($result)) {
	   $indexKe++;
       $sizeTypeHeight = $arr['Size'].' / '.$arr['Type'].' / '.$arr['Height']; 	   
	   $principle = haveCustomerName($arr['principle']);

   	   $survey=str_replace(" ","",$arr['Survey']);
 	   if($survey=="") { $design=$design.'<tr style="color:Red;">'; }
	   else { $design=$design.'<tr style="color:Blue;">'; }

/*	   
  	   $desc='%'.$arr["NoContainer"].'%';
	   $do="Select Top(1) userID From userLogAct Where DescriptionLog Like '$desc' Order By dateLog Desc";
	   $rsl=mssql_query($do);
	   if(mssql_num_rows($rsl)> 0) {
	   $col=mssql_fetch_array($rsl);
         $adm=$col['userID'];			  
	   }
   	   mssql_free_result($rsl);
*/	   
	   $design=$design.'<td>'.$indexKe.'</td>
	                    <td style="text-align:center">'.substr($arr["NoContainer"],0,4).'</td>
						<td style="text-align:center">'.substr($arr["NoContainer"],4,6).'</td>
						<td style="text-align:center">'.substr($arr["NoContainer"],10,1).'</td>
	                    <td style="text-align:center">'.$sizeTypeHeight.'</td>
                        <td>'.$arr['Mnfr'].'</td>
                        <td>'.$arr['Constr'].'</td>
				        <td>'.$arr['GIPort'].'</td>
                        <td>'.$arr['DTMIn'].'</td>
                        <td>'.$arr['Survey'].'</td>
                        <td>'.$arr['CR'].'</td>
                        <td>'.$arr['CCleaning'].'</td>							
                        <td>'.$arr['DTMOut'].'</td>
				        <td>'.$principle.'</td>							
					   </tr>'; 				 
	 }
	} 
	mssql_free_result($result);
	mssql_close($dbSQL);
	
	echo $design;
	
?>

    </tbody></table><div class="height-20"></div> 

<?php
    }   
?>

 </div>
</body>
</html>
	
<script language="php">

  }
</script>