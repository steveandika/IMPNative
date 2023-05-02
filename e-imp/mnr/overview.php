<?php
session_start();
include("../asset/libs/db.php");  
include("../asset/libs/common.php");  

$kywrd = '';   
$kodeBooking = '';

if(isset($_GET["noCnt"]))    { $kywrd = strtoupper($_GET["noCnt"]); } 
//if(isset($_GET["loc"]))      { $loc = strtoupper($_GET["loc"]); } 	
if(isset($_POST["noCnt"]))   { $kywrd = strtoupper($_POST["noCnt"]); } 
if(isset($_POST["loc"]))     { $loc = strtoupper($_POST["loc"]); } 
if(isset($_POST["BookID"]))  { $kodeBooking = strtoupper($_POST["BookID"]); } 
if(isset($_GET['filterid'])) { $noCnt = $_GET['filterid']; }
if(isset($_GET['transid']))  { $kodeBooking = $_GET['transid']; }

if(isset($_POST["noCnt"]) && isset($_POST["formID"])) 
{			 
  if($_POST["formID"] =="fcontEvent" && $_POST['discharge'] == '') 
  {
	$dtmin = $_POST["eventDate"];
	$dateInPort = $_POST['dateInPort'];
	$mlo = $_POST["mlo"];
	$consg = $_POST["consignee"];
	$kodeBooking = $_POST["BookID"];
	$workshop = $_POST["location"];
	$vesselName = strtoupper($_POST['vesselName']);
	$voyageNo = strtoupper($_POST['voyageNo']);		
	$size = $_POST["contSize"];
	$tipe = $_POST["contType"];
	$height = $_POST["contHeight"];
	$mnfr = $_POST["mnfr"];
	$vent = $_POST["vent"];
	$constr = $_POST["constr"]; 
	
	$ubahKodeBooking=0;
	  if(substr($kodeBooking,0,3) == 'SLD' || substr($kodeBooking,0,3) == 'HMP') 
	  {
		$ubahKodeBooking=1;
		$bookIDNew=str_ireplace("-", "", $dtmin);
	  }	
	  else 
	  {			  
		if(strlen($kodeBooking) < 10) 
		{
		  $ubahKodeBooking=1;  
		  $bookIDNew=str_ireplace("-", "", $dtmin);
		}	
	  }	
	
	if($ubahKodeBooking==1) 
	{    
	  $kodeBooking = $_POST["BookID"];
	  $bookIDNew=substr($bookIDNew,0,1).substr($bookIDNew,2,6);
	  $bookIDNew=$workshop.$bookIDNew;
	  
	  $do="Declare @KeyField VarChar(30); 
		   If Not Exists(Select * From with (NOLOCK) logKeyField Where keyFName Like '".$bookIDNew.'%'."') Begin 
			  Set @KeyField=CONCAT('$bookIDNew','1');
			  Insert Into logKeyField Values('$bookIDNew', 1); 
		   End Else Begin
				  Declare @LastKey Int;	              
				  Update logKeyField Set lastNumber  =lastNumber +1 Where keyFName Like '".$bookIDNew.'%'."';                            
				  Select @LastKey=lastNumber +1 From logKeyField Where KeyFName Like '".$bookIDNew.'%'."'; 
				  Set @KeyField = CONCAT('".$bookIDNew."', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastKey)))); 					  
			   End;
		   Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
								 Values(@KeyField,0,'$kodeBooking','$mlo','$vesselName','$dtmin','','$voyageNo','$dtmin','');
		   Update containerJournal Set bookInID=@KeyField Where bookInID='$kodeBooking' And NoContainer='$kywrd';
		   Update RepairHeader Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd';
		   Update CleaningHeader Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd';
		   Update containerPhoto Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd';
		   
		   Select bookInID From containerJournal with (NOLOCK) Where bookInID=@KeyField And NoContainer='$kywrd'; ";
	  $rsl = mssql_query($do); 
	  
	  if($rsl) 
	  {
		$col=mssql_fetch_array($rsl);
		$kodeBooking=$col['bookInID'];
		//echo $kodeBooking.'<br>';
		mssql_free_result($rsl);
	  }				
	}			
	
	$do = "Update containerJournal Set gateIn = '$dtmin', GIPort='$dateInPort', workshopID='$workshop' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd';			 		
		   if Exists(Select * From containerLog with (NOLOCK) Where ContainerNo = '$kywrd') begin		
			 Update containerLog Set Constr = '$constr', Mnfr = '$mnfr', Ventilasi = '$vent', Size = '$size', Type = '$tipe', Height = '$height' Where ContainerNo = '$kywrd'; 			   
		   end else begin
				 insert into containerLog(COntainerNo,COnstr,Mnfr,Ventilasi,Size,type,Height) Values('$kywrd','$constr','$mnfr','$vent','$size','$tipe','$height');
			   end;			   
			   
		   Update tabBookingHeader Set principle = '$mlo', consignee='$consg', vessel='$vesselName', voyageID='$voyageNo' Where bookID = '$kodeBooking'; ";
	$result = mssql_query($do);	     
	
	if($result) 
	{ 
	  $message="Log updated";
	  echo "<script type='text/javascript'>alert('$message');</script>"; 
	}
  }
 
  if($_POST["formID"] == 'fcontSurvey' && $_POST['discharge'] == '') 
  {	
	$dtmsurvey = $_POST["eventDate"];	 
	$surveyor = strtoupper($_POST["surveyor"]);
	$remark = strtoupper($_POST["surveyRemark"]);
	
	
	if(isset($_POST["iscleaning"]) && $_POST["iscleaning"] == 'on') { $needCleaning = 1; }
	else { $needCleaning = 0; }
	if(isset($_POST["isrepair"]) && $_POST["isrepair"] == 'on')  { $needRepair = 1; } 
	else { $needRepair = 0; }
	
	$kodeBooking = $_POST["BookID"];

	$isOk = 1;
	$query = "Select Format(gateIn,'yyyy-MM-dd') As gateIn From containerJournal Where NoContainer= '$kywrd' And bookInID='$kodeBooking' and gateIn Is Not Null;";	   
	$result = mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $arr = mssql_fetch_array($result);
	  $dtmin = $arr["gateIn"];
	}
	else { $isOk = 0; }	   	   
	mssql_free_result($result);

	if($isOk == 1) 
	{	   
	  $do = "Update containerJournal Set tanggalSurvey = '$dtmsurvey', isCleaning = 1, isRepair = 1, pendingRemark = '$remark', Surveyor = '$surveyor'
			  Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
	  $result = mssql_query($do);		  
	  if($result) 
	  { 
		$message="Survey Log updated";
		echo "<script type='text/javascript'>alert('$message');</script>"; 
	  }		  
	}
	else 
	{ 	    
	  $message="Found invalid data input while update process. Recheck your data input.";
	  echo "<script type='text/javascript'>alert('$message');</script>"; 
	}
  }		
  
  if($_POST["formID"] == "fcontCleaning" && $_POST['discharge'] == '') 
  {
	$kodeBooking = $_POST["BookID"];
	$cleaning = $_POST["cleaningType"];	  
	$tglCleaning = $_POST["datecleaning"];
	$dpp = $_POST["dpp"];		
	if(trim($dpp) == '') { $dpp = 0; }
	$nilaiMaterial = $_POST["nilaiMaterial"];
	if(trim($nilaiMaterial) == "") { $nilaiMaterial = $dpp; }
	
	$postTgl = trim(str_ireplace("-","",$tglCleaning));
	$postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));

	if($cleaning=="WW") { $remark = "LIGHT CLEANING"; }
	if($cleaning=="DW") { $remark = "MEDIUM CLEANING"; }
	if($cleaning=="CC") { $remark = "HEAVY CLEANING"; }
	if($cleaning=="SC") { $remark = "SPECIAL CLEANING"; }
	if($cleaning=="SW") { $remark = "SWEEP OUT FLOOR PLYWOOD"; }
   
	$location = '';
	$query = "Select workshopID From containerJournal with (NOLOCK) Where NoContainer='$kywrd' And BookinID='$kodeBooking'";
	$result = mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $arrResl = mssql_fetch_array($result);		
	  $location = $arrResl["workshopID"];
	}
	mssql_free_result($result);	   

	$query = "If Not Exists(Select bookID From CleaningHeader With (NOLOCK) Where containerID='$kywrd' And bookID='$kodeBooking') Begin
				 Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
				 Set @ToDay_ = '$tglCleaning'; 
				 Set @Keywrd=CONCAT('$postTgl','%');
			
				 If Exists(Select keyFName From logKeyField With (NOLOCK) Where keyFName Like @Keywrd) Begin 
				   Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
				   Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
				   Set @NewDraft=CONCAT('$postTgl', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
			  
				 End Else Begin 
					  Insert Into logKeyField(keyFName, lastNumber) Values('$postTgl', 1);
					  Set @NewDraft=CONCAT('$postTgl', '.1');
					 End;
		  
				 Insert Into CleaningHeader(cleaningID, containerID, cleaningDate, nilaiDPP, bookID, invoiceNumber) 
									 Values(@NewDraft, '$kywrd', @Today_, $dpp, '$kodeBooking', '');			 
				 Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
									 Values(@NewDraft, '$location', $nilaiMaterial, '$remark', '$cleaning');
									 
			 End Else Begin
				   Declare @IndexRec VarChar(30);
				   Select @IndexRec = cleaningID From CleaningHeader Where containerID='$kywrd' And bookID='$kodeBooking';
				   
				   Update CleaningDetail Set repairID = '$cleaning', Remarks = '$remark', materialValue=$nilaiMaterial Where cleaningID = @IndexRec;
				   Update CleaningHeader Set cleaningDate = '$tglCleaning',  nilaiDPP = $dpp Where cleaningID = @IndexRec;
				   Update containerJournal Set CCleaning= '$tglCleaning' Where NoContainer='$kywrd' And bookInID='$kodeBooking'; 
				 End; ";
	$result=mssql_query($query);
	
	$do="Update containerJournal Set cleaningType='$cleaning' Where NoContainer='$kywrd' And bookInID='$kodeBooking'; ";
	$result = mssql_query($do);
	if($result) 
	{ 
	  $message="Log updated";
	  echo "<script type='text/javascript'>alert('$message');</script>"; 
	}
  }
  
  if($_POST["formID"]=="fcontRepair" && $_POST["discharge"]=="") 
  {
	$cr ="";
	$tglApprove ="";	   
	$kodeBooking = $_POST["BookID"];
	$tglEOR = $_POST["submittedEOR"];
	$nilaiDPP = $_POST["dpp"];
	$cr = $_POST["CR"];
	$NoEst = strtoupper($_POST["noEstimate"]);
	$tglApprove = $_POST["EORapproved"];
	$nilaiMH = $_POST["manhour"];
	$labor=$_POST["laborVal"];
	
	$do ="";	
	if(trim($NoEst) !="" && strtotime($tglEOR) <= strtotime(date("Y-m-d"))) 
	{	   
	  if(substr($NoEst,0,3) !="REP") 
	  {
		$do = "If Not Exists(Select estimateID From RepairHeader with (NOLOCK) Where estimateID='$NoEst') Begin 		  
				Insert Into RepairHeader(estimateID, containerID, estimateDate, nilaiDPP, totalHour, totalLabor, totalMaterial, totalOwner, totalUser, 
										 laborRate, invoiceNumber, bookID, isValid)
								  Values('$NoEst', '$kywrd', '$tglEOR', $nilaiDPP, $nilaiMH, $labor, $nilaiDPP-$labor, 0, 0, 0, '', '$kodeBooking', 1);
			   End Else Begin
					 Update RepairHeader Set estimateID='$NoEst', estimateDate='$tglEOR', nilaiDPP=$nilaiDPP, totalHour=$nilaiMH, totalMaterial=$nilaiDPP-$labor, totalLabor=$labor 
					  Where estimateID='$NoEst'; 
				   End; ";		
		$res=mssql_query($do);	
		if($res) { 
		  $message="Log updated.";
		  echo "<script type='text/javascript'>alert('$message');</script>"; 
		}			
	  }
	  else 
	  { 
		$message="EOR Number Field is only for Manual EOR (Upload)";
		echo "<script type='text/javascript'>alert('$message');</script>"; 
	  }
	}
	else
	{
		if(strtotime($tglEOR) > strtotime(date("Y-m-d")))
		{
			$message="Invalid date entry.";
			echo "<script type='text/javascript'>alert('$message');</script>"; 
		}			
	}		
   
	if(trim($tglApprove) !="") 
	{
	  $do = "Update repairHeader Set tanggalApprove = '$tglApprove',statusEstimate= 'APPROVE' 
			 Where containerID='$kywrd' And bookID='$kodeBooking' And estimateID='$NoEst'; "; 
	  $res = mssql_query($do);		 
	  if($res) 
	  { 
		$message="Approval Date updated";
		echo "<script type='text/javascript'>alert('$message');</script>"; 
	  }
	}
   
	if(trim($cr) != '') 
	{
	  $do = "Update containerJournal Set CRDate = '$cr' Where bookInID = '$kodeBooking' 
			And NoContainer = '$kywrd' ";
	  $res = mssql_query($do);	  
	  if($res) 
	  { 	    
		$message="Complete Repair Date updated";
		echo "<script type='text/javascript'>alert('$message');</script>"; 		  		
	  }
	}
  }
}

