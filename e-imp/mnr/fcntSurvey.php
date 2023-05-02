<?php
  if(isset($_POST["noCnt"]) && isset($_POST["bookID"])) {
    include("../asset/libs/db.php");  	  

    $noCnt = strtoupper($_POST["noCnt"]);
	$BookID= strtoupper($_POST["bookID"]);
	
    $query = "Select Format(tanggalSurvey, 'yyyy-MM-dd') As tanggalSurvey, isCleaning, isRepair, Surveyor, pendingRemark, bookInID, Format(gateIn, 'yyyy-MM-dd') As tglIn 
	          From containerJournal
			  Where NoContainer='$noCnt'And BookInID='$BookID'";
    $result = mssql_query($query);	
	if(mssql_num_rows($result) > 0) {
	  $arrfetch = mssql_fetch_array($result);
	  $dateSurvey = $arrfetch["tanggalSurvey"];
	  $surveyor = $arrfetch["Surveyor"];
	  $needCleaning = $arrfetch["isCleaning"];
	  $needRepair = $arrfetch["isRepair"];
	  $surveyresult = $arrfetch["pendingRemark"];
	  $tglIn = $arrfetch["tglIn"];
	  if(trim($dateSurvey) == '') { $dateSurvey = $tglIn; }
?>
<div class="w3-round-small" style="border:1px solid #d5d8dc; background:#f8f9f9">
 <div class="w3-container">
  <label style="font: 600 15px/35px Rajdhani, Helvetica, sans-serif;">Survey Log</label>
  <form id="fcontSurvey" method="post">

   <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
   <input type="hidden" name="formID" value="fcontSurvey" />   
   <input type="hidden" name="BookID" value="<?php echo $BookID?>" />
   <input type="hidden" name="discharge" value="" />

   <div style="padding:10px 10px 15px 15px;border:0"> 
    <label class="w3-text-grey" style="font-size:13px">Catatan:<br>
	  &nbsp;&nbsp;1.&nbsp;&nbsp;Masukan data tanggal tanpa tanda baca (contoh: "-","/")<br>
      &nbsp;&nbsp;2.&nbsp;&nbsp;Format Tanggal: yyyyMMdd (contoh: 20171101).</label>
   </div>
   
   <div class="w3-row-padding">
    <div class="w3-third">
	 <label class="w3-text-grey">Date of Survey</label>
	 <input class="style-input style-border" type="text" name="eventDate" id="fDate" title="Year-Month-Date" onKeyUp=dateSeparator("fDate") value="<?php echo $dateSurvey?>" required autofocus />						   				
    </div>
	<div class="w3-twothird"></div>
   </div>
   <div class="height-5"></div>
   
   <div class="w3-row-padding">
    <div class="w3-half">
	 <label class="w3-text-grey">Surveyor Name</label>
	 <select name="surveyor" class="style-select">
	 <?php
	   $qry = "Select completeName From m_Employee Where currentFunction=6 and isResign <> 1 Order By completeName";
	   $res = mssql_query($qry);
	   while($col = mssql_fetch_array($res))
	   {
		if($surveyor == $col['completeName']) { echo "<option selected value='".$col['completeName']."'>&nbsp;".$col['completeName']."&nbsp;</option>"; }
        else { echo "<option value='".$col['completeName']."'>&nbsp;".$col['completeName']."&nbsp;</option>"; }		
       }
       mssql_free_result($res);	   
	 ?>
	 </select>
<!--	 <input type="text" class="w3-input w3-border" required maxlength="30" name="surveyor" style="text-transform:uppercase" value="<?php echo $surveyor?>" /> -->
    </div>
	<div class="w3-half"></div>
   </div>
   <div class="height-5"></div>
      
   <div class="w3-container">
     <label class="w3-text-grey">Survey Result</label>
     <textarea name="surveyRemark" required rows="5" cols="60" class="style-input style-border" maxlength="100" style="text-transform:uppercase"><?php echo $surveyresult?></textarea>	 
   </div>
   
   <div class="height-10"></div>
   <div class="w3-container">   
     <button type="submit" class="w3-button w3-blue w3-round-small">Update Log Survey</button>&nbsp;
     <input type="submit" class="w3-button w3-pink w3-round-small" onclick="this.form.discharge.value=this.value;" value="Cancel" />   
   </div>	 
  </form>

<?php
    }
    else { echo '<script>swal("Error","Related record has not found in Database or have already Out Event or Condition available already.")</script>'; }		
    mssql_close($dbSQL);	
  }
?>
  <div class="height-20"></div>
 </div>
</div>

<script type="text/javascript">  
  $(document).ready(function(){
    $("#fcontSurvey").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("overview.php", formValues, function(data){ $("#mnr_form").html(data); });
    });
  });
</script>	