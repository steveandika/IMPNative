<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  $errIs="";
  
  if(isset($_POST['noCnt'])) {
    $keywrd=$_POST['noCnt'];
	
    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, c.Mnfr, c.Constr, b.locationID, Format(a.gateIn, 'yyyy-MM-dd') As dateIn, a.JamIn, a.isPending,
	        b.isCleaning, b.isRepair, a.remarkCond, a.pendingRemark   
            From containerJournal a 
	        Inner Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner Join containerLog c On c.ContainerNo=a.NoContainer 			  
		    Where (a.NoContainer='$keywrd') And (a.gateOut Is Null) and(a.locationID = '".$_SESSION["location"])."'";	  
	$result=mssql_query($query);
	$numRow=mssql_num_rows($result);
	if($numRow > 0) {
	  $arrfetch=mssql_fetch_array($result);
	  $sizeCode=$arrfetch[2];
	  $tipe=$arrfetch[3];
	  $height=$arrfetch[4];
	  $Mnfr=$arrfetch[5];
	  $const=$arrfetch[6];
	  $dtmin=$arrfetch[8];
	  $jamin=$arrfetch[9];
	  $ispending=$arrfetch[10];
	  $iscleaning=$arrfetch[11];
	  $isrepair=$arrfetch[12];
	  $remark=$arrfetch[13];
	  $pending=$arrfetch[14];
      $errIs="OK";    
	  mssql_free_result($result); }
	else { 
	  $errIs="Container not found in active stock."
	  echo '<script>swal("Error","'.$errIs.'", "error");</script>'; }
  }
  
  if($errIs=="OK") {
</script>	  

<div class="w3-row-padding">
  <div class="w3-third">
    <label class="w3-text-teal">* Container Size</label>
    <select name="contSize" class="w3-select w3-border">
    <script language="php">
	  if($contSize == "20") { echo '<option selected value="20">&nbsp;20&nbsp;</option>'; }
	  else { echo '<option value="20">&nbsp;20&nbsp;</option>'; }
	  if($contSize == "40") { echo '<option selected value="40">&nbsp;40&nbsp;</option>'; }
	  else { echo '<option value="40">&nbsp;40&nbsp;</option>'; }
	  if($contSize == "45") { echo '<option selected value="45">&nbsp;45&nbsp;</option>'; }
	  else { echo '<option value="45">&nbsp;45&nbsp;</option>'; }	  
	</script>	
    </select>
  </div>
  <div class="w3-third">
    <label class="w3-text-teal">* Container Type</label>
    <select name="contType" class="w3-select w3-border">
    <script language="php">
	  if($contSize == "GP") { echo '<option selected value="GP">&nbsp;20&nbsp;</option>'; }
	  else { echo '<option value="GP">&nbsp;GP&nbsp;</option>'; }
	  if($contSize == "OT") { echo '<option selected value="OT">&nbsp;OT&nbsp;</option>'; }
	  else { echo '<option value="OT">&nbsp;OT&nbsp;</option>'; }
	  if($contSize == "OS") { echo '<option selected value="OS">&nbsp;OS&nbsp;</option>'; }
	  else { echo '<option value="OS">&nbsp;OS&nbsp;</option>'; }	  
	  if($contSize == "FR") { echo '<option selected value="FR">&nbsp;FR&nbsp;</option>'; }
	  else { echo '<option value="FR">&nbsp;FR&nbsp;</option>'; }
	  if($contSize == "TW") { echo '<option selected value="TW">&nbsp;TW&nbsp;</option>'; }
	  else { echo '<option value="TW">&nbsp;TW&nbsp;</option>'; }
	  if($contSize == "RF") { echo '<option selected value="RF">&nbsp;RF&nbsp;</option>'; }
	  else { echo '<option value="RF">&nbsp;RF&nbsp;</option>'; }	  
	  if($contSize == "TK") { echo '<option selected value="TK">&nbsp;TK&nbsp;</option>'; }
	  else { echo '<option value="TK">&nbsp;TK&nbsp;</option>'; }
	  if($contSize == "VT") { echo '<option selected value="VT">&nbsp;VT&nbsp;</option>'; }
	  else { echo '<option value="VT">&nbsp;VT&nbsp;</option>'; }
	  if($contSize == "BK") { echo '<option selected value="BK">&nbsp;BK&nbsp;</option>'; }
	  else { echo '<option value="BK">&nbsp;BK&nbsp;</option>'; }	  	  
	  if($contSize == "OTH") { echo '<option selected value="OTH">&nbsp;OTH&nbsp;</option>'; }
	  else { echo '<option value="OTH">&nbsp;OTH&nbsp;</option>'; }	  
	</script>	
    </select>
  </div>
  <div class="w3-third">
    <label class="w3-text-teal">* Container Height</label>
    <select name="contHeight" class="w3-select w3-border">
    <script language="php">
	  if($contSize == "STD") { echo '<option selected value="STD">&nbsp;STD&nbsp;</option>'; }
	  else { echo '<option value="STD">&nbsp;STD&nbsp;</option>'; }
	  if($contSize == "HC") { echo '<option selected value="HC">&nbsp;HC&nbsp;</option>'; }
	  else { echo '<option value="HC">&nbsp;HC&nbsp;</option>'; }
	  if($contSize == "OTH") { echo '<option selected value="OTH">&nbsp;OTH&nbsp;</option>'; }
	  else { echo '<option value="OTH">&nbsp;OTH&nbsp;</option>'; }	  
	</script>	
	</select>
  </div>  
</div>
<div class="height-10"></div>


<script language="php">
  }
  mssql_close($dbSQL);
</script>