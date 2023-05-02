<script language="php">
  session_start();     
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>   
  <script src="../asset/js/sweetalert2.min.js"></script>
</head>

<body> 
 <script language="php">  
  include("../asset/libs/db.php"); 
  
  if(isset($_GET['act'])) {
    $unit = strtoupper($_GET['unit']);
	$bookInID = strtoupper($_GET['transid']);
    $dateSurvey = $_GET['eventDate'];
    $statusPending = $_GET['surveyRemark'];
	$surveyor = strtoupper($_GET['surveyor']);
	$wrkid = $_GET['wrkid'];
	
	$Cleaning = 0;
	$Repair = 0;
	$failed = 0;
	
	if(isset($_GET['iscleaning'])) {$Cleaning = 1;}
	if(isset($_GET['isrepair'])) {$Repair = 1;}
	
    $query = "Select * From containerJournal Where NoContainer = '$unit' And BookInID = '$bookInID' And gateOut is Null And gateIn Is Not Null; ";
    $result = mssql_query($query);
    if(mssql_num_rows($result) <= 0) {$failed++;}
    mssql_free_result($result);
	
	$query = "Select * From tabBookingHeader Where bookID = '$bookInID' And principle != '' And consignee != '' And principle Is Not Null And consignee Is Not Null; ";
	$result = mssql_query($query);
	if(mssql_num_rows($result) <= 0) {$failed++;}
	mssql_free_result($result);
    
    if($failed <= 0) {	
	  $do = "Update containerJournal Set tanggalSurvey = '$dateSurvey', Surveyor = '$surveyor', pendingRemark = '$statusPending',
	                                     isCleaning = ".$Cleaning.", isRepair = ".$Repair."
	         Where NoContainer = '$unit' And BookInID = '$bookInID'; 
			 Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	         Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 120), CONCAT('Update Survey Log ', '$unit', ' Book ID ', '$bookInID')); ";
	  $result = mssql_query($do);
	  
	  $url = '/e-imp/mnr/?do=cont_list&noCnt='.$unit.'&location='.$wrkid.'&query=Start+Query';
	  echo "<script type='text/javascript'>location.replace('$url');</script>";       
	}
    else {	
	  echo '<script>swal("Error","Log failed to save. Container Journal block from editing");</script>';	
	}  
  }
    
  if(isset($_GET['unit']) && isset($_GET['transid']) && !isset($_GET['act'])) {	
    $unit = $_GET['unit'];
    $dtmin = $_GET['dtmin'];
	$location = $_GET['wrkid'];
	$bookID = $_GET['transid'];
	$dateSurvey = '';
	$surveyor = '';
	$statusPending = '';
	$Cleaning = 0;
	$Repair = 0;
    
    $query = "Select Format(tanggalSurvey, 'yyyy-MM-dd') As tanggalSurvey, Surveyor, pendingRemark, isCleaning, isRepair From containerJournal 
              Where NoContainer = '$unit' And bookInID = '$bookID'; ";
    $result = mssql_query($query);
    while($arr = mssql_fetch_array($result)) {
	  $dateSurvey = $arr['tanggalSurvey'];
      $surveyor = $arr['Surveyor'];
	  $statusPending = $arr['pendingRemark'];
	  $Cleaning = $arr['isCleaning'];
	  $Repair = $arr['isRepair'];
    }
    mssql_free_result($result);
	
    echo '<div class="w3-container">
           <table class="w3-table w3-bordered" style="background-color:#2196F3;font-weight:600">
	 	    <tr style="color:#fff!important;">
		     <td>MANAGE SURVEY LOG</td>
		    </tr>
		   </table>
		   <div class="height-10"></div>
		   <fieldset style="padding-left:0 40px 0 40px; background-color:#f1f1f1"> 
            <legend style="background-color:#fff;font-size:13px" class="w3-text-grey">&nbsp;Survey Detail&nbsp;</legend>
            <div class="height-10"></div>		 
 	  	    <label class="w3-text-grey">Notes For Date Field:<br>
		     &nbsp;&nbsp;1.&nbsp;&nbsp;Enter a date without punctuation (i.e "-","/")<br>
		     &nbsp;&nbsp;2.&nbsp;&nbsp;Date format yyyyMMdd (i.e 20171101).</label>
		    <div class="height-20"></div>	
			
			<form id="fsurvey" method="get" action="site_survey.php">
			 <input type="hidden" name="act" value="surveyLog">
			 <input type="hidden" name="unit" value='.$unit.'>
			 <input type="hidden" name="transid" value='.$bookID.'>
			 <input type="hidden" name="wrkid" value='.$location.'>
			 			 
			 <div class="w3-row-padding">
			   <div class="w3-half">
			    <label>Surveyor</label>
				<input type="text" class="w3-input w3-border" required maxlength="30" name="surveyor" style="text-transform:uppercase" value="'.$surveyor.'" />
			   </div>
               <div class="w3-half">
			    <label>Date of Survey</label>
			    <input class="w3-input w3-border" type="text" name="eventDate" id="fDate" maxlength="8" required 
				  title="Year-Month-Date" onKeyUp=dateSeparator("fDate")  value='.$dateSurvey.'>						   				
               </div>			   
			 </div>
			 
			 <div class="height-10"></div>
			 <div class="w3-container">
			  <div style="border-bottom:1px solid #ccc">Action To Do</div>
			 </div>  
			 <div class="height-10"></div>
			 <div class="w3-row-padding">
			  <div class="w3-half">';
    if($Cleaning == 1) {echo '<input class="w3-check" type="checkbox" name="iscleaning" checked />&nbsp;<label class="w3-text-grey">Need Cleaning</label>'; }
	else {echo '<input class="w3-check" type="checkbox" name="iscleaning">&nbsp;<label class="w3-text-grey">Need Cleaning</label>'; }
			  
	echo '	  </div>
			  <div class="w3-half">';

    if($Repair == 1) {echo '<input class="w3-check" type="checkbox" name="isrepair" checked />&nbsp;<label class="w3-text-grey">Need Repair</label>'; }
	else {echo '<input class="w3-check" type="checkbox" name="isrepair">&nbsp;<label class="w3-text-grey">Need Repair</label>'; }

   	echo '    </div>			  
			 </div>

			 <div class="height-10"></div>
			 <div class="w3-container">
			   <label>Survey Remarks</label>
			   <textarea name="surveyRemark" rows="5" cols="60" class="w3-input w3-border" style="text-transform:uppercase">'.$statusPending.'</textarea>
			   <div class="height-20"></div>';

    if(!isset($dateSurvey)) {echo '<button type="submit" class="w3-btn w3-border w3-pink">Save Log</button>';}

	echo '	  </div>
            </form>
           </fieldset>			
          </div>';	
  }
  mssql_close($dbSQL);
 </script>
</body>
</html>

<script>
  function dateSeparator(varID) {
    var str = document.getElementById(varID).value;
	panjang = str.length;
	if (panjang==8) {
      var partYear = str.slice(0,4);
	  var partMonth = str.slice(4,6); 
	  var partDate = str.slice(6,8);
	  
	  result = partYear.concat('-', partMonth, '-', partDate);
	  document.getElementById(varID).value = result;
	} 		 
  }
</script>