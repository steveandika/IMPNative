<script language="php">	    	
  include("../asset/libs/db.php");	
  if(isset($_GET['id'])) { 
	$custid=$_GET['id']; 
		  
	$query="Select * From m_Customer Where custRegID='$custid'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);
	  $nama=$row[1];
	  $short_name=$row['shortName'];
	  $alamatKntr=$row[2];
	  $phoneKntr=$row[3];
	  $fax=$row[4];
	  $npwp=$row[6];
	  $alamat_npwp=$row[7];
	  $depotRate=$row[8];
	  $priceCode=$row["repairPriceCode"];
	  $currRate=$row["currRepair"];
	  if(!is_null($row[10])) { $isExp=$row[10]; }
	  else { $isExp = 0; }
	  if(!is_null($row[11])) { $isImp=$row[11]; }
	  else { $isImp = 0; }
	  if(!is_null($row[12])) { $isLogParty=$row[12]; }
	  else { $isLogParty = 0; }
	  if(!is_null($row[13])) { $isMLO=$row[13]; }
	  else { $isMLO = 0; }
	  if(!is_null($row[14])) { $isFeed=$row[14]; }
	  else { $isFeed = 0; }
	  if(!is_null($row[15])) { $isSupp=$row[15]; }
	  else { $isSupp = 0; }
	  if(!is_null($row[16])) { $isOther=$row[16]; }
      else { $isOther = 0; } }
	mssql_free_result($result);

	$contact=array(" "," "," ");
	$email=array(" "," "," ");
	$phone=array(" "," "," ");
  	$i=0;				  
	
	$query="Select * From m_CustomerContact Where custRegID='$custid'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  while($row=mssql_fetch_array($result)) {
		$contact[$i] = $row[1];
		$email[$i] = $row[2];
		$phone[$i] = $row[3];
		$i++; }
	}
	mssql_free_result($result);  
  }
</script>

<div id="id01" class="w3-modal">  
  <div class="w3-modal-content w3-round-large w3-animate-zoom">
    <div class="w3-container">      
	  