if(isset($_POST['formID'])) 
{
	if($_POST["formID"] == 'fcontRepair' || $_POST["formID"] == "fcontCleaning") 
	{
		$query = "Select Format(CCleaning, 'yyyy-MM-dd') As CCleaning, Format(CRDate, 'yyyy-MM-dd') As CR, isCleaning, isRepair
					From containerJournal Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
		$resl = mssql_query($query);
		$arrfetch = mssql_fetch_array($resl);
		$tglCeaning = $arrfetch["CCleaning"];
		$Cleaning = $arrfetch["isCleaning"];
		$Repair = $arrfetch["isRepair"];
		$tglCR = $arrfetch["CR"];
		mssql_free_result($resl);
  
		if(strtotime($tglCR) >= strtotime($tglCleaning)) { $avcond=$tglCR; }
		if(strtotime($tglCR) < strtotime($tglCleaning)) { $avcond=$tglCleaning; }
		
		$do="Update containerJournal Set AVCond='$avcond', Cond='AV', isPending='N', gateOut='$avcond' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
		$rslExec=mssql_query($do);	   		  		  	  

		$datecr="";
		$kodeEstimate="";
		$nilaiEstimate=0;
		$qry="Select a.nilaiDPP As DPPCleaning, ISNULL(b.nilaiDPP,0) As DPPEstimate, b.estimateID
				From CleaningHeader a 
				Left Join RepairHeader b On b.containerID = a.containerID And b.BookID = a.BookID
				Where a.containerID='$kywrd' And a.bookID='$kodeBooking' ";
		$rsl=mssql_query($qry);
		if(mssql_num_rows($rsl) >0) 
		{
			$col=mssql_fetch_array($rsl);	
			if($col['DPPEstimate'] >0) 
			{
				if($col['DPPCleaning']>0 && $col['DPPEstimate']==$col['DPPCleaning']) 
				{ 
					$datecr=$tglCleaning; 
					$kodeEstimate=$col['estimateID'];
					$nilaiEstimate=$col['DPPEstimate'];
				}	  
			}
		}
		mssql_free_result($rsl);
	
		if($datecr!="" && $datecr!="1900-01-01") 
		{
			$do="Update containerJournal Set isRepair=1, CRDate='$datecr', Cond='AV', gateOut='$tglCleaning', isPending='N', AVCond='$tglCleaning' Where NoContainer='$kywrd' And bookInID='$kodeBooking'		";	
			$rsl=mssql_query($do);
		}
		
		if($kodeEstimate!="") 
		{
			$do="Update RepairHeader Set FinishRepair='$datecr' Where estimateID='$kodeEstimate' And containerID='$kywrd' And nilaiDPP=$nilaiEstimate";	
			$rsl=mssql_query($do);			
		}
  
/*	  $tglAV = '';
  if($Cleaning == 1 && $Repair == 1) {
	if(trim($tglCleaning) != '' && $tglCleaning != '1900-01-01' && trim($tglCR) != '' && $tglCR != '1900-01-01') {
	  if(strtotime($tglCleaning) >= strtotime($tglCR)) { $tglAV = $tglCleaning; }
	  if(strtotime($tglCR) >= strtotime($tglCleaning)) { $tglAV = $tglCR; }
	 
	  //$do = "Update containerJournal Set Cond='AV', AVCond='$tglAV', gateOut='$tglAV' Where bookInID='$kodeBooking' And NoContainer='$kywrd'; ";
	  //$exeQuery = mssql_query($do);		
	 // if($exeQuery) { echo '<script>swal("Container Condition is AV")</script>'; }		  
	}		   
  }	 
  
  if($Cleaning == 1 && $Repair == 0) {
	if(trim($tglCleaning) != '' && $tglCleaning != '1900-01-01') {
	  $tglAV = $tglCleaning;	 
	  $do = "Update containerJournal Set Cond='AV', AVCond='$tglAV', gateOut='$tglAV' Where bookInID='$kodeBooking' And NoContainer='$kywrd'; ";
	  $exeQuery = mssql_query($do);		 		  
	  if($exeQuery) { echo '<script>swal("Container Condition is AV")</script>'; }		  
	}  
  }	  
  
  if($Cleaning == 0 && $Repair == 1) {
	if(trim($tglCR) != '' && $tglCR != '1900-01-01') {
	  $tglAV = $tglCR;	 
	  $do = "Update containerJournal Set Cond='AV', AVCond='$tglAV', gateOut='$tglAV' Where bookInID='$kodeBooking' And NoContainer='$kywrd'; ";
	  $exeQuery = mssql_query($do);		 		  
	  if($exeQuery) { echo '<script>swal("Container Condition is AV")</script>'; }		  
	}  
  }	  	  	      
  */
	}   
}

