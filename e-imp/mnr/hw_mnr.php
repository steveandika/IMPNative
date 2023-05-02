<script language="php">
  session_start();     
  include("../asset/libs/db.php"); 
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
  if(isset($_GET['act'])) {
    $unit = strtoupper($_GET['unit']);
	$bookInID = strtoupper($_GET['transid']);
	$wrkid = $_GET['wrkid'];
    $completeCl = $_GET['completeCleaning'];
	$completeCR = $_GET['completeRepair'];
    $jenisCl = $_GET['cleaningType'];
	$dtmin = $_GET['dtmin'];
    
	$failed = 0;
	
    $query = "Select NoContainer From containerJournal Where NoContainer = '$unit' And BookInID = '$bookInID' And gateOut is Null And gateIn Is Not Null; ";
    $result = mssql_query($query);
    if(mssql_num_rows($result) <= 0) {$failed++;}
    mssql_free_result($result);
	
	$query = "Select bookID From tabBookingHeader Where bookID = '$bookInID' And principle != '' And consignee != '' And principle Is Not Null And consignee Is Not Null; ";
    $result = mssql_query($query);
    if(mssql_num_rows($result) <= 0) {$failed++;}
    mssql_free_result($result);
	
	if(trim($completeCR) != '') {
	  if(date('yyyy-MM-dd', strtotime($dtmin)) > date('yyyy-MM-dd', strtotime($completeCR))) {$failed++;}
	}	  
/*	
	if(trim($completeCl) != '') {
	  if(date('yyyy-MM-dd', strtotime($dtmin)) > date('yyyy-MM-dd', strtotime($completeCl))) {$failed++;}
	}	  
	*/
    if($failed == 0) {	
	  $do = "Update containerJournal Set ";
	  if(trim($completeCl) != '') { $do = $do."CCleaning = '$completeCl', ";}
	  if(trim($completeCR) != '') { $do = $do."CRDate = '$completeCR', ";}
      $do = $do." cleaningType = '$jenisCl'
	              Where NoContainer = '$unit' And BookInID = '$bookInID'; 
			      Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	              Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 120), CONCAT('Update Repair and Cleaning ', '$unit', ' Book ID ', '$bookInID')); ";			 
      $result = mssql_query($do);
	  
	  $query = "Declare @LastMnr DATETIME;
	            Select @LastMnr = (Case When CCleaning > CRDate Then CCleaning
	                                    When CRDate > CCleaning Then CRDate
							            ELse Null
					               End)
				From containerJournal Where NoContainer = '$unit' And BookInID = '$bookInID';  
				
				If(@LastMnr != NULL) Begin
 				  Update containerJournal Set AVCond = @LastMnr, Cond = 'AV', isPending = 'N' 
				  Where NoContainer = '$unit' And BookInID = '$bookInID';				  
				End; ";
      $result = mssql_query($query);				
	  
	  $url = '/e-imp/mnr/?do=cont_list&noCnt='.$unit.'&location='.$wrkid;
	}
    else { $url = '/e-imp/mnr/?do=cont_list&noCnt='.$unit.'&location='.$wrkid.'&rejected=1'; }		

	echo "<script type='text/javascript'>location.replace('$url');</script>";       
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
	$cleaningType = '';
	$dtmCC = '';
	$dtmCR = '';
    
    $query = "Select Format(tanggalSurvey, 'yyyy-MM-dd') As tanggalSurvey, Surveyor, pendingRemark, isCleaning, isRepair, cleaningType, 
	          Format(CRDate, 'yyyy-MM-dd') As CRDate, Format(CCleaning, 'yyyy-MM-dd') As CCleaning, Cond
	          From containerJournal 
              Where NoContainer = '$unit' And bookInID = '$bookID'; ";
    $result = mssql_query($query);
    while($arr = mssql_fetch_array($result)) {
	  $dateSurvey = $arr['tanggalSurvey'];
      $surveyor = $arr['Surveyor'];
	  $statusPending = $arr['pendingRemark'];
	  $Cleaning = $arr['isCleaning'];
	  $Repair = $arr['isRepair'];
	  $cleaningType = $arr['cleaningType'];
	  $dtmCC = $arr['CCleaning'];
	  $dtmCR = $arr['CRDate'];
	  $Cond = $arr['Cond'];
    }
    mssql_free_result($result);