<script language="php">			
  $design=' <div class="height-20"></div>
            <form id="fRegCustomer" method="post"> 
			  <input type="hidden" name="custID" value="'.$custid.'">';
		
  $design=$design.'  <div class="w3-container">
                        <label>Customer Name</label>
                        <input class="style-input style-border" type="text" name="custName" maxlength="80" style="text-transform:uppercase;" required  autofocus value="'.$nama.'">
                     </div>					   
                     <div class="height-5"></div>';		

  $design=$design.'  <div class="w3-container">
                        <label>Short Name</label>
                        <input class="style-input style-border" type="text" name="shortname" maxlength="20" style="text-transform:uppercase;" required  value="'.$short_name.'">
                     </div>					   
                     <div class="height-5"></div>';		
					 
  $design=$design.'  <div class="w3-container">  
                      <fieldset style="padding:10px 5px;border-color:#f4f6f6 "> <legend style="font-size:.810rem">&nbsp;Customer Type&nbsp;</legend>
                       <div class="w3-row-padding">
                        <div class="w3-quarter">';
						
  if($isExp != 1) { $design=$design.'<input class="w3-check" type="checkbox" name="isExportir">&nbsp<label>Exportir</label>&nbsp;&nbsp;'; }
  else {  $design=$design.'<input class="w3-check" type="checkbox" Checked="Checked" name="isExportir">&nbsp<label>Exportir</label>&nbsp;&nbsp;'; }
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter">';
  if($isImp != 1) { $design=$design.'      <input class="w3-check" type="checkbox" name="isImportir">&nbsp<label>Importir</label></p>'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" Checked="Checked" name="isImportir">&nbsp<label>Importir</label></p>'; }  
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter">';
  if($isLogParty != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="isLogistic">&nbsp<label>Logistic Party</label>&nbsp;&nbsp;'; }
  else {	$design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="isLogistic">&nbsp<label>Logistic Party</label>&nbsp;&nbsp;'; }	  
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter">';
  if($isMLO != 1) { $design=$design.'      <input class="w3-check" type="checkbox" name="isMLO">&nbsp<label>M L O</label></p>'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" Checked="Checked" name="isMLO">&nbsp<label>M L O</label></p>'; }  
  $design=$design.'     </div>';  
  $design=$design.'    </div>';
  
  $design=$design.'    <div class="w3-row-padding">';
  $design=$design.'     <div class="w3-quarter">';
  if($isFeed != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="isFeeder">&nbsp<label>Feeder Operator</label></p>'; }
  else { $design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="isFeeder">&nbsp<label>Feeder Operator</label></p>'; }  
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter">';
  if($isSupp != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="isSupplier">&nbsp<label>Supplier</label></p>'; }
  else { $design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="isSupplier">&nbsp<label>Supplier</label></p>'; }  
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter">';
  if($isOther != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="isOther">&nbsp<label>O t h e r</label>&nbsp;&nbsp;</p>'; }
  else { $design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="isOther">&nbsp<label>O t h e r</label>&nbsp;&nbsp;</p>'; }   
  $design=$design.'     </div>';
  $design=$design.'     <div class="w3-quarter"></div>';  
  $design=$design.'    </div>';  
  $design=$design.'   </fieldset> 
                     </div>  
                     <div class="height-5"></div>';  
  
  $design=$design.'  <div class="w3-container">
                        <label>Office Address</label>		
                        <input class="style-input style-border" type="text" name="officeAddr" maxlength="150" style="text-transform:uppercase;" required value="'.$alamatKntr.'">
					 </div>  
					 <div class="height-5"></div>';
					 
  $design=$design.'  <div class="w3-row-padding">
                       <div class="w3-half">
                        <label>Office Phone Number</label>
						<input class="style-input style-border" type="text" name="phoneNumber" maxlength="20" value="'.$phoneKntr.'" >
                       </div>
                       <div class="w3-half">
                        <label>Office Fax Number</label>
						<input class="style-input style-border" type="text" name="faxNumber" maxlength="20" value="'.$fax.'">
                       </div>					   
					 </div> 
					 <div class="height-5"></div>';

  $design=$design.'  <div class="w3-container">					 
                      <label>Registered Tax Number</label>
                      <input class="style-input style-border" type="text" name="taxNumber" maxlength="30" value="'.$npwp.'">
                      <div class="height-5"></div>
                      <label>Registered Tax Address</label>
                      <input class="style-input style-border" type="text" name="taxAddress" maxlength="150" style="text-transform:uppercase;" value="'.$alamat_npwp.'">
					 </div> 
                     <div class="height-5"></div>';  
  
  $design=$design.'  <div class="w3-row-padding">
                        <div class="w3-half">
                         <label>Price Code</label>
                         <select name="repairCode" class="style-select">
                         <option value="none">&nbsp;NONE </option>';
						 
  $sql = "Select Distinct a.priceCode From m_RepairPriceList a Inner Join m_RepairPriceList_Header b On b.priceCode=a.priceCode Order By a.priceCode";
  $result = mssql_query($sql);
  while($rows = mssql_fetch_array($result)) {
	if($priceCode == $rows['priceCode']) { $design=$design.'<option selected value='.$rows['priceCode'].'> &nbsp;'.$rows['priceCode'].'&nbsp; </option>'; }
    else { $design=$design.'<option value='.$rows['priceCode'].'> &nbsp;'.$rows['priceCode'].'&nbsp; </option>'; }}	
  mssql_free_result($result);
  
  $design=$design.'      </select>
                        </div>
                        <div class="w3-half">
                         <label>Depot Rate (Labour)</label>
                         <input class="style-input style-border" type="text" name="depotRate" maxlength="20" required onkeypress="return isNumber(event)" style="text-align:right" value="'.$depotRate.'" >
                         <input type="hidden" name="currRate" maxlength="10" style="text-transform:uppercase;" value=0 >
						</div> 
					 </div> 
                     <div class="height-5"></div>';  
  
  $design=$design.'  <div class="w3-row-padding">';		  
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>Contact Person Name</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="contactPerson-1" maxlength="50" style="text-transform:uppercase;" value="'.$contact[0].'" >';
  $design=$design.'    </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>E-Mail Address</label>';
  $design=$design.'     <input class="style-input style-border" type="email" name="email-1" maxlength="150"  value="'.$email[0].'"> </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>Phone/Mobile Number</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="phone-1" maxlength="50" value="'.$phone[0].'">';
  $design=$design.'    </div>';
  $design=$design.'  </div>';
  $design=$design.'  <div class="height-5"></div>';
		
  $design=$design.'  <div class="w3-row-padding">';				
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>Contact Person Info</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="contactPerson-2" maxlength="50" style="text-transform:uppercase;" value="'.$contact[1].'" >';
  $design=$design.'    </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>E-Mail Address</label>';
  $design=$design.'     <input class="style-input style-border" type="email" name="email-2" maxlength="150" value="'.$email[1].'">';
  $design=$design.'    </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'     <label>Phone/Mobile Number</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="phone-2" maxlength="50" value="'.$phone[1].'">';
  $design=$design.'    </div>';
  $design=$design.'  </div>';
  $design=$design.'  <div class="height-5"></div>';

/*  
  $design=$design.'  <div class="w3-row-padding">';				
  $design=$design.'    <div class="w3-third">';
  $design=$design.'   <label>Contact Person Info</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="contactPerson-3" maxlength="50" style="text-transform:uppercase;" value="'.$contact[2].'" >';
  $design=$design.'    </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'   <label>E-Mail Address</label>';
  $design=$design.'     <input class="style-input style-border" type="email" name="email-3" maxlength="150" value="'.$email[2].'"> </div>';
  $design=$design.'    <div class="w3-third">';
  $design=$design.'   <label>Phone/Mobile Number</label>';
  $design=$design.'     <input class="style-input style-border" type="text" name="phone-3" maxlength="50"value="'.$phone[2].'">';
  $design=$design.'    </div>';				
  $design=$design.'  </div>';		
*/
  
  $design=$design.'  <div class="height-20"></div>
                     <input type=hidden name="whatToDo" value="" />';
					 
  if(isset($_GET['id'])) {
    $design=$design.'<input type="submit" class="w3-button w3-pink w3-round-small" name="update_field" value="Update" />';
  }
  else { 
    $design=$design.'  <input type="submit" class="w3-button w3-blue w3-round-small"  name="save_view" 
	                   onclick="this.form.whatToDo.value = this.value;" value="Save" />&nbsp;
	                   <input type="submit" class="w3-button w3-blue w3-round-small" name="save_addnew" value="Save, Add New"
					    onclick="this.form.whatToDo.value = this.value;" />'; 
  }
  
  $design=$design.' &nbsp;<input type="button" onclick="discharge()" class="w3-button w3-black w3-round-small" name="batal" value="Discharge" />';
  $design=$design.' </form><div class="height-20"></div>';  		
  
  echo $design;
  
  if($_GET['valid'] == 1) 
  {
	echo '<script>swal("Success","Your entry has been saved.");</script>';   
  }	  
</script>

</div></div><div class="height-30"></div></div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegCustomer").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("dostore_cust.php", formValues, function(data){ $("#result").html(data); });
    });	  
  });

  function discharge() { $url="/e-imp/master.data/?show=vcust";  location.replace($url); }     
</script>     

<script>
    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }
</script> 