$principle="";
$consignee="";	
$gateOutHamparan="";
$gateInHamparan="";
$dateInPort="";
$needCleaning = 1;
$needRepair = 1;	   
$tglCR="";
$tglAVCond="";
$tglSurvey="";
$tglCleaning="";
$workshopID="";
$vesselName="";
$voyageNo="";
$noEstimate="";
$tglSubmitted="";
$DPP = 0;
$tglApprove="";
$dirNamePDF="";	
$MH = 0;
$Cond="";
$mnfr="";
$constr="";
$vent="";
$Size="";
$Type="";
$Height="";

$do="UPDATE containerJournal SET gateOut=NULL WHERE gateOut='1900-01-01' AND bookInID='$kodeBooking';
	 UPDATE CleaningHeader SET cleaningDate=NULL WHERE cleaningDate='1900-01-01' AND bookID='$kodeBooking'; ";
$rsl=mssql_query($do);

$do = "Select NoContainer, Format(gateIn, 'yyyy-MM-dd') As gateIn, Size, Type, Height, Format(GIPort, 'yyyy-MM-dd') As dateInPort,
			  Cond, Format(tanggalSurvey, 'yyyy-MM-dd') As surveyDate, Surveyor, 
			  CASE WHEN tanggalSurvey Is Null THEN 0
				   ELSE 1
			  END As flagSurvey,
			  Format(CRDate, 'yyyy-MM-dd') As CRDate, Format(CCleaning, 'yyyy-MM-dd') As CCleaning, 				
			  Format(AVCond, 'yyyy-MM-dd') As AVCond,
			  CASE WHEN CCleaning Is Null THEN 0
				   ELSE 1
			  END As flagCleaning, 				  
			  c.principle, c.consignee, a.bookInID, a.cleaningType, a.pendingRemark,
			  b.Constr, b.Mnfr, b.Ventilasi, IsNull(a.isCleaning,0) As isCleaning, IsNull(a.isRepair, 0) As isRepair,
			  a.bookInID, d.locationDesc As workshopID, c.vessel, c.voyageID, Format(gateOut, 'yyyy-MM-dd') As gateOut, Cond				 
	   From containerJournal a 
	   Inner Join containerLog b On b.ContainerNo = a.NoContainer
	   Inner Join tabBookingHeader c On c.BookId = a.bookInID
	   Inner Join m_Location d On d.locationID=a.workshopID
	   Where NoContainer='$kywrd' And a.bookInID ='$kodeBooking'; ";    
