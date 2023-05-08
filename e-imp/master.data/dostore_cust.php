<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  if(isset($_POST['custID']) && trim($_POST['custID']) != "") {
	$custid = trim($_POST['custID']);
	if(!isset($_POST["depotRate"])) { $depotRate=0; }
	else { $depotRate=$_POST["depotRate"]; };
	
    $query="Update m_Customer Set 
	        completeName='".strtoupper($_POST['custName'])."', shortName='".strtoupper($_POST['shortname'])."', officeAddress='".strtoupper($_POST['officeAddr'])."', phoneNo='".$_POST['phoneNumber']."',
			faxNo='".$_POST['faxNumber']."', NPWP='".$_POST['taxNumber']."', NPWPAddress='".strtoupper($_POST['taxAddress'])."' ";
			
	if((isset($_POST['isExportir'])) && ($_POST['isExportir']=="on")) { $query=$query.", asExp=1"; }
	else { $query=$query.", asExp=0"; }
	if((isset($_POST['isImportir'])) && ($_POST['isImportir']=="on")) { $query=$query.", asImp=1"; }
	else { $query=$query.", asImp=0"; }
	if((isset($_POST['isLogistic'])) && ($_POST['isLogistic']=="on")) { $query=$query.", asLogParty=1"; }
	else { $query=$query.", asLogParty=0"; }
	if((isset($_POST['isFeeder'])) && ($_POST['isFeeder']=="on")) { $query=$query.", asFeed=1"; }
	else { $query=$query.", asFeed=0"; }	
	if((isset($_POST['isMLO'])) && ($_POST['isMLO']=="on")) { $query=$query.", asMLO=1"; }
	else { $query=$query.", asMLO=0"; }
	if((isset($_POST['isSupplier'])) && ($_POST['isSupplier']=="on")) { $query=$query.", asSupp=1"; }
	else { $query=$query.", asSupp=0"; }
	if((isset($_POST['isOther'])) && ($_POST['isOther']=="on")) { $query=$query.", asOther=1"; }
	else { $query=$query.", asOther=0"; }	
	$query=$query." ,LabourRateCost=".$_POST['depotRate'].", currRepair='".strtoupper($_POST["currRate"])."', repairPriceCode='".$_POST['repairCode']."' Where custRegID='$custid'; ";
	
	$query=$query." Delete From m_CustomerContact Where custRegID='$custid'; ";
	if((isset($_POST['contactPerson-1'])) && (Trim($_POST['contactPerson-1']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-1'])."', '";
	  $query=$query.$_POST['email-1']."', '".$_POST['phone-1']."'); "; }
	if((isset($_POST['contactPerson-2'])) && (Trim($_POST['contactPerson-2']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-2'])."', '";
	  $query=$query.$_POST['email-2']."', '".$_POST['phone-2']."'); "; }
	if((isset($_POST['contactPerson-3'])) && (Trim($_POST['contactPerson-3']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-3'])."', '";
	  $query=$query.$_POST['email-3']."', '".$_POST['phone-3']."'); "; }
	
	$query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	$query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Customer Detail ','".$custid."')); ";
    
	$result = mssql_query($query);
    mssql_close($dbSQL);	
	echo '<script>swal("Success","Record has been updated.");</script>'; 
	echo '<script>$("#result").load("customer.php?id=+'.$custid.'");</script>'; 
  }
  else {
	$shortName  = $_POST["shortname"];
	
	$query="Declare @KeyField VarChar(4); ";
	$query=$query."If Not Exists(Select * From logKeyField Where keyFName = 'CSTM') Begin ";
	$query=$query."  Set @KeyField='C001'; ";
	$query=$query."  Insert Into logKeyField Values('CSTM', 1); ";
	$query=$query."End Else Begin ";
	$query=$query."      Declare @LastKey Int, @StrLastKey VarChar(3); ";
	$query=$query."      Select @LastKey=lastNumber +1 From logKeyField Where KeyFName = 'CSTM'; ";
	$query=$query."      Set @StrLastKey = LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey))); ";
	$query=$query."      if LEN(@StrLastKey) = 1 Set @KeyField = CONCAT('C00', @StrLastKey); ";
	$query=$query."      if LEN(@StrLastKey) = 2 Set @KeyField = CONCAT('C0', @StrLastKey); ";
	$query=$query."      if LEN(@StrLastKey) = 3 Set @KeyField = CONCAT('C', @StrLastKey); ";
	$query=$query."      Update logKeyField Set lastNumber=lastNumber +1 Where KeyFName = 'CSTM'; ";
	$query=$query."    End; ";
	
	$query=$query."Insert Into m_Customer(custRegID, completeName, officeAddress, phoneNo, faxNo, isPPNInclude, ";
	$query=$query."NPWP, NPWPAddress, LabourRateCost, repairPriceCode, asExp, asImp, asLogParty, asMLO, asFeed, asSupp, asOther, ShortName) Values(";
	$query=$query."@KeyField, '".strtoupper($_POST['custName'])."', '".strtoupper($_POST['officeAddr'])."', '".$_POST['phoneNumber']."', '";
	$query=$query.$_POST['faxNumber']."', 1, '".$_POST['taxNumber']."', '".strtoupper($_POST['taxAddress'])."', ";
	$query=$query."0, '".$_POST['repairCode']."', ";
	if((isset($_POST['isExportir'])) && ($_POST['isExportir']=="on")) { $query=$query."1,"; }
	else { $query=$query."0,"; }
	if((isset($_POST['isImportir'])) && ($_POST['isImportir']=="on")) { $query=$query."1, "; }
	else { $query=$query."0, "; }
	if((isset($_POST['isLogistic'])) && ($_POST['isLogistic']=="on")) { $query=$query."1,"; }
	else { $query=$query."0, "; }
	if((isset($_POST['isMLO'])) && ($_POST['isMLO']=="on")) { $query=$query."1,"; }
	else { $query=$query."0, "; }
	if((isset($_POST['isFeeder'])) && ($_POST['isFeeder']=="on")) { $query=$query."1,"; }
	else { $query=$query."0, "; }
	if((isset($_POST['isSupplier'])) && ($_POST['isSupplier']=="on")) { $query=$query."1,"; }
	else { $query=$query."0, "; }
	if((isset($_POST['isOther'])) && ($_POST['isOther']=="on")) { $query=$query."1, '$shortName'); "; }
	else { $query=$query."0, '$shortName'); "; }

	if((isset($_POST['contactPerson-1'])) && (Trim($_POST['contactPerson-1']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-1'])."', '";
	  $query=$query.$_POST['email-1']."', '".$_POST['phone-1']."'); "; }
	if((isset($_POST['contactPerson-2'])) && (Trim($_POST['contactPerson-2']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-2'])."', '";
	  $query=$query.$_POST['email-2']."', '".$_POST['phone-2']."'); "; }
	if((isset($_POST['contactPerson-3'])) && (Trim($_POST['contactPerson-3']) != "")) {
	  $query=$query."Insert Into m_CustomerContact Values('$custid', '".strtoupper($_POST['contactPerson-3'])."', '";
	  $query=$query.$_POST['email-3']."', '".$_POST['phone-3']."'); "; }
	
	$result = mssql_query($query); 	 
	
	$query="";
	$query=$query."Select custRegID From m_Customer Where completeName='".strtoupper($_POST['custName'])."'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);
	  $custid=$row[0];
	  mssql_free_result($result);
	  
	  if($_POST['whatToDo']=="Save") 
	  {
		$url = "?show=vcust";
		echo "<script type='text/javascript'>location.replace('$url');</script>"; 
	    /*echo '<script>$("#result").load("customer.php?id=+'.$custid.'");</script>'; */
	  } 
	  else 
	  {
		$url = "?show=vcust&new=1&valid=1";
		echo "<script type='text/javascript'>location.replace('$url');</script>"; 
		
        /*echo '<script>$("#result").load("manage_cust.php");</script>'; */
	  }	  
	  //echo '<script>$("#result").load("customer.php?id=+'.$custid.'");</script>'; }  
    }  
	else { 
	  echo '<script>$("#result").load("customer.php");</script>'; 
	}  
  } 
</script>  