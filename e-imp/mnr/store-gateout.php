<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  $valid = 0;
  if($_POST['nocontainer']) {
    $keywrd = $_POST['nocontainer'];
	
	$dtmout = $_POST['dtmout'];
	$kodeBooking = $_POST['kodeBooking'];
	$timeOut = date("h:i");
	$valid = 1;
	
	$qry="Select Format(gateIn, 'yyyy-MM-dd') As DTMIn From containerJournal Where NoContainer='$keywrd' And bookInID='$kodeBooking'; ";
	$rsl=mssql_query($qry);
	$cols=mssql_fetch_array($rsl);
	$dateInHamparan=$cols['DTMIn'];
	mssql_free_result($rsl);
	
	if(strtotime($dateInHamparan) >= strtotime($dtmout)) { $valid = 0; }
			
	if($valid==1) {		
	  $remark = "GATE OUT_".$keywrd."_TICKET_NUMBER_".$kodeBooking;
	  $do = "If Exists(Select * From containerJournal Where NoContainer='$keywrd' And bookInId='$kodeBooking' And gateOut Is NULL) Begin 
       	      Update containerJournal Set isBookOut=1, BookOutID='', gateOut='$dtmout', JamOut='$timeOut', TruckingOut='', VehicleOutNumber='' 
			   Where NoContainer='$keywrd' And bookInID='$kodeBooking'; 
			
  		      Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('".$_SESSION['uid']."', GETDATE(), '$remark'); 
		     End; ";	
	  $result = mssql_query($do);		
      if($result) { $valid = 1; }
	  else { $valid = 0; }
    }
  
    mssql_close($dbSQL);  
  }	
  
  $url = '/e-imp/mnr/?do=gateout&valid='.$valid;
  echo "<script type='text/javascript'>location.replace('$url');</script>";      
</script>