$resldo=mssql_query($do);

if(mssql_num_rows($resldo) <= 0) 
{
	echo '<h3 style="padding:5px 0 5px 0;background:#2196F3;color:#fff;margin-top:0!important">&nbsp;&nbsp;Container Overview : <?php echo $kywrd?></h3>
		  <div class="w3-container w3-animate-zoom" style="font-size:.840rem!important;padding:10px;background:#f7f9f9;overflow-y:scroll;max-height:300px;">    
		   <div class="height-10"></div>
		   <p style="letter-spacing:1px;color:red;font-weight:bold;font-size:13px">0 Record has been found.</p>
		  </div>
		  <div class="height-10"></div>';			  	  
}    
else 
{		
  $arr = mssql_fetch_array($resldo);
  $principle = haveCustomerName($arr["principle"]);
  $consignee = haveCustomerName($arr["consignee"]);	
  $needCleaning = $arr["isCleaning"];
  $needRepair = $arr["isRepair"];	   
  $kodeBooking = $arr["bookInID"];
  $NoContainer = strtoupper($kywrd);
  $tglCR = $arr["CRDate"];
  $tglAVCond = $arr["AVCond"];
  $tglSurvey=$arr["surveyDate"];
  $workshopID=$arr["workshopID"];
  $vesselName=$arr['vessel'];
  $voyageNo=$arr['voyageID'];
  $Cond=$arr['Cond'];
  $gateInHamparan=$arr['gateIn'];
  $dateInPort=$arr['dateInPort'];
  $gateOutHamparan=$arr['gateOut'];
  $Cond=$arr['Cond'];
  $mnfr = $arr['Mnfr'];
  $constr = $arr['Constr'];
  $vent = $arr['Ventilasi'];
  $Size = $arr['Size'];
  $Type = $arr['Type'];
  $Height = $arr['Height'];	  
}	  
?>

<div id="FORMEvent" class="w3-container">
	<label style="font: 600 12px/25px Tahoma, Helvetica, sans-serif;">Container Profile and Captured Event</label>

	<div class="w3-row-padding" >
	 <div class="w3-half ">Ticket No.</div>
	 <div class="w3-half"><?php echo $kodeBooking?></div>
	</div> 
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding" >
	 <div class="w3-half ">Size/Type/Height</div>
	 <div class="w3-half"><?php echo $Size.' / '.$Type.' / '.$Height ?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding" >
	 <div class="w3-half ">Mnfr./Constr./Vent.</div>
	 <div class="w3-half"><?php echo $mnfr.' / '.$constr.' / '.$vent ?></div>
	</div> 
	<div class="height-5" style="border-top:1px dotted #ddd"></div>	  

	<div class="w3-row-padding">
	 <div class="w3-half ">Port In</div>
	 <div class="w3-half"><?php echo $dateInPort?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Workshop In</div>
	 <div class="w3-half"><?php echo $gateInHamparan?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Principle</div>
	 <div class="w3-half"><?php echo $principle?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>			

	<div class="w3-row-padding">
	 <div class="w3-half ">Ex. User</div>
	 <div class="w3-half"><?php echo $consignee?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Workshop Name</div>
	 <div class="w3-half"><?php echo $workshopID?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Ex. Vessel/Voyage</div>
	 <div class="w3-half"><?php echo $vesselName.' '.$voyageNo?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding" >
	 <div class="w3-half ">Workshop Out</div>
	 <div class="w3-half"><?php echo $gateOutHamparan?></div>
	</div>

	<div class="height-10"></div>		
	<div class="w3-row-padding">
		<div class="w3-quarter">
			<form id="fcntEvent" method="post">
				<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />
				<input type="hidden" name="bookID" value="<?php echo $kodeBooking?>" />
				<input type="hidden" name="loc" value="<?php echo $loc?>" />					 
				<button type="submit" class="w3-button w3-green">Event Log</button>
			</form>
		</div>
	 <div class="w3-quarter">		 
	  <?php		 		    
		if(($tglSurvey == '' || $tglSurvey == '1900-01-01') && $_SESSION["allowDelete"] == 1) 
		{
		  echo '<form id="fHapusCont" method="post"> 
				 <input type="hidden" name="noCnt" value="'.$kywrd.'" />
				 <input type="hidden" name="bookID" value="'.$kodeBooking.'" />
				 <input type="hidden" name="loc" value="'.$loc.'" />
				 <input type="hidden" name="formID" value="fHapusCont" />
				 <button type="submit" class="w3-button w3-red">Remove</button> 
				</form>';	 
		} 
		else 
		{ 
			echo '<button type="submit" disabled class="w3-button w3-red">Remove</button>';  
		}	
	  ?>	
	 </div>
	 <div class="w3-quarter"></div>
	 <div class="w3-quarter"></div>
	</div>
</div>
<div class="height-5"></div>
  
<div id="FORMLog"  class="w3-container">   
	<label style="font: 600 12px/25px Tahoma, Helvetica, sans-serif;">Survey Log</label>			  		
	<div class="w3-row-padding">
	 <div class="w3-half ">Survey Date</div>
	 <div class="w3-half"><label><?php echo $tglSurvey;?></label></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Surveyor</div>
	 <div class="w3-half"><label><?php echo $arr["Surveyor"]?></label></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>
	
	<div class="w3-row-padding">
	 <div class="w3-half ">Remark</div>
	 <div class="w3-half"><label><?php echo $arr["pendingRemark"]?></label></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="height-10"></div>		
	<div class="w3-container">
	  <form id="fcntsurvey" method="post">
		<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />
		<input type="hidden" name="bookID" value="<?php echo $kodeBooking?>" />
		<button type="submit" class="w3-button w3-green">Survey Log</button>
	  </form>
	</div>	  	  
</div><div class="height-5"></div>
	
<?php 
	$related_Estimate_ID = "";
	$tglCleaning = "";
	$cleaningType = "";
	$related_Estimate_ID = "";
	
	$logcleaning ="SELECT Format(a.CCleaning, 'yyyy-MM-dd') As tglCleaning, a.cleaningType, b.estimateID 
				   FROM containerJournal a
	  			   INNER JOIN CleaningHeader b On b.bookID=a.bookInID
				   WHERE NoContainer='$kywrd' And bookInID='$kodeBooking' ";
	$reslog = mssql_query($logcleaning);
	if(mssql_num_rows($reslog) > 0) { 
	   $arrlog = mssql_fetch_array($reslog);
	   $tglCleaning = $arrlog["tglCleaning"];			  
	   $cleaningType=$arrlog["cleaningType"];
	   $related_Estimate_ID=$arrlog["estimateID"];
	}
	mssql_free_result($reslog);			

	$found_rsl=1;
	if($related_Estimate_ID!="" && (substr($related_Estimate_ID,0,3)=="REP" || substr($related_Estimate_ID,0,3)=="DRF")) {
	   $logcleaning="SELECT idItem FROM RepairDetail
					 WHERE estimateID='$related_Estimate_ID' AND repairID='$cleaningType'";
	   $reslog=mssql_query($logcleaning);
	   $found_rsl=mssql_num_rows($reslog);
	   mssql_free_result($reslog);			  
	}
	 
	if($found_rsl > 0) {
	   $dppCleaning = 0;
	   $logcleaning = "Select nilaiDPP From CleaningHeader Where containerID='$kywrd' And bookID='$kodeBooking' ";
		 
	   $reslog = mssql_query($logcleaning);
	   if(mssql_num_rows($reslog) > 0) {
		 $arrlog = mssql_fetch_array($reslog);
		 $dppCleaning = $arrlog["nilaiDPP"];
	  }
	  mssql_free_result($reslog);
	}			 
?>		

<div id="FORMCleaning" class="w3-container">
	 <label style="font: 600 12px/25px Tahoma, Helvetica, sans-serif;">Cleaning Log</label>
	   
	 <div class="w3-row-padding">
		<div class="w3-half ">Finish Cleaning</div>
		<div class="w3-half"><?php echo $tglCleaning?></div>
	   </div>
	   <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
	   <div class="w3-row-padding">
		<div class="w3-half ">Cleaning Type</div>
		<div class="w3-half"><?php echo $cleaningType?></div>
	   </div>
	   <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
	  <div class="w3-row-padding">
	   <div class="w3-half ">Total Before Tax</div>
	   <div class="w3-half" style="text-align:right"><?php echo number_format($dppCleaning,2,",",".")?></div>
	  </div>
	  <div class="height-5" style="border-top:1px dotted #ddd"></div>

	  <div class="height-10"></div>		
	  <div class="w3-container">
	   <form id="fcntCleaning" method="post">
		<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />			         
		<input type="hidden" name="loc" value="<?php echo $loc?>" />		
		<input type="hidden" name="kodeBook" value="<?php echo $kodeBooking?>" />			
		<button type="submit" class="w3-button w3-green">Cleaning Log</button>					 
	   </form>
	  </div>	   
</div><div class="height-10"></div>

<?php 
	 $noEstimate='';
	 $tglSubmitted='';
	 $DPP=0;
	 $dirNamePDF='';
	 $MH=0;
	 $Labor=0;
	 $adm="";
	 $logrepair="Select * from vw_MNRHeader
				 Where bookID='$kodeBooking' And containerID='$kywrd'; ";		
	 $reslog = mssql_query($logrepair);	
	 if(mssql_num_rows($reslog) > 0) {	
	   $arrlog = mssql_fetch_array($reslog);		 

	   $noEstimate = $arrlog["estimateID"];
	   $tglSubmitted = $arrlog["estimateDate"];
	   $DPP = $arrlog["nilaiDPP"];			  
	   $tglApprove = $arrlog["tglApprove"];
	   $dirNamePDF = $arrlog["dirname"];
	   $MH = $arrlog["totalHour"];
	   $Labor= $arrlog["totalLabor"];
	   $adm=$arrlog["completeName"];
	 }		
	 mssql_free_result($reslog);			

	 if(substr($noEstimate,0,3)=="REP" || substr($noEstimate,0,3)=="DRF") { 
	   $query="Select ISNULL(SUM(hoursValue),0) As TotalHours, ISNULL(SUM(laborValue),0) As TotalLabor, ISNULL(SUM(materialValue),0) As TotalMaterial, 
					  ISNULL(SUM(totalValue),0) As TotalEOR From RepairDetail Where estimateID='$noEstimate'";
	   $result=mssql_query($query);
	   while($arr=mssql_fetch_array($result)) {
		  $DPP=$arr[3];
		  $totalMH=$arr[0];
		  $totalLabor=$arr[1];
		  $totalMaterial=$arr[2]; 
	   }			
	   mssql_free_result($result);
	   
	   $do="Update Repairheader Set nilaiDPP=$DPP, totalHour=$totalMH, totalLabor=$totalLabor, totalMaterial=$totalMaterial 
			 Where estimateID='$noEstimate' ";
	   
	   $res_exec=mssql_query($do);								  
	 }
			 
?>
	   
<div id="FORMCleaning" class="w3-container">	          
	<label style="font: 600 12px/25px Tahoma, Helvetica, sans-serif;">Estimate/Repair Log</label>
	
	<div class="w3-row-padding">
	 <div class="w3-half ">Estimate Number</div>
	 <div class="w3-half"><?php echo $noEstimate?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>
  
	<div class="w3-row-padding">
	 <div class="w3-half ">Submitted Date</div>
	 <div class="w3-half"><?php echo $tglSubmitted?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half">Submitted By</div>
	 <div class="w3-half"><?php echo $adm?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>
	
	<div class="w3-row-padding">
	 <div class="w3-half ">Approved Date</div>
	 <div class="w3-half"><?php echo $tglApprove?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
	<div class="w3-row-padding">
	 <div class="w3-half ">Total Estimate Before Tax</div>
	 <div class="w3-half" style="text-align:right"><?php echo number_format($DPP,2,",",".")?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Total M/H</div>
	 <div class="w3-half" style="text-align:right"><?php echo number_format($MH,2,",",".")?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>

	<div class="w3-row-padding">
	 <div class="w3-half ">Labor Value</div>
	 <div class="w3-half" style="text-align:right"><?php echo number_format($Labor,2,",",".")?></div>
	</div>
	<div class="height-5" style="border-top:1px dotted #ddd"></div>
	
	<div class="w3-row-padding">
	 <div class="w3-half ">Finished Repair DTTM</div>
	 <div class="w3-half"><?php echo $tglCR?></div>
	</div>				  
			  
	<div class="height-10"></div>
	<div class="w3-row-padding">
	 <div class="w3-half">
	   <form id="fcntRepair" method="post">
		<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />			         
		<input type="hidden" name="kodeBooking" value="<?php echo $kodeBooking?>" />	
		<input type="hidden" name="loc" value="<?php echo $loc?>" />			
		<input type="hidden" name="estNum" value="<?php echo $noEstimate?>" />			
		<button type="submit" <?php if($tglSurvey=="" && $tglSurvey!="1900-01-01") { echo "disabled"; }?> 
			<?php if($principle=="") { echo "disabled"; }?>
			class="w3-button w3-green">Repair/Estimate Log
		</button>
	   </form>
	 </div>	
	 <div class="w3-half">
	 <?php 		
	   if($noEstimate!="" && substr($noEstimate,0,3)!="REP" && substr($noEstimate,0,3)!="DRF" && trim($principle)!="" && $workshopID!="" && $tglSurvey!="" && $tglSurvey!="1900-01-01") {
	 ?>	  
		<form name="fuploadEOR" method="get" action="attchFile" target="popUpW" 
				onsubmit="window.open('','popUpW','directories=0,location=0,menu=0,scrollbars=1,resizable=0,width=700,height=600,toolbar=0')">			 
		  <input type="hidden" name="noCnt" value="<?php echo $kywrd;?>" />			         
		  <input type="hidden" name="kodeBook" value="<?php echo $kodeBooking?>" />						  
		  <button type="submit" class="w3-button w3-blue">Upload Estimate</button>
		</form>
	 <?php		  
	   }
	   if(trim($noEstimate)=="" && trim($principle)!="" && $workshopID!="" && $tglSurvey!="" && $tglSurvey!="1900-01-01") {
		echo '<form id="fCreateEOR" method="get">
				<input type="hidden" name="noCnt" value="'.$kywrd.'" />			         
				<input type="hidden" name="kodeBooking" value="'.$kodeBooking.'" />			         
				<input type="hidden" name="loc" value="'.$loc.'" />
				<input type="hidden" name="noCntFilter" value="'.$kywrd.'" />
				<button type="submit" class="w3-button w3-light-grey">Create Estimate</button>	
			  </form>';
	   }					                    
	   if((substr($noEstimate,0,3)=="REP" || substr($noEstimate,0,3)=="DRF")) {
	 ?>			   
		<form id="fCreateEOR" method="post">
				<input type="hidden" name="have" value="estimate" />			           
				<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />			         
				<input type="hidden" name="kodeBooking" value="<?php echo $kodeBooking?>" />			         
				<input type="hidden" name="kodeEstimate" value="<?php echo $noEstimate?>" />
				<button type="submit" <?php if($principle=="") { echo "disabled"; }?> class="w3-button w3-blue">View Estimate</button>	
		</form>					
	 <?php 		  
	   }							
	 ?>  		 
	 </div>
	</div>	
</div>
<div class="height-10"></div>

<?php if($noEstimate!="" && substr($noEstimate,0,3)!="REP" && substr($noEstimate,0,3)!="DRF" && $dirNamePDF!="") {
	   
		$filePost = "doc/".$dirNamePDF;			  
		echo '<embed type="application/pdf" src="'.$filePost.'" width=900 height=700 />';
		   //include("view_pdf.php");
	  }
?>
 
<div style="max-width:500px,margin:0 auto;">
    <div id="edit_content"></div> 
</div>  

<?php
	mssql_close($dbSQL);
?>

<script type="text/javascript">  
  $(document).ready(function(){	  
    $("#backToList").submit(function(event){
      event.preventDefault();
	  $('#loader-icon').show();
      var formValues = $(this).serialize();
      $.post("mnguntFiltered.php", formValues, function(data){ $('#loader-icon').hide(); $("#mnr_content").html(data); });
    });
	
    $("#fHapusCont").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fHapusCont.php", formValues, function(data){ $("#content").html(data); });
    });
	
    $("#fcntprofile").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntprofile.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "mnr_form";
    });
    
    $("#fcntEvent").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntEvent.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "mnr_form";
    }); 	

    $("#fcntsurvey").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntSurvey.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "mnr_form";
    }); 	
	
    $("#fcntCleaning").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntCleaning.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "mnr_form";
    }); 		
	
    $("#fcntRepair").submit(function(event){
	  $('#loader-icon').show();	
      event.preventDefault();		
      var formValues = $(this).serialize();
      $.post("fcntCRepair.php", formValues, function(data){ $("#mnr_form").html(data); });
	  $('#loader-icon').hide();
	  window.location.hash = "mnr_form";
    }); 	

    $("#fCreateEOR").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("estimate.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "mnr_form";
    }); 
  });  
</script>    