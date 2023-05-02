<script language="php">
  session_start();
  include("../asset/libs/db.php");	
  
  if(isset($_GET['id'])) {
	$keywrd=$_GET['id'];
	$validToDel=1;
	
	$query="Select * From m_EmployeeFunction Where functionID=".$keywrd."; ";
	echo $query.'<br>';
	$result=mssql_query($query);
	if(mssql_num_rows($result) >= 1) {
	  $row=mssql_fetch_array($result);
	  $deskripsi=$row[0]; }
	mssql_free_result($result);
	
	if(strlen(trim($deskripsi)) > 0) {
	  //if(strpos($deskripsi, 'DIRECTOR') >= 0) { $validToDel = 0; }
	  //if(strpos($deskripsi, 'SURVEYOR') >= 0) { $validToDel = 0; }
	  if($deskripsi == 'SURVEYOR' || $deskripsi == 'DIRECTOR') { $validToDel = 0; }
    }		
	
	if($validToDel == 1) {
      $query="Select * From m_EmployeeFunctionLog Where currentFunction=".$keywrd;
      $result=mssql_query($query);
	  if(mssql_num_rows($result) <= 0) {
	    mssql_free_result($result);
	    $query="Delete From m_EmployeeFunction Where functionID=".$keywrd."; ";
	    $result=mssql_query($query);	
        echo '<script>$("#result").load("function.php");</script>'; }	
	  
  	  else {
	    mssql_free_result($result); 
        echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 	  
        echo '<script>$("#result").load("function.php");</script>'; }
	}
	else {
      echo '<script>swal("Error","Current function not allow remove from system.","error");</script>'; 	  
      //echo '<script>$("#result").load("function.php");</script>'; }
	  echo $validToDel;
  }
  }	
</script>