</script>

 <div class="w3-container">
  <table class="w3-table w3-bordered" style="background-color:#2196F3;font-weight:600">
   <tr style="color:#fff!important;">
	<td>MANAGE REPAIR AND CLEANING LOG</td>
   </tr>
  </table>
  <div class="height-10"></div>
  
  <fieldset style="padding-left:0 40px 0 40px; background-color:#f1f1f1"> 
   <legend style="background-color:#fff;font-size:13px" class="w3-text-grey">&nbsp;Maintenance and Repair&nbsp;</legend>
   <div class="height-10"></div>		 
   <label class="w3-text-grey">Notes For Date Field:<br>
    &nbsp;&nbsp;1.&nbsp;&nbsp;Enter a date without punctuation (i.e "-","/")<br>
	&nbsp;&nbsp;2.&nbsp;&nbsp;Date format yyyyMMdd (i.e 20171101).</label>
   <div class="height-20"></div>	
   
   <form method="get" action="hw_mnr.php">

<script language="php">   
    echo '<input type="hidden" name="act" value="mnrLog">
	      <input type="hidden" name="unit" value='.$unit.'>
		  <input type="hidden" name="dtmin" value='.$dtmin.'>
	      <input type="hidden" name="transid" value='.$bookID.'>
	      <input type="hidden" name="wrkid" value='.$location.'>';
</script>	

    <div class="w3-row-padding">
	 <div class="w3-third">
	 <label>Cleaning Type</label>

<script language="php">
	if($Cleaning == 1) {		
	  echo '<select name="cleaningType" class="w3-select w3-border">';
	  echo '<option value=" ">&nbsp;&nbsp;</option>';
      if($cleaningType == "HC") {echo '<option selected value="HC">&nbsp;HEAVY CLEANING&nbsp;</option>';}
	  else {echo '<option value="HC">&nbsp;HEAVY CLEANING&nbsp;</option>';}
	  if($cleaningType == "LC") {echo '<option selected value="LC">&nbsp;LIGHT CLEANING&nbsp;</option>';}
	  else {echo '<option value="LC">&nbsp;LIGHT CLEANING&nbsp;</option>';}
      echo '  </select>';
    }
    else {
	  echo '<select name="cleaningType" class="w3-select w3-border">
	        </select>';	
    }		
</script>	  

    </div>
    <div class="w3-third">  
     <label>Complete Cleaning on Date</label>			 

<script language="php">
    if($Cleaning == 1) {
      echo '<input class="w3-input w3-border" type="text" name="completeCleaning" id="fDate1" maxlength="8" value="'.$dtmCC.'" 
	        title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") />';			
	}
	else {
      echo '<input class="w3-input w3-border" type="text" name="completeCleaning" id="fDate1" maxlength="8" disabled />';					
	}	
</script>

     </div>
     <div class="w3-third">
      <label>Complete Repair on Date</label>			 
      <input class="w3-input w3-border" type="text" name="completeRepair" id="fDate2" maxlength="8" title="Year-Month-Date" onKeyUp="dateSeparator('fDate2')"
	        value=<?php echo $dtmCR; ?>>		  
     </div>
    </div>
    <div class="height-20"></div>
	
<script language="php">	
    if($Cond == 'DM') {
      echo '<div class="w3-container">
             <button type="submit" class="w3-button w3-pink">Save Log</button>
            </div>';
	}
</script>
	
   </form>
   
   <div class="height-10"></div>		     
  </fieldset>
 </div>

<script language="php">
  }
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