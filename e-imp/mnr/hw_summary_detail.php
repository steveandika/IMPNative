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
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>   
</head>

<body> 
  <script language="php">
    include("../asset/libs/db.php");
	include("../asset/libs/common.php");
  
    $filter1 = $_GET['filter1'];
    $filter2 = $_GET['filter2'];
  
    $do = "Select * From m_Location Where locationID = '".$_GET['location']."'";
    $result = mssql_query($do);
    while($arr = mssql_fetch_array($result)) {$workshopName = $arr[1];}
    mssql_free_result($result);
    
    echo '
           <div class="w3-container">
	        <div class="height-10"></div>
	        <label class="w3-text-grey">Event Date From: '.$filter1. ' Until: '.$filter2.'</label><br>
		    <label class="w3-text-grey">Workshop Location: '.$workshopName.'</label><br>
		    <div class="height-10"></div>';	
  
    $do = "Select NoContainer, Format(gateIn, 'yyyy-MM-dd') As gateIn, Size, Type, Format(gateOut, 'yyyy-MM-dd') As gateOut, 
                  Cond, Format(tanggalSurvey, 'yyyy-MM-dd') As surveyDate, Surveyor, 
	              Format(CRDate, 'yyyy-MM-dd') As CRDate, Format(CCleaning, 'yyyy-MM-dd') As CCleaning, Format(AVCond, 'yyyy-MM-dd') As AVCond,
                  c.principle, c.consignee, a.bookInID, cleaningType  
           From containerJournal a 
		   Inner Join containerLog b On b.ContainerNo = a.NoContainer
		   Inner Join tabBookingHeader c On c.BookId = a.bookInID
		   Where gateIn Is Not Null And (gateIn Between '$filter1' And '$filter2') And workshopID = '".$_GET['location']."'
		   Order By gateIn, Type, Size; ";
    $result = mssql_query($do);
    
    if(mssql_num_rows($result) > 0) {
      $index = 0;
      echo '<div class="w3-responsive"><table class="w3-table-all">
             <thead>        
			  <tr> 	
               <th>Index</th>			  
		       <th>Container Number</th>
		       <th>Size</th>
		       <th>Type</th>
			   <th>Principle</th>
			   <th>Consignee</th>
		       <th>Event In</th>			   
		       <th>Cond</th>
		       <th>Survey</th>
		       <th>Field Surveyor</th>
		       <th>Complete Repair</th>
               <th>Complete Cleaning</th>
			   <th>Cleaning Type</th>
               <th>AV</th>		
		       <th>Event Out</th>	
		      </tr>  
             </thead>
		     <tbody>';
  
      while($arr = mssql_fetch_array($result)) {
		/*$urlvar = '?unit='.$arr["NoContainer"].'&wrkid='.$_GET['location'].'&dtmin='.$arr["gateIn"].'&transid='.$arr["bookInID"];*/
		$liners = '';
		$consignee = '';
		
        $liners = haveCustomerName($arr["principle"]);
		$consignee = haveCustomerName($arr["consignee"]);
		$index++;
        echo '<tr>
		       <td>'.$index.'</td> 
	           <td>'.$arr["NoContainer"].'</td>
	           <td>'.$arr["Size"].'</td>
	           <td>'.$arr["Type"].'</td>
	           <td>'.$liners.'</td>
	           <td>'.$consignee.'</td>			   
	           <td>'.$arr["gateIn"].'</td>
	           <td>'.$arr["Cond"].'</td>
	           <td>'.$arr["surveyDate"].'</td>
	           <td>'.strtoupper($arr["Surveyor"]).'</td>
	           <td>'.$arr["CRDate"].'</td>
	           <td>'.$arr["CCleaning"].'</td>
			   <td>'.$arr["cleaningType"].'</td>
	           <td>'.$arr["AVCond"].'</td>
	           <td>'.$arr["gateOut"].'</td>';
/*        
        if(trim($arr["gateOut"]) != '') {echo '<td>&nbsp;</td>';}		
		else {echo '<td><a onclick=opendetail("'.$urlvar.'") class="w3-btn w3-light-grey w3-border" style="padding:1px 10px;border-radius:4px">Open It >></a></td>';}
	    */
		echo '</tr>';
      }

      echo ' </tbody></table></div><br><br>';  
    }
    else {echo "<label style='letter-spacing:1px;color:red;font-weight:bold;'>0 RECORD HAS FOUND</label>";}
	
	echo '</div>';
    mssql_close($dbSQL);
  }
</script>
</body>
</html>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
<script type="text/javascript">
  function opendetail(urlVariable) { 
    var w = window.open("hwdetail.php"+urlVariable); 
 	$(w.document.body).html(response); 
  }
</script>