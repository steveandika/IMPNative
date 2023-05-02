<?php
  if(isset($_POST["noCnt"]) && isset($_POST["kodeBook"])) {
	include ("../asset/libs/db.php");  
	
	$noCnt = $_POST["noCnt"];
	$BookID = $_POST["kodeBook"];
	$jenisCleaning = '';
	$jasaCleaning = 0;
	$estimateID = '';
	$nilaiMaterial = 0;
	$Shipping = "";
	
    $query = "Select CONCAT(b.Size,'/', b.Type, '/', b.Height) As IsoCode, a.bookInID, Format(a.gateIn,'yyyy-MM-dd') As dtmin, 
	                 Format(a.CCleaning, 'yyyy-MM-dd') As tglCleaning, d.nilaiDPP, e.repairID, f.estimateID, isnull(e.materialValue,0) AS materialValue, c.principle 
	          From   containerJournal a
			  Inner Join containerLog b On b.ContainerNo=a.NoContainer
			  Inner Join tabBookingHeader c On c.BookID=a.bookInID
			  Left Join CleaningHeader d On d.bookID=a.bookInID And d.containerID=a.NoContainer
			  Left Join CleaningDetail e On e.cleaningID=d.cleaningID
			  Left Join repairHeader f On f.BookID=a.bookInID And f.containerID=a.NoContainer
			  Where  a.NoContainer='$noCnt' And BookInID='$BookID' And a.isCleaning=1 "; //a.gateOut IS NULL And 
	$result = mssql_query($query);
	$rowCount = mssql_num_rows($result);
	if($rowCount > 0){
	  $arr = mssql_fetch_array($result);
	  $tglCleaning = $arr["tglCleaning"];
	  $isocode = $arr[0];
	  $tglIn = $arr["dtmin"];
	  $jenisCleaning = $arr['repairID'];
	  $jasaCleaning = $arr['nilaiDPP'];
	  $estimateID = $arr['estimateID'];
	  $nilaiMaterial = $arr["materialValue"];
	  if(trim($tglCleaning) == '') { $tglCleaning = $tglIn; }
	  $Shipping = $arr["principle"];
	}
	else {
	  $isocode='';
	  echo '<script>swal("Error", "Container was not found in active stock", "error")</script>';
	}
    mssql_free_result($result);	
    mssql_close($dbSQL);
?>

<div class="w3-round-small" style="border:1px solid #d5d8dc;">
 <div class="w3-container">
 <div class="height-5"></div>
  <label style="font: 600 15px/35px Rajdhani, Helvetica, sans-serif;">Cleaning Log</label>
  <div class="height-10"></div>
  <form id="fcontCleaning" method="post">
     <input type="hidden" name="have" value="overview" />  
     <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
     <input type="hidden" name="formID" value="fcontCleaning" />   
     <input type="hidden" name="BookID" value="<?php echo $BookID?>" />
     <input type="hidden" name="tanggalIn" value="<?php echo $tglIn?>" />
     <input type="hidden" name="discharge" value="" />
   
     <label>Cleaning Type</label>
     <select name="cleaningType" class="style-select">
	 <?php if($jenisCleaning=="WW") { echo '<option selected value="WW">LIGHT&nbsp;</option>'; }
	       else { echo '<option value="WW">LIGHT&nbsp;</option>'; }
           if($jenisCleaning=="DW") { echo '<option selected value="DW">MEDIUM&nbsp;</option>'; }
		   else { echo '<option value="DW">MEDIUM&nbsp;</option>'; }
           if($jenisCleaning=="CC") { echo '<option selected value="CC">HEAVY&nbsp;</option>'; }
		   else { echo '<option value="CC">HEAVY&nbsp;</option>'; }
           if($jenisCleaning=="SC") { echo '<option selected value="SC">SPECIAL&nbsp;</option>'; }
		   else { echo '<option value="SC">SPECIAL&nbsp;</option>'; }        
           if($jenisCleaning=="SW") { echo '<option selected value="SW">SWEEP&nbsp;</option>'; }
		   else { echo '<option value="SW">SWEEP&nbsp;</option>'; }  ?>      
     </select>
     <div class="height-5"></div>

     <label>Cleaning Date</label>
     <input class="style-input style-border" type="text" name="datecleaning" id="fDate" value='<?php echo $tglCleaning ;?>' title="Year-Month-Date" onKeyUp=dateSeparator("fDate") />
     <div class="height-5"></div>
   
     <label>Cleaning Value</label>
     <?php
        if(substr($estimateID,0,3) == 'REP') { echo '<input class="style-input" style="text-align:right" type="text" name="dpp" readonly value="'.$jasaCleaning.'"  autofocus />'; }
        else { echo '<input class="style-input style-border" style="text-align:right" type="text" name="dpp" onkeypress="return isNumber(event)" value="'.$jasaCleaning.'"   autofocus /> '; }		
		
		if(substr($estimateID,0,3) != 'REP') {
     ?>	  
	      <div class="height-5"></div>
          <label>Material Value</label> 
          <input class="style-input style-border" style="text-align:right" type="text" name="nilaiMaterial" onkeypress="return isNumber(event)" value="<?php echo $nilaiMaterial?>" /> 		
     <?php
	    }
     ?>	 
     <div class="height-10"></div>

     <button type="submit" class="w3-button w3-border w3-blue">Save Log</button>&nbsp;
	 <input type="submit" class="w3-button w3-border w3-pink" onclick="this.form.discharge.value=this.value;" value="Cancel" />   
  </form>
  <div class="height-20"></div>
 </div> 
</div>

<script language="php">
  }
</script>

<script type="text/javascript">  
  $(document).ready(function(){
    $("#fcontCleaning").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("overview.php", formValues, function(data){ $("#mnr_form").html(data); });
    });
  });
</script>