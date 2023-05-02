<script language="php">  
  if(isset($_POST['noCnt'])) {
	include("../asset/libs/db.php");
	
    $keywrd = $_POST['noCnt'];
	
	$query = "Select Format(gateIn, 'yyyy-MM-dd') As gateIn, Cond, Format(CRDate, 'yyyy-MM-dd') As CRDate,
                     Format(CCleaning, 'yyyy-MM-dd') As CCleaning, Format(AVCond, 'yyyy-MM-dd') As AVCode,
					 Format(tanggalSurvey, 'yyyy-MM-dd') As Survey, Surveyor, pendingRemark, isCleaning, isRepair, bookInID,
					 Size, Type, Height, Constr, Mnfr, Ventilasi, workshopID
					 From containerJournal a 
					 Left Join containerLog b On b.ContainerNo = a.NoContainer
					 Where a.NoContainer = '$keywrd' And gateOut Is Null" ;
	$result = mssql_query($query);
	while($arr = mssql_fetch_array($result)) {
	  $workshop = $arr['workshopID'];
	  $tanggalIn = $arr['gateIn'];
	  $tanggalCR = $arr['CRDate'];
	  $tanggalCC = $arr['CCleaning'];
	  $tanggalAV = $arr['AVCode'];
	  $tanggalSurvey = $arr['tanggalSurvey'];
	  $Surveyor = $arr['Surveyor'];
	  $KeteranganSurvey = $arr['pendingRemark'];
	  $isCleaning = $arr['isCleaning'];
	  $isRepair = $arr['isRepair'];
	  $kodeBooking = $arr['bookInID'];
	  $size = $arr['Size'];
	  $type = $arr['Type'];
	  $height = $arr['Height'];
	  $constr = $arr['Constr'];
	  $mnfr = $arr['Mnfr'];
	  $Ventilasi = $arr['Ventilasi'];
	}
	mssql_free_result($result);	
</script>

<fieldset class="w3-round-medium" style="padding:0 20px 15px 20px;border:1px solid #f7f9f9;background:#fff">
 <h3 style="padding:0 0 12px 0;border-bottom:1px solid #ccc;color:#757575">&nbsp;&nbsp;Container Profile</h3>
 
 <form method="post">
  <input type="hidden" name="noCont" value="<?php echo $keywrd;?>" />
  
  <div class="w3-container">
   <label>Hamparan Location</label>
   <select name="location" class="w3-select w3-border">
   <script language="php">
    $query = "Select * From m_Location Order By locationDesc ";
    $result = mssql_query($query);
    while($arr = mssql_fetch_array($result)) { 
      if($workshop == $arr[0]) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';}
      else {echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';}
    }
    mssql_free_result($result);  
   </script> 
 
   </select>
   <div class="height-10"></div>
   <label>Container Size/Type/Height</label>
  </div>  
    
  <div class="w3-row-padding">
    <div class="w3-third">
     <select name="contSize" class="w3-select w3-border">     
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
     <select name="contType" class="w3-select w3-border">
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
     <select name="contHeight" class="w3-select w3-border">
	 <script language="php">
      if($height != '')    {echo '<option selected value='.$height.'>&nbsp;'.$height.'&nbsp;</option>';}
      if($height != "STD") {echo '<option value="STD">&nbsp;STD&nbsp;</option>';}	  
      if($height != "HC")  {echo '<option value="HC">&nbsp;HC&nbsp;</option>';}	  
      if($height != "OTH") {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  
	 </script>
     </select>
    </div>
  </div>	
  <div class="height-10"></div>
  
  <div class="w3-container"><label>Container Constr/Mnfr/Ventilation</label></div>  
  <div class="w3-row-padding">
    <div class="w3-third">     
     <select name="constr" class="w3-select w3-border">
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
	  <input class="w3-input w3-border" type="text" name="mnfr" maxlength="8" value="<?php echo $mnfr;?>"  />
	  <div class="height-5"></div>
	</div>	
	<div class="w3-third">
	  <input class="w3-input w3-border" type="text" name="vent" onkeypress="return isNumber(event)" maxlength="2" value="1" required />
	  <div class="height-5"></div>
	</div>
  </div>
  <div class="height-20"></div>
  
  <button type="submit" class="w3-button w3-red w3-round-small" style="font-weight:600">Update Profile</button>
  
 
 </form>
</fieldset> 

<script language="php">
    mssql_close($dbSQL);
  }
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
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }  
</script>