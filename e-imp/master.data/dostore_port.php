<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  if(isset($_POST['id'])) {
    $keywrd=trim($_POST['id']);
	
	$query="Update m_harbour Set portDescription='".strtoupper($_POST['namaport'])."',
	        countryDescription='".strtoupper($_POST['countryname'])."',
			countryCode='".strtoupper($_POST['countryid'])."' Where portCode='$keywrd';
			Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	        Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Port Detail: ','".$keywrd."')); ";
	$result=mssql_query($query);
	echo '<script>swal("Success","Record has been updated.");</script>'; 
	echo '<script>$("#result").load("ports.php");</script>'; 
    mssql_close($dbSQL); }
  
  else {
	$query="Declare @NewKey Int, @LastKey Int;
	        Select @LastKey=MAX(portID) From m_harbour;
            Set @NewKey=@LastKey +1;
            Insert Into m_harbour(portID, portCode,portDescription, countryCode, countryDescription) 
           	Values(@NewKey, '".strtoupper($_POST['kodeport'])."', '".strtoupper($_POST['namaport'])."', '".
			strtoupper($_POST['countryid'])."', '".strtoupper($_POST['countryname'])."');";
	$result=mssql_query($query);
	mssql_close($dbSQL);
	
	echo '<script>swal("Success","Your entry has been saved.")</script>'; 
	echo '<script>$("#result").load("ports.php");</script>'; }	
</script>