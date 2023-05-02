<script language="php">
    session_start();
    include("../asset/libs/db.php");  
    include("../asset/libs/common.php");  
   
    $kywrd = '';   
	$kodeBooking = '';
	
	if(isset($_GET["noCnt"]))    { $kywrd = strtoupper($_GET["noCnt"]); } 
	if(isset($_GET["loc"]))      { $loc = strtoupper($_GET["loc"]); } 	
	if(isset($_POST["noCnt"]))   { $kywrd = strtoupper($_POST["noCnt"]); } 
	if(isset($_POST["loc"]))     { $loc = strtoupper($_POST["loc"]); } 
	if(isset($_GET['filterid'])) { $noCnt = $_GET['filterid']; }
	if(isset($_GET['transid'])) { $kodeBooking = strtoupper($_GET['transid']); }

    if(isset($_POST["noCnt"]) && isset($_POST["formID"])) 
	{			 
      if($_POST["formID"] == 'fcontEvent' && $_POST['discharge'] == '') 
	  {
	    $dtmin = $_POST["eventDate"];
		$dateInPort = $_POST['dateInPort'];
	    $mlo = $_POST["mlo"];
	    $consg = $_POST["consignee"];
	    $kodeBooking = $_POST["BookID"];
		$workshop = $_POST["location"];
	    $vesselName = $_POST['vesselName'];
	    $voyageNo = $_POST['voyageNo'];		
	    $size = $_POST["contSize"];
	    $tipe = $_POST["contType"];
	    $height = $_POST["contHeight"];
	    $mnfr = $_POST["mnfr"];
	    $vent = $_POST["vent"];
	    $constr = $_POST["constr"]; 
		
        $do = "Update containerJournal Set gateIn = '$dtmin', GIPort='$dateInPort', workshopID='$workshop' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd';			 
		
               if Exists(Select * From containerLog Where ContainerNo = '$kywrd') begin		
                 Update containerLog Set Constr = '$constr', Mnfr = '$mnfr', Ventilasi = '$vent', Size = '$size', Type = '$tipe', Height = '$height' Where ContainerNo = '$kywrd'; 			   
			   end else begin
			         insert into containerLog(COntainerNo,COnstr,Mnfr,Ventilasi,Size,type,Height) Values('$kywrd','$constr','$mnfr','$vent','$size','$tipe','$height');
                   end;			   
				   
	           Update tabBookingHeader Set principle = '$mlo', consignee='$consg', vessel='$vesselName', voyageID='$voyageNo' Where bookID = '$kodeBooking';
			   
	           Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Container Event ',' $kywrd',' ','$kodeBooking')); ";
	    $result = mssql_query($do);	     
		
		$do='Update containerJournal Set GIPort=Null Where GIPort="1900-01-01"; ';
		$resl=mssql_query($do);
		
	    if($result) { echo '<script>swal("","Log Event has been updated")</script>'; }
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
	    if(mssql_num_rows($result) > 0) 
		{
	      $arr = mssql_fetch_array($result);
		  $dtmin = $arr["gateIn"];
	    }
        else { $isOk = 0; }	   	   
	    mssql_free_result($result);
/*	   
	    if($isOk == 1) 
		{
	      if(strtotime($dtmin) > strtotime($dtmsurvey)) { $isOk = 0; }
	    }
	   */
	    if($isOk == 1) 
		{	   
	      $do = "Update containerJournal Set tanggalSurvey = '$dtmsurvey', isCleaning = $needCleaning, isRepair = $needRepair, pendingRemark = '$remark', Surveyor = '$surveyor'
		          Where bookInID = '$kodeBooking' And NoContainer = '$kywrd';
				  
		   	     Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Container Survey Log ','$kywrd',' ','$kodeBooking')); ";
	      $result = mssql_query($do);		  
	      if($result) { echo '<script>swal("Log survey was updated.")</script>'; }
	    }
	    else { echo '<script>swal("","Invalid value of Survey Date.")</script>'; }	  
      }	 

	  
	  if($_POST["formID"] == "fcontCleaning" && $_POST['discharge'] == '') 
	  {
	    $kodeBooking = $_POST["BookID"];
	    $cleaning = $_POST["cleaningType"];	  
	    $tglCleaning = $_POST["datecleaning"];
	    $dpp = $_POST["dpp"];
	    if(trim($dpp) == '') { $dpp = 0; }
   	    $postTgl = trim(str_ireplace("-","",$tglCleaning));
	    $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
	
 	    if($cleaning == "WW") { $remark = "LIGHT CLEANING"; }
	    if($cleaning == "DW") { $remark = "MEDIUM CLEANING"; }
	    if($cleaning == "CC") { $remark = "HEAVY CLEANING"; }
	    if($cleaning == "SC") { $remark = "SPECIAL CLEANING"; }
	   
	    $location = '';
        $query = "Select workshopID From containerJournal Where NoContainer='$kywrd' And BookinID='$kodeBooking'";
        $result = mssql_query($query);
        if(mssql_num_rows($result) > 0) 
		{
          $arrResl = mssql_fetch_array($result);		
	      $location = $arrResl["workshopID"];
	    }
        mssql_free_result($result);	   
	
        $query = "If Not Exists(Select * From CleaningHeader Where containerID='$kywrd' And bookID='$kodeBooking') Begin
	 	             Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		             Set @ToDay_ = '$tglCleaning'; 
		   	         Set @Keywrd=CONCAT('$postTgl','%');
				
		             If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
				       Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                       Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
				       Set @NewDraft=CONCAT('$postTgl', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
			         End Else Begin 
				          Insert Into logKeyField(keyFName, lastNumber) Values('$postTgl', 1);
				          Set @NewDraft=CONCAT('$postTgl', '.1');
				         End;
			  
                     Insert Into CleaningHeader(cleaningID, containerID, cleaningDate, nilaiDPP, bookID, invoiceNumber) 
                                         Values(@NewDraft, '$kywrd', @Today_, '$dpp', '$kodeBooking', '');			 
                     Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                         Values(@NewDraft, '$location', 0, '$remark', '$cleaning');
										 
				 End Else Begin
				       Declare @IndexRec VarChar(30);
					   Select @IndexRec = cleaningID From CleaningHeader Where containerID='$kywrd' And bookID='$kodeBooking';
					   Update CleaningDetail Set repairID = '$cleaning', Remarks = '$remark' Where cleaningID = @IndexRec;
					   Update CleaningHeader Set cleaningDate = '$tglCleaning',  nilaiDPP = $dpp Where cleaningID = @IndexRec;
				     End; ";
	    $result = mssql_query($query);

	    $do = "Update containerJournal Set CCleaning = '$tglCleaning', cleaningType = '$cleaning' Where NoContainer = '$kywrd' And bookInID = '$kodeBooking';
	           Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Insert/Update Cleaning Log ','$kywrd',' ','$kodeBooking')); ";
	    $result = mssql_query($do);
	    if($result) { echo '<script>swal("Success","Cleaning Log has been updated")</script>'; }
	  }
	  
	  if($_POST["formID"] == 'fcontRepair' && $_POST["discharge"] == '') 
	  {
	    $cr = '';
        $tglApprove = '';	   
	    $kodeBooking = $_POST["BookID"];
	    $tglEOR = $_POST["submittedEOR"];
	    $nilaiDPP = $_POST["dpp"];
	    $cr = $_POST["CR"];
	    $NoEst = strtoupper($_POST["noEstimate"]);
	    $tglApprove = $_POST["EORapproved"];
		$nilaiMH = $_POST["manhour"];
		
	    $do = '';
        if(trim($NoEst) != '') 
		{	   
	      $do = "If Not Exists(Select estimateID From RepairHeader Where containerID='$kywrd' And bookID='$kodeBooking') Begin 
		          Insert Into RepairHeader(estimateID, containerID, estimateDate, nilaiDPP, totalHour, totalLabor, totalMaterial, totalOwner, totalUser, 
				                           laborRate, invoiceNumber, bookID, isValid)
								    Values('$NoEst', '$kywrd', '$tglEOR', $nilaiDPP, $nilaiMH, 0, 0, 0, 0, 0, '', '$kodeBooking', 1);
		         End Else Begin
				      Update RepairHeader Set estimateID='$NoEst', estimateDate = '$tglEOR', nilaiDPP = $nilaiDPP, totalHour=$nilaiMH 
					  Where containerID='$kywrd' And bookID='$kodeBooking';
				    End;";		
		  $res = mssql_query($do);	  
		  if($res) { echo '<script>swal("Success","Repair Log has been updated")</script>'; }
	    }	
	   
	    if(trim($tglApprove) != '') 
		{
	      $do = "Update repairHeader Set tanggalApprove = '$tglApprove',statusEstimate= 'APPROVE' 
		         Where containerID='$kywrd' And bookID='$kodeBooking' And estimateID='$NoEst'; "; 
          $res = mssql_query($do);		 
	    }
	   
	    if(trim($cr) != '') 
		{
	      $do = "Update containerJournal Set CRDate = '$cr' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd';
	             Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                             Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Complete Repair Log ','$kywrd',' ','$kodeBooking')); ";
	      $res = mssql_query($do);	  
	    }
	  }
    }
   
    if($_POST["formID"] == 'fcontRepair' || $_POST["formID"] == "fcontCleaning") 
    {
      $query = "Select Format(CCleaning, 'yyyy-MM-dd') As CCleaning, Format(CRDate, 'yyyy-MM-dd') As CR, isCleaning, isRepair
                From containerJournal Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
      $resl = mssql_query($query);
      $arrfetch = mssql_fetch_array($resl);
      $tglCleaning = $arrfetch["CCleaning"];
	  $Cleaning = $arrfetch["isCleaning"];
	  $Repair = $arrfetch["isRepair"];
      $tglCR = $arrfetch["CR"];
      mssql_free_result($resl);
       
	  $tglAV = '';
	  if($Cleaning == 1 && $Repair == 1) 
	  {
        if(trim($tglCleaning) != '' && $tglCleaning != '1900-01-01' && trim($tglCR) != '' && $tglCR != '1900-01-01') 
		{
		  if(strtotime($tglCleaning) >= strtotime($tglCR)) { $tglAV = $tglCleaning; }
		  if(strtotime($tglCR) >= strtotime($tglCleaning)) { $tglAV = $tglCR; }
		 
		  $do = "Update containerJournal Set Cond = 'AV', AVCond = '$tglAV' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
          $exeQuery = mssql_query($do);		 
        }		   
	  }	 
	  
	  if($Cleaning == 1 && $Repair == 0) 
	  {
        if(trim($tglCleaning) != '' && $tglCleaning != '1900-01-01') 
		{
		  $tglAV = $tglCleaning;	 
		  $do = "Update containerJournal Set Cond = 'AV', AVCond = '$tglAV' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
          $exeQuery = mssql_query($do);		 		  
		}  
	  }	  
	  
	  if($Cleaning == 0 && $Repair == 1) 
	  {
        if(trim($tglCR) != '' && $tglCR != '1900-01-01') 
		{
		  $tglAV = $tglCR;	 
		  $do = "Update containerJournal Set Cond = 'AV', AVCond = '$tglAV' Where bookInID = '$kodeBooking' And NoContainer = '$kywrd'; ";
          $exeQuery = mssql_query($do);		 		  
		}  
	  }	  	  	      
	}   
	
    $principle = '';
    $consignee = '';	
	$gateOutHamparan='';
	$gateInHamparan='';
	$dateInPort='';
    $needCleaning = 0;
    $needRepair = 1;	   
	$tglCR = '';
	$tglAVCond = '';
	$tglSurvey = '';
	$tglCleaning = '';
	$workshopID = '';
	$vesselName = '';
	$voyageNo = '';
	$noEstimate = '';
    $tglSubmitted = '';
	$DPP = 0;
	$tglApprove = '';
	$dirNamePDF = '';	
	$MH = 0;
	$Cond = '';
	$mnfr = '';
	$constr = '';
	$vent = '';
	$Size = '';
	$Type = '';
	$Height = '';
	
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
	       Left Join containerLog b On b.ContainerNo = a.NoContainer
		   Inner Join tabBookingHeader c On c.BookId = a.bookInID
		   Left Join m_Location d On d.locationID=a.workshopID
		   Where NoContainer = '$kywrd' And a.bookInID='$kodeBooking'; ";
    $resldo = mssql_query($do);
    
    if(mssql_num_rows($resldo) <= 0) 
	{
		echo '<h3 style="padding:5px 0 5px 0;background:#2196F3;color:#fff;margin-top:0!important">&nbsp;&nbsp;Overview : <?php echo $kywrd?></h3>
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
	  $NoContainer = strtoupper($_POST['noCnt']);
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
  	  $constr = $arr['COnstr'];
	  $vent = $arr['Ventilasi'];
	  $Size = $arr['Size'];
	  $Type = $arr['Type'];
	  $Height = $arr['Height'];
	  
	  if((strtotime($gateInHamparan) > strtotime($gateOutHamparan)) && $gateOutHamparan !='') {
	    echo '<script>swal("Error","There was an error, invalid Date of Gate Out Hamparan");</script>';
	  }	  
</script>

 <!-- <h3 class="w3-blue" style="padding-bottom:4px">&nbsp;&nbsp;OVERVIEW: <?php echo $kywrd?></h3> -->
  <div class="w3-container" style="padding:10px;border:1px solid #d7dbdd;height:430px;overflow-y:auto" id="style-4">    		
    <div class="w3-row-padding">
	  <div class="w3-half" id="edit_content">&nbsp;</div>   <!-- edit side -->
	  
	  <div class="w3-half">                                 <!-- view side -->
	    <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">CONTAINER NO.</label></div>
	     <div class="w3-half"><?php echo $kywrd?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
        <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">TRANSC. NUMBER</label></div>
	     <div class="w3-half"><?php echo $kodeBooking?></div>
	    </div> 
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">HAMPARAN OUT DATE</label></div>
	     <div class="w3-half"><?php echo $gateOutHamparan?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
        <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">COND.</label></div>
	     <div class="w3-half"><?php echo $Cond?></div>
	    </div> 
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>
		
		<div class="height-10"></div>		
	    		
		<!-- start: event log -->
	    <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SIZE/TYPE/HEIGHT</label></div>
	     <div class="w3-half"><?php echo $Size.' / '.$Type.' / '.$Height ?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
        <div class="w3-row-padding" style="line-height:20px">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">MNFR/CONSTR/VENT.</label></div>
	     <div class="w3-half"><?php echo $mnfr.' / '.$constr.' / '.$vent ?></div>
	    </div> 
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">GATE IN (AT PORT ARRIVE) DATE </label></div>
	     <div class="w3-half"><?php echo $dateInPort?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
		
	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">HAMPARAN IN DATE</label></div>
	     <div class="w3-half"><?php echo $gateInHamparan?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
		
	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SHIPPING LINE</label></div>
	     <div class="w3-half"><?php echo $principle?></div>
        </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>			
		
	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">USER</label></div>
	     <div class="w3-half"><?php echo $consignee?></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">HAMPARAN</label></div>
	     <div class="w3-half"><?php echo $workshopID?></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">Ex. VESSEL VOYAGE</label></div>
	     <div class="w3-half"><?php echo $vesselName.' '.$voyageNo?></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
		
        <div class="height-10"></div>		
		<div class="w3-row-padding">
		 <div class="w3-third">
          <form id="fcntEvent" method="post">
			<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />
			<input type="hidden" name="bookID" value="<?php echo $kodeBooking?>" />
			<input type="hidden" name="loc" value="<?php echo $loc?>" />					 
			<button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">EDIT EVENT LOG</button>
		  </form>
		 </div>
		 <div class="w3-third">
		 
          <?php		 
		    if($tglSurvey == '' || $tglSurvey == '1900-01-01')  		 
	        {
	          echo '<form id="fHapusCont" method="post"> 
				     <input type="hidden" name="noCnt" value="'.$kywrd.'" />
				     <input type="hidden" name="bookID" value="'.$kodeBooking.'" />
				     <input type="hidden" name="loc" value="'.$loc.'" />
				     <input type="hidden" name="formID" value="fHapusCont" />
				     <button class="w3-text-red" style="border:0;background:none;font-weight:600;padding:0;outline:none">REMOVE</button> 
				    </form>';	 
	        }	
		  ?>	
		 </div>
		 <div class="w3-third"></div>
		</div>
		<!-- end: event log -->

		<!-- start: Survey log -->
		<div class="height-10"></div>
		<div class="height-10"></div>
	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SURVEY DATE</label></div>
	     <div class="w3-half"><label><?php echo $tglSurvey;?></label></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SURVEYOR</label></div>
	     <div class="w3-half"><label><?php echo $arr["Surveyor"]?></label></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">CLEANING</label></div>
	     <div class="w3-half">
         
   	      <?php
		    if($needCleaning == 1) { echo '<input type="checkbox" name="iscleaning" checked disabled />'; }
		    else { echo '<input type="checkbox" name="iscleaning" />'; } 
		  ?>
		 
		 </div>
        </div>
        <div class="height-5"></div>	

	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">REPAIR/ESTIMATE</label></div>
	     <div class="w3-half">
		 
          <?php
		    if($needRepair == 1) { echo '<input type="checkbox" name="isrepair" checked disabled/>'; }
		    else { echo '<input type="checkbox" name="isrepair" />'; } 
  		  ?>
		 
		 </div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
		
	    <div class="w3-row-padding">
         <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SURVEY REMARKS</label></div>
	     <div class="w3-half"><label><?php echo $arr["pendingRemark"]?></label></div>
        </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>

        <div class="height-10"></div>		
		<div class="w3-row-padding">
		 <div class="w3-half">
		 
		  <?php
//		    if($Cond == 'DM')  
//            {
		      echo '<form id="fcntsurvey" method="post">
			         <input type="hidden" name="noCnt" value="'.$kywrd.'" />
				     <input type="hidden" name="loc" value="'.$loc.'" />	
			         <button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">EDIT SURVEY LOG</button>
			        </form>';				
//            } 				
		  ?>
		  
		 </div>
		 <div class="w3-half">&nbsp;</div>
		</div>
		<!-- end: Survey log -->		

		<!-- start: Cleaning Log -->			
<?php
		  
  if($needCleaning == 1) 
  {
    $logcleaning  = "Select Format(a.CCleaning, 'yyyy-MM-dd') As tglCleaning, cleaningType From containerJournal a
                     Where a.NoContainer='$kywrd' And bookInID='$kodeBooking' ";
    $reslog = mssql_query($logcleaning);
	if(mssql_num_rows($reslog) > 0)
    { 
      $arrlog = mssql_fetch_array($reslog);
      $tglCleaning = $arrlog["tglCleaning"];			  
      if($arrlog["cleaningType"] == 'WW') {$cleaningType = "LIGHT CLEANING";}
      if($arrlog["cleaningType"] == 'DW') {$cleaningType = "MEDIUM CLEANING";}
      if($arrlog["cleaningType"] == 'CC') {$cleaningType = "HEAVY CLEANING";}
      if($arrlog["cleaningType"] == 'SC') {$cleaningType = "SPECIAL CLEANING";}			  
    }
    mssql_free_result($reslog);			
            
    $dppCleaning = 0;
    $logcleaning = "Select nilaiDPP From CleaningHeader Where containerID='$kywrd' And bookID='$kodeBooking' ";
    $reslog = mssql_query($logcleaning);
    if(mssql_num_rows($reslog) > 0) 
	{
	  $arrlog = mssql_fetch_array($reslog);
	  $dppCleaning = $arrlog["nilaiDPP"];
	}
	mssql_free_result($reslog);
?>	
            
		<div class="height-20"></div>
		<div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">CLEANING DATE</label></div>
		 <div class="w3-half"><?php echo $tglCleaning?></div>
	    </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
		  
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">CLEANING TYPE</label></div>
		 <div class="w3-half"><?php echo $cleaningType?></div>
	    </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
		  
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">CLEANING FEE</label></div>
		 <div class="w3-half" style="text-align:right"><?php echo number_format($dppCleaning,2,",",".")?></div>
	    </div>
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
	    
		<div class="height-10"></div>		
		<div class="w3-row-padding">
         <div class="w3-half">
          <form id="fcntCleaning" method="post">
			<input type="hidden" name="noCnt" value="<?php echo $kywrd?>" />			         
			<input type="hidden" name="loc" value="<?php echo $loc?>" />					 
			<button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">EDIT CLEANING LOG</button>					 
		  </form>
         </div>
         <div class="w3-half"></div> 			
	    </div>
		<!-- end: Cleaning log -->						
		
<?php		
  }			
  if($needRepair == 1) 
  {
    $noEstimate = '';
    $tglSubmitted = '';
    $DPP = 0;
	$dirNamePDF = '';
    $logrepair = "Select estimateID, Format(estimateDate, 'yyyy-MM-dd') As estimateDate, nilaiDPP, Format(tanggalApprove, 'yyyy-MM-dd') As tglApprove, dirname, IsNull(totalHour,0) As totalHour
                  From RepairHeader Where bookID='$kodeBooking' And containerID='$kywrd'";		
    $reslog = mssql_query($logrepair);	
	if(mssql_num_rows($reslog) > 0) 
	{	
      $arrlog = mssql_fetch_array($reslog);		 
      $noEstimate = $arrlog["estimateID"];
	  $tglSubmitted = $arrlog["estimateDate"];
	  $DPP = $arrlog["nilaiDPP"];			  
	  $tglApprove = $arrlog["tglApprove"];
	  $dirNamePDF = $arrlog["dirname"];
	  $MH = $arrlog["totalHour"];
	}		
    mssql_free_result($reslog);			
?>	
			
		<!-- start: Repair Log -->				
		<div class="height-20"></div>
		<div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">ESTIMATE NUMBER</label></div>
		 <div class="w3-half"><?php echo $noEstimate?></div>
	    </div>
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>
	  
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">SUBMITTED DATE</label></div>
		 <div class="w3-half"><?php echo $tglSubmitted?></div>
	    </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
 
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">APPROVED DATE</label></div>
		 <div class="w3-half"><?php echo $tglApprove?></div>
	    </div>
        <div class="height-5" style="border-top:1px dotted #ddd"></div>
		  
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">DPP ESTIMATE</label></div>
		 <div class="w3-half" style="text-align:right"><?php echo number_format($DPP,2,",",".")?></div>
	    </div>
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>

        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">TOTAL MH</label></div>
		 <div class="w3-half" style="text-align:right"><?php echo number_format($MH,2,",",".")?></div>
	    </div>
	    <div class="height-5" style="border-top:1px dotted #ddd"></div>
				  
        <div class="w3-row-padding">
	     <div class="w3-half"><label class="w3-text-grey" style="font-weight:500">COMPLETE REPAIR DATE</label></div>
		 <div class="w3-half"><?php echo $tglCR?></div>
	    </div>				  
		<div class="height-5" style="border-top:1px dotted #ddd"></div>
				  
		<div class="height-10"></div>
		<div class="w3-row-padding">
		 <div class="w3-third">

<?php		
	if($tglSurvey != '' && $tglSurvey != '1900-01-01' && $needRepair==1 && ($tglCR == '' || $tglCR == '1900-01-01')) 
	{
	  echo '<form id="fcntRepair" method="post">
	          <input type="hidden" name="noCnt" value="'.$kywrd.'" />			         
		      <input type="hidden" name="loc" value="'.$loc.'" />				   
		      <button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">EDIT ESTIMATE LOG</button>
		    </form>';  
    }			  		  
?>	
			
	     </div>
		
<?php		
  } 
?>   
		<!-- end: Repair log -->		

        <!-- start: EOR Document Log -->
		 <div class="w3-third"></div>
         <div class="w3-third">
		
        <?php 		
	      if($noEstimate != '' && substr($noEstimate,0,3) != 'REP' && substr($noEstimate,0,3) != 'DRF' && trim($principle) != '' && $workshopID != '' && $tglSurvey != '' && $tglSurvey != '1900-01-01') 
		  {
		?>	  
            <form name="fuploadEOR" method="get" action="attchFile.php" target="popUpW" 
			        onsubmit="window.open('','popUpW','directories=0,location=0,menu=0,scrollbars=1,resizable=0,width=700,height=600,toolbar=0')">			 
		      <input type="hidden" name="noCnt" value="<?php echo $kywrd;?>" />			         
			  <input type="hidden" name="kodeBook" value="<?php echo $kodeBooking?>" />						  
			  <button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0">MANAGE ESTIMATE DOC.</button>
		    </form>
		<?php		  
		  }
		  if(trim($noEstimate) == '' && trim($principle) != '' && $workshopID != '' && $tglSurvey != '' && $tglSurvey != '1900-01-01' && $needRepair==1) 
		  {
			echo '<form id="fCreateEOR" method="get">
  			        <input type="hidden" name="noCnt" value="'.$kywrd.'" />			         
			        <input type="hidden" name="kodeBooking" value="'.$kodeBooking.'" />			         
				    <input type="hidden" name="loc" value="'.$loc.'" />
					<input type="hidden" name="noCntFilter" value="'.$noCnt.'" />
			        <button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">CREATE ESTIMATE</button>	
                  </form>';
          }					
                    
          if(substr($noEstimate,0,3) == 'REP' || substr($noEstimate,0,3) == 'DRF')
          {
			echo '<form id="fCreateEOR" method="get">
  			        <input type="hidden" name="noCnt" value="'.$kywrd.'" />			         
			        <input type="hidden" name="kodeBooking" value="'.$kodeBooking.'" />			         
				    <input type="hidden" name="loc" value="'.$loc.'" />
			        <button type="submit" class="w3-text-blue" style="border:0;background:none;font-weight:600;padding:0;outline:none">VIEW ESTIMATE</button>	
                  </form>';				
          }							
		?>  
		  
         </div>		 
		</div> 
        
        <!-- end: EOR Document Log -->		
	  </div> 	  
	</div>		
  </div>

<script language="php">
    }
	mssql_close($dbSQL);
</script>  

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

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;

	if (charCode == 46) {
	  return true;
    } else {	  
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	    return false;
      }
	}  
    
	return true;
  }    
</script>

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
      $.post("fcntprofile.php", formValues, function(data){ $("#edit_content").html(data); });
    });
    
    $("#fcntEvent").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntEvent.php", formValues, function(data){ $("#edit_content").html(data); });
    }); 	

    $("#fcntsurvey").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntSurvey.php", formValues, function(data){ $("#edit_content").html(data); });
    }); 	
	
    $("#fcntCleaning").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fcntCleaning.php", formValues, function(data){ $("#edit_content").html(data); });
	  window.location.hash = "edit_content";
    }); 		
	
    $("#fcntRepair").submit(function(event){
      event.preventDefault();		
      var formValues = $(this).serialize();
      $.post("fcntCRepair.php", formValues, function(data){ $("#edit_content").html(data); });
      window.location.hash = "edit_content";
    }); 	

    $("#fCreateEOR").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("estimate.php", formValues, function(data){ $("#mnr_form").html(data); });
	  window.location.hash = "edit_content";
    }); 
  });  
</script>    

<!--
    $("#fuploadEOR").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("fUploadEOR.php", formValues, function(data){ $("#edit_content").html(data); });
    }); 


-->