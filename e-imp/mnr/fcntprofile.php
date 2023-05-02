<script language="php">
  if(isset($_POST["noCnt"]) && trim($_POST["noCnt"]) != '') {
    include("../asset/libs/db.php");  	  
	
    $noCnt = strtoupper($_POST["noCnt"]);
	$query = "Select * From containerLog Where ContainerNo = '$noCnt'";
	$result = mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $arrfetch = mssql_fetch_array($result);
	  $size = $arrfetch["Size"];
	  $tipe = $arrfetch["Type"];
	  $height = $arrfetch["Height"];
	  $mnfr = $arrfetch["Mnfr"];
	  $vent = $arrfetch["Ventilasi"];
	  $constr = $arrfetch["Constr"]; 
</script>

<div class="w3-animate-zoom">  
  
  <h3 style="padding:0 0 10px 0;border-bottom:1px solid #b3b6b7;color:#b3b6b7;margin-top:0">&nbsp;&nbsp;Container Profile</h3>  
  <form id="fcontprofile" method="post">
   <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
   <input type="hidden" name="formID" value="fcontprofile" />
   <input type="hidden" name="discharge" value="" />
   
   <div class="w3-row-padding">
     <div class="w3-third">
	   <label class="w3-text-grey">Size</label>
       <select name="contSize" class="w3-select w3-border" >     
	   <script language="php">
         if($size != '')   {echo '<option selected value='.$size.'>&nbsp;'.$size.'&nbsp;</option>';}
   	     if($size != "20") {echo '<option value="20">&nbsp;20&nbsp;</option>';}
	     if($size != "40") {echo '<option value="40">&nbsp;40&nbsp;</option>';}
	     if($size != "45") {echo '<option value="45">&nbsp;45&nbsp;</option>';}	  	 
	   </script>     
       </select>
	   <div class="height-5"></div>
	 </div>	 
     <div class="w3-third">
	   <label class="w3-text-grey">Type</label>
       <select name="contType" class="w3-select w3-border" >
       <script language="php">
         if($tipe != '')    {echo '<option selected value='.$tipe.'>&nbsp;'.$tipe.'&nbsp;</option>';}
         if($tipe != "GP")  {echo '<option value="GP">&nbsp;GP&nbsp;</option>';}	
	     if($tipe != "OT")  {echo '<option value="OT">&nbsp;OT&nbsp;</option>';}
	     if($tipe != "OS")  {echo '<option value="OS">&nbsp;OS&nbsp;</option>';}
	     if($tipe != "FR")  {echo '<option value="FR">&nbsp;FR&nbsp;</option>';}
	     if($tipe != "TW")  {echo '<option value="TW">&nbsp;TW&nbsp;</option>';}
	     if($tipe != "RF")  {echo '<option value="RF">&nbsp;RF&nbsp;</option>';}
	     if($tipe != "TK")  {echo '<option value="TK">&nbsp;TK&nbsp;</option>';}
	     if($tipe != "VT")  {echo '<option value="VT">&nbsp;VT&nbsp;</option>';}
	     if($tipe != "BK")  {echo '<option value="BK">&nbsp;BK&nbsp;</option>';}
	     if($tipe != "OTH") {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  
	   </script>	 	 
       </select>
	   <div class="height-5"></div>
	 </div>
     <div class="w3-third">
	   <label class="w3-text-grey">Height</label>
       <select name="contHeight" class="w3-select w3-border" >
	   <script language="php">
         if($height != '')    {echo '<option selected value='.$height.'>&nbsp;'.$height.'&nbsp;</option>';}
         if($height != "STD") {echo '<option value="STD">&nbsp;STD&nbsp;</option>';}	  
         if($height != "HC")  {echo '<option value="HC">&nbsp;HC&nbsp;</option>';}	  
         if($height != "OTH") {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  
	   </script>
       </select>
	   <div class="height-5"></div>
	 </div>
   </div>   
   <div class="height-5"></div>
   
   <div class="w3-row-padding">
     <div class="w3-third">
	   <label class="w3-text-grey">Constr</label>
       <select name="constr" class="w3-select w3-border" >
	   <script language="php">
         if($constr != '')    {echo '    <option selected value='.$constr.'>&nbsp;'.$constr.'&nbsp;</option>';}
	     if($constr != "STL") {echo '    <option value="STL">&nbsp;STL&nbsp;</option>';} 
	     if($constr != "AL")  {echo '    <option value="AL">&nbsp;AL&nbsp;</option>';} 
	     if($constr != "FRP") {echo '    <option value="FRP">&nbsp;FRP&nbsp;</option>';} 	
       </script>		 
	   </select>
	   <div class="height-5"></div>
	 </div>
     <div class="w3-third">
	   <label class="w3-text-grey">Mnfr</label>
       <input type="text" class="w3-input w3-border" name="mnfr" maxlength="8" value="<?php echo $mnfr;?>" required />
	   <div class="height-5"></div>
	 </div>
     <div class="w3-third">
	   <label class="w3-text-grey">Ventilation</label>
	   <input type="text" class="w3-input w3-border"  name="vent" onkeypress="return isNumber(event)" maxlength="1" value="<?php echo $vent;?>" required />  
	   <div class="height-5"></div>
	 </div>
	
   </div>
   <div class="height-10"></div>
   
   <div class="w3-container">
     <button type="submit" class="w3-button w3-blue w3-round-small">Update Profile</button>&nbsp;
     <input type="submit" class="w3-button w3-pink w3-round-small" onclick="this.form.discharge.value=this.value;" value="Cancel" />
   </div>	 
  </form>
  <div class="height-10"></div>
</div>

<script language="php">	
    }
    mssql_close($dbSQL);
  }
</script>

<script type="text/javascript">  
  $(document).ready(function(){
    $("#fcontprofile").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("cont_list.php", formValues, function(data){ $("#content").html(data); });
    });
  });
</script>	