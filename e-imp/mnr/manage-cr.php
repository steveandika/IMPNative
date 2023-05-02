<?php
  session_start();   

  if (!isset($_SESSION["uid"])) {
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  } 	
  else {     
    $BookID="";
	$keywrd="";
	if(isset($_POST['equipment'])) { $keywrd=strtoupper($_POST['equipment']); }  	
	if(isset($_POST['BookID'])) { $BookID=$_POST['BookID']; }
	
    if(isset($_POST['reg']) && isset($_POST['eq'])) {
	  include("../asset/libs/db.php");
	  
	  $updateCR=0;	
	  $updateCC=0;	
	  $updateAPP=0;
	  $crdate="";
	  $ccdate="";
	  $saveLog = date("Y-m-d h:i:s");
	  $user_id = $_SESSION["uid"];
	  
	  $reg=$_POST['reg'];
	  if(isset($_POST['eq'])) {
	    $eq=strtoupper($_POST['eq']);
		$keywrd=$eq;
	  }	
	  	  
	  if(isset($_POST['crdate']) && $_POST['crdate'] != "") {
		$crdate=$_POST['crdate']; 		
	    $do="Update containerJournal Set CRDate='$crdate' Where bookInID='$reg' And NoContainer='$eq';
		     Update RepairHeader Set FinishRepair='$crdate' Where BookID='$reg' And ContainerID='$eq';
			 
        	 Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$user_id', '$saveLog',CONCAT('Update complete repair date ','$eq',' ','$reg')); ";
        $rslExec=mssql_query($do);	   
		if($rslExec) { $updateCR++; }
		
		//$sql="Select estimateID From RepairHeader Where BookID='$reg' And ContainerID='$eq';";
        //$rsl=mssql_query($sql);		
      }		  
	  else {
	    $do="Update containerJournal Set CRDate=Null Where bookInID='$reg' And NoContainer='$eq';
		     Update RepairHeader Set FinishRepair=Null Where BookID='$reg' And ContainerID='$eq';
			 
        	 Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$user_id', '$saveLog', CONCAT('Reset complete cleaning ','$eq',' ','$reg')); ";
        $rslExec=mssql_query($do);	   		  
		if($rslExec) { $updateCR++; }		
	  }	  

	  if(isset($_POST['cldate']) && $_POST['cldate'] != "") {
		$cldate=$_POST['cldate'];  
	    $do="Update containerJournal Set CCleaning='$cldate' Where bookInID='$reg' And NoContainer='$eq';
		     Update CleaningHeader Set cleaningDate='$cldate' Where BookID='$reg' And ContainerID='$eq';
			 
        	 Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$user_id', '$saveLog', CONCAT('Update complete cleaning date ','$eq',' ','$reg')); ";
        $rslExec=mssql_query($do);	 
		if($rslExec) { $updateCC++; }		
      }		 
      else {
	    $do="Update containerJournal Set CCleaning=Null Where bookInID='$reg' And NoContainer='$eq';
		     Update CleaningHeader Set cleaningDate=Null Where BookID='$reg' And ContainerID='$eq';
			 
        	 Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$user_id', '$saveLog', CONCAT('Reset complete cleaning ','$eq',' ','$reg')); ";
        $rslExec=mssql_query($do);	 
		if($rslExec) { $updateCC++; }		
      }		 

      if (isset($_POST["submitdate"])) {
		$sql = "Update repairHeader Set estimateDate='".$_POST["submitdate"]."' Where BookID='$reg' And ContainerID='$eq';";
        $rslExec = mssql_query($sql);		
      }		  
	  
	  if(isset($_POST['appdate'])) {
		$appdate=$_POST['appdate'];
        $submitdate=$_POST['submitdate'];
        
        if(strtotime($submitdate) <= strtotime($appdate)) {		
	      $do="Update RepairHeader Set tanggalApprove='$appdate' Where BookID='$reg' And ContainerID='$eq';
        	   Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$user_id', '$saveLog',CONCAT('Update Estimate Approval date ','$eq',' ','$reg')); ";
          $rslExec=mssql_query($do);	 
		  if($rslExec) { $updateAPP++; }				
		}  
      }	  

	  if($crdate!="" && $ccdate!="") {
		$crdate=$_POST['crdate'];  
		$cldate=$_POST['cldate'];  
		if(strtotime($crdate) >= strtotime($cldate)) { $avcond=$crdate; }
		if(strtotime($crdate) < strtotime($cldate)) { $avcond=$cldate; }
	    $do="Update containerJournal Set AVCond='$avcond', Cond='AV', isPending='N', gateOut='$avcond' Where bookInID='$reg' And NoContainer='$eq' ";
        $rslExec=mssql_query($do);	   		  		  
	  }	  
	  else {		
	    $qry="Select IsNull(b.nilaiDPP,0) As EOR, IsNull(c.nilaiDPP,0) As Cleaning 
		      From containerJournal a
              Left Join RepairHeader b On b.BookID=a.BookInID And b.ContainerID=a.NoContainer
              Left Join CleaningHeader c On c.BookID=a.BookInID And c.ContainerID=a.NoContainer
			  Where a.BookInID='$reg' And a.NoContainer='$eq' ";
		$rsl=mssql_query($qry);
		if(mssql_num_rows($rsl) > 0) {
		  $col=mssql_fetch_array($rsl);
		  if($col['EOR']==0) { 
		    if($cldate!="") { $avcond=$cldate; }		  
		  }
		  if($col['Cleaning']==0) { 
		    if($crdate!="") { $avcond=$crdate; }		  
		  }		
		  if($col['Cleaning']==$col['EOR']) { 
		    if($cldate!="") { $avcond=$cldate; }		  
		  }				  		  
		  if($col['Cleaning']!=$col['EOR']) { 
		    if($crdate!="") { $avcond=$crdate; }		  
			else { $avcond=""; }
		  }				  		  
		  
		}
        mssql_free_result($rsl);
        
        if($avcond!="") {
		  $do="Update containerJournal Set AVCond='$avcond', Cond='AV', isPending='N', gateOut='$avcond' Where bookInID='$reg' And NoContainer='$eq' ";
          $rslExec=mssql_query($do);	   		  		  
        } else {
		    $do="Update containerJournal Set AVCond=NULL, Cond='DM', isPending='Y', gateOut=NULL Where bookInID='$reg' And NoContainer='$eq' ";
            $rslExec=mssql_query($do);	   		  		  			
	 	  }
        //echo $do;		
	  }	  
	  
	  mssql_close($dbSQL);
	}  
?>

	<div class="height-10"></div>
	<form class="search border-radius-3" id="findContainer" method="post" action="?do=cruddate">
		<h1>C/R, C/C, Estimate Approval Date Management</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	
		<input type="text" placeholder="Container Number" name="equipment" maxlength="11" style="text-transform:uppercase" value="<?php echo $keywrd?>" required />	 		
		<button type="submit">Search</button>
		<div class="height-20"></div>		
	</form>		
	<div class="height-20"></div>
		
	<div class="w3-threequarter">
    <?php
  	  if($updateCR > 0 || $updateCC > 0 || $updateAPP > 0) {
		$design="";  
	    $design='<div class="hardnotif">
	            <div class="height-10"></div>
			    <div class="w3-container">';
				
		if($updateCR > 0)  { $design=$design.'Pengubahan Log Container dan Repair berhasil. Tanggal C/R berhasil disimpan.'.'<br>'; }
		if($updateCC > 0)  { $design=$design.'Pengubahan Log Container dan Cleaning berhasil. Tanggal C/C berhasil disimpan.'.'<br>'; }		
		if($updateAPP > 0) { $design=$design.'Pengubahan Log Repair berhasil. Tanggal Persetujuan EOR berhasil disimpan.'.'<br>'; }				
		
	    $design=$design.'</div>	 
				         <div class="height-10"></div>		       
                         </div><div class="height-10"></div>';	
        echo $design;						 
	  }	  
	  
      if($keywrd!="") {
        include("../asset/libs/db.php");  
	  
	    $design="";
		
        $qry="Select a.BookInID, a.noContainer, Format(a.gateIn,'yyyy-MM-dd') As gateIn, Format(a.CRDate,'yyyy-MM-dd') As CRDate,
              Format(a.CCleaning,'yyyy-MM-dd') As CCleaning, Format(a.AVCond,'yyyy-MM-dd') As AVCond, Format(a.GIPort,'yyyy-MM-dd') As GIPort
              From containerJournal a
			  Inner Join tabBookingHeader b On b.BookID=a.bookInID
              Where a.noContainer='$keywrd' And gateIn Is Not Null Order By gateIn Desc";
        $rsl=mssql_query($qry);
        $foundEquip=mssql_num_rows($rsl);
		if($foundEquip>0) {
		  $design=$design.'<div class="w3-container">
                            <h6>Container Journal</h6>	 
		                    <div class="w3-container w3-responsive">
		                     <table class="w3-table w3-bordered">
							   <tr>
							    <th>Ticket No.</th>
							    <th>Port In</th>
							    <th>Hamparan In</th>
							    <th>C/R</th>
							    <th>C/C</th>
							   </tr>';
          while($arr=mssql_fetch_array($rsl)) {
			if($foundEquip==1) { $BookID=$arr['BookInID']; }
			
		    $design=$design.'<tr>
			                  <td><form id="manageFinishMNR" method="post" action="?">
							       <input type="hidden" name="do" value="cruddate" />
							       <input type="hidden" name="BookID" value="'.$arr['BookInID'].'" />
								   <input type="hidden" name="equipment" value="'.$keywrd.'" />
							       <button type="submit" style="text-decoration:none;border:none;background:none;padding:0" class="w3-text-blue">'.$arr['BookInID'].'</button>
								  </form></td>
							  <td>'.$arr['GIPort'].'</td>
							  <td>'.$arr['gateIn'].'</td>
							  <td>'.$arr['CRDate'].'</td>
							  <td>'.$arr['CCleaning'].'</td>
                             </tr>'; 			
          }
		  $design=$design.' </table></div><div class="height-10"></div></div><div class="height-10"></div>';
		  mssql_free_result($rsl);
		}
				
	    if($BookID!="") {
          $qry="Select BookInID, noContainer, Format(gateIn,'yyyy-MM-dd') As gateIn, Format(CRDate,'yyyy-MM-dd') As CRDate,
                Format(CCleaning,'yyyy-MM-dd') As CCleaning, Format(AVCond,'yyyy-MM-dd') As AVCond, Format(GIPort,'yyyy-MM-dd') As GIPort
                From containerJournal 
                Where noContainer='$keywrd' And BookInID='$BookID'";
          $rsl=mssql_query($qry);
		  //if ($_SESSION["uid"]=="ROOT") { echo $qry; }
		  $col=mssql_fetch_array($rsl);
		  $design=$design.'<div class="form-work">
                            <h6>Maintenance & Repair Log</h6>	 
							<div class="height-10"></div>
							<form id="logMNR" method="post" action="?">
							  <input type="hidden" name="do" value="cruddate" />
							  <input type="hidden" name="reg" value="'.$BookID.'" />
							  <input type="hidden" name="eq" value="'.$keywrd.'" />
							  <input type="hidden" name="sqlUpdate" value="updateLog" />
                              
							  <div class="w3-row-padding">
		                       <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Complete Repair</div>
				               <div class="w3-quarter"><input type="date" class="w3-input w3-border " name="crdate" value="'.$col['CRDate'].'" id="crdate" /></div>
							   <div class="w3-twoquarter"></div>
                              </div>
						      <div class="height-5"></div>
							
		                      <div class="w3-row-padding">
 		                       <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Ticket No.</div>
			  	               <div class="w3-quarter"><input type="text" class="w3-input w3-border " disabled value="'.$col['BookInID'].'"/></div>
				               <div class="w3-twoquarter"></div>
                              </div>
				              <div class="height-5"></div>
		                      <div class="w3-row-padding">
		                       <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Hamparan/Workshop In</div>
				               <div class="w3-quarter"><input type="text" class="w3-input w3-border " disabled value="'.$col['gateIn'].'"/></div>
				               <div class="w3-twoquarter"></div>
                              </div>
				              <div class="height-5"></div>';
		
		  $rep="Select Format(tanggalApprove,'yyyy-MM-dd') As ApprovalDate, estimateID, Format(estimateDate,'yyyy-MM-dd') As SubmitDate 
		        From RepairHeader 
			    Where BookID='$BookID' And ContainerID='$keywrd' And (InvoiceNumber Is Null Or InvoiceNumber ='')";
		  $rslRep=mssql_query($rep);
		  $haveEstimate=mssql_num_rows($rslRep);
		  if($haveEstimate > 0) {
		    $arr=mssql_fetch_array($rslRep);	
            $design=$design.'<div class="w3-row-padding">
		                      <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Estimate No.</div>
			  	              <div class="w3-quarter"><input type="text" class="w3-input w3-border " disabled value="'.$arr['estimateID'].'"/></div>
							 <div class="w3-twoquarter"></div>
                             </div>
						     <div class="height-5"></div>							 
		                     <div class="w3-row-padding">
		                      <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Submitted Date</div>
				              <div class="w3-quarter"><input type="text" class="w3-input w3-border " disabled name="submitdate" value="'.$arr['SubmitDate'].'" id="submitdate" onKeyUp=dateSeparator("submitdate")/></div>
							  <div class="w3-twoquarter"></div>
                             </div>
						     <div class="height-5"></div>
		                     <div class="w3-row-padding">
		                      <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Approval Date</div>
				              <div class="w3-quarter"><input type="date" class="w3-input w3-border " name="appdate" value="'.$arr['ApprovalDate'].'" id="appdate" /></div>
							  <div class="w3-twoquarter"></div>
                             </div>
						     <div class="height-5"></div>';		
		  }
          mssql_free_result($rslRep);

		  $cln="Select Format(cleaningDate,'yyyy-MM-dd') As cleaningDate, cleaningID 
		        From CleaningHeader 
			    Where BookID='$BookID' And ContainerID='$keywrd' And (InvoiceNumber Is Null Or InvoiceNumber='')";
		  $rslCln=mssql_query($cln);
		  $haveCl=mssql_num_rows($rslCln);
		  if($haveCl > 0) {
		    $arr=mssql_fetch_array($rslCln);	
            $design=$design.'<div class="w3-row-padding">
		                      <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Cleaning Log No.</div>
			  	              <div class="w3-quarter"><input type="text" class="w3-input w3-border " disabled value="'.$arr['cleaningID'].'"/></div>
							 <div class="w3-twoquarter"></div>
                             </div>
						     <div class="height-5"></div>
		                     <div class="w3-row-padding">
		                      <div class="w3-quarter" style="color:#a20912;font-weight:500;padding:4px 4px">Finish Cleaning Date</div>
				              <div class="w3-quarter"><input type="date" class="w3-input w3-border " name="cldate" value="'.$arr['cleaningDate'].'" id="cldate" /></div>
							  <div class="w3-twoquarter"></div>
                             </div>
						     <div class="height-5"></div>';		
		  }
          mssql_free_result($rslCln);	
		  
		  if($haveEstimate > 0 || $haveCl > 0) {
            $design=$design.'<div class="height-10"></div>
		                     <div class="w3-container">
		                     <button type="submit" class="w3-button w3-blue">Update Log</button></div></form>';   		  
		  }					 
          $design=$design.'<div class="height-10"></div></div>';							 
	    }
		
        mssql_close($dbSQL); 
		echo $design; 
      } ?>

		<div class="height-10"></div>
    </div>   
 <!-- </div> -->
 <div class="height-10"></div>
<!--</div> -->
 
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

<?php
  }
?>