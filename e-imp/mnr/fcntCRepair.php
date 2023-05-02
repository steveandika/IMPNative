<?php
  if(isset($_POST["noCnt"]) &&isset($_POST['kodeBooking'])) {
    include("../asset/libs/db.php");  	   	  

	$estNum="";
    $noCnt = strtoupper($_POST["noCnt"]);
	$kodeBook=$_POST['kodeBooking'];
	if(isset($_POST["estNum"]) && !Empty($_POST["estNum"])) { $noEstimate=$_POST["estNum"]; }
	
    $qry = "Select Format(a.gateIn, 'yyyy-MM-dd') As gateIn, b.principle, b.consignee, a.bookInID, Format(CRDate, 'yyyy-MM-dd') As CRDate,
                   Format(c.estimateDate, 'yyyy-MM-dd') As tglEOR, c.estimateID, IsNull(c.totalHour, 0) As MH, c.totalLabor, c.totalMaterial,
		  	       c.totalOwner, c.totalUser, Format(c.TanggalApprove, 'yyyy-MM-dd') As tglApprove, IsNull(c.nilaiDPP, 0) As DPP, IsNull(c.totalLabor, 0) As Labor,
				   Format(CRDate, 'yyyy-MM-dd') As CRDate
	        From containerJournal a
			Inner Join tabBookingHeader b On b.bookID = a.bookInID
			Left Join RepairHeader c On c.bookID = a.bookInId And c.containerID = a.NoContainer ";
    if($estNum!="") { $qry=$qry."Where estimateID='$estNum'; "; }    			
	else { $qry=$qry."Where a.NoContainer='$noCnt' And a.BookInID='$kodeBook'; "; }
    $res = mssql_query($qry);	
	if(mssql_num_rows($res) > 0) {
	  $arrfetch = mssql_fetch_array($res);
	  
	  $dtmin = $arrfetch["gateIn"];
	  $noEstimate = $arrfetch["estimateID"];
	  $principle = $arrfetch["principle"];
	  $consignee = $arrfetch["consignee"];
	  $tglApproved = $arrfetch["tglApprove"];
	  $tglSubmitted = $arrfetch["tglEOR"];
	  $DPP = $arrfetch["DPP"];
	  $MH = $arrfetch["MH"];
	  $Labor=$arrfetch["Labor"];
	  $finishRep=$arrfetch["CRDate"];
?>

<div class="height-10"></div>
<div class="w3-round-small" style="border:1px solid #d5d8dc; background:#f8f9f9">
 <div class="height-5"></div>
 <div class="w3-container">
  <label style="font: 600 15px/35px Rajdhani, Helvetica, sans-serif;">Customer EOR - Repair Log</label>
  <div class="height-10"></div>
  <form id="fcontRepair" method="post">
   <input type="hidden" name="have" value="overview" />
   <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
   <input type="hidden" name="formID" value="fcontRepair" />   
   <input type="hidden" name="BookID" value="<?php echo $kodeBook?>" />
   <input type="hidden" name="discharge" value="" />
   
   <label >Estimate Number</label>
	
	   <?php
	     if(trim($noEstimate) != '') {
		    echo '<input type="text" maxlength="30" class="w3-input w3-border" name="noEstimate" style="text-transform:uppercase;font-weight:600;letter-spacing:.05em" 
		           value="'.$noEstimate.'" readonly />';	  
         }
         else {
		    echo '<input type="text" maxlength="30" class="w3-input w3-border" name="noEstimate" style="text-transform:uppercase;font-weight:600;letter-spacing:.05em" required />';	  			 
         }			 
	   ?>
   <div class="height-5"></div>

   <label >Submitted Date</label>
   <input class="w3-input w3-border" type="date" name="submittedEOR" id="fDate1" title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") value="<?php echo $tglSubmitted?>" />
   <div class="height-5"></div>
   
   <label>Approved Date</label>
   <input class="w3-input w3-border" type="date" name="EORapproved" id="fDate3" title="Year-Month-Date" onKeyUp=dateSeparator("fDate3") value="<?php echo $tglApproved?>" />
   <div class="height-5"></div>

   <label >Total Estimate Before Tax</label>
   <input class="w3-input w3-border" type="text" name="dpp" onkeypress="return isNumber(event)" 
          value="<?php if ($DPP > 0) { echo number_format($DPP,2,",","."); }
		               else { echo $DPP; }
				  ?>" style="text-align:right" required />
   <div class="height-5"></div>

   <label >Total M/H</label>
   <input class="w3-input w3-border" type="text" name="manhour" onkeypress="return isNumber(event)" 
          value="<?php if ($MH > 0) { echo number_format($MH,2,",","."); }
		               else { echo $MH; }
				  ?>" style="text-align:right" required />
   <div class="height-5"></div>   

   <label >Total Labor Value</label>
   <input class="w3-input w3-border" type="text" name="laborVal" onkeypress="return isNumber(event)" 
          value="<?php if ($Labor > 0) { echo number_format($Labor,2,",","."); }
		               else { echo $Labor; }
		          ?>" style="text-align:right" required />
   <div class="height-5"></div>   
   
   <label >Finish Repair</label>
   <input class="w3-input w3-border" type="date" name="CR" id="fDate2" title="Year-Month-Date" onKeyUp=dateSeparator("fDate2") value="<?php echo $finishRep;?>"  />
   <div class="height-10"></div>
   
   <div class="w3-container">   
     <button type="submit" class="button-blue">Save Repair Log</button>&nbsp;                  
	 <input type="submit" class="button-blue" onclick="this.form.discharge.value=this.value;" value="Cancel" />
   </div>   
	 
  </form>
  <div class="height-10"></div> 
 </div> 
</div>  
<div class="height-10"></div>

<script language="php">      
    }	
	else { echo '<script>swal("Error","Said Container was not found in waiting finish repair list. Try again.")</script>'; }		
	mssql_free_result($res);
  }	
</script>

<script type="text/javascript">  
  $(document).ready(function(){
    $("#fcontRepair").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("overview.php", formValues, function(data){ $("#mnr_form").html(data); });
    });
  });
</script>