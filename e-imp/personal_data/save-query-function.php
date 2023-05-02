<script language="php">
  include ("../asset/libs/db.php");
  
  if(isset($_POST['id']) && $_POST['id'] != 0) {
    $keywrd=$_POST['id'];
	$validToEdit = 1;
	
	$query="Select * From m_EmployeeFunction Where functionID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) >= 1) {
	  $row=mssql_fetch_array($result);
	  $deskripsi=$row[0]; }
	mssql_free_result($result);
	
	if(strlen(trim($deskripsi)) > 0) {
	  if(strpos($deskripsi, 'DIRECTOR') >= 0) { $validToEdit = 0; }
	  if(strpos($deskripsi, 'SURVEYOR') >= 0) { $validToEdit = 0; }
    }		
	
	if($validToEdit == 1) {
	  $deskripsi=strtoupper($_POST['deskripsi']);
	
	  $query="Update m_EmployeeFunction Set Description='$deskripsi' Where functionID=".$keywrd;
	  $result=mssql_query($query);
	  echo '<script>swal("Success","Record has been updated.");</script>'; 
	  echo '<script>$("#result").load("function.php?deskripsi=+'.$deskripsi.'");</script>'; }  

	else {
      echo '<script>swal("Error","Current function not allow for editing.","error");</script>'; 	  
      echo '<script>$("#result").load("function.php");</script>'; }
  }	  
  else {
	$deskripsi=strtoupper($_POST['deskripsi']);
    
    $query="Declare @KeyField Int; ";
    $query=$query."Select @KeyField=MAX(functionID)+1 From m_EmployeeFunction; ";
    $query=$query."If Not Exists(Select * From m_EmployeeFunction Where Description='$deskripsi') Begin ";
    $query=$query."  Insert Into m_EmployeeFunction(Description, functionID) ";
    $query=$query."  Values('$deskripsi', @KeyField); ";
    $query=$query."End; ";
    $result=mssql_query($query); 
  
	echo '<script>swal("Success","Your entry has been saved.")</script>'; 
	if($_POST["whatToDo"] == 'save_AddNew') { echo '<script>$("#result").load("manage-function.php");</script>'; }
	else { echo '<script>$("#result").load("function.php");</script>'; }		
  }	
	//echo '<script>$("#result").load("function.php");</script>'; }	 
</script>