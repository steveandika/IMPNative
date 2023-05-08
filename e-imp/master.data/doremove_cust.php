<script language="php">
  session_start();
  include ("../asset/libs/db.php");  
  
  if(isset($_GET['id'])) {	   
    $keywrd=trim($_GET['id']);
    
    $allowDel = 1;	    
	$query="Select custRegID From MNRHeader Where custRegID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) >= 1) { $allowDel = 0; }
	mssql_free_result($result);
	
    if($allowDel == 1) {
	  $query="Select operatorID From m_vessel Where operatorID='$keywrd'";
	  $result=mssql_query($query);
	  if(mssql_num_rows($result) >=1) { $allowDel = 0; }
	  mssql_free_result($result);
	}
	
    if($allowDel == 1) {    
	  $query="Delete From m_Customer Where custRegID='$keywrd'; 
	          Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Customer : ','$custid')); ";
	  $result=mssql_query($query); 
	  mssql_close($dbSQL);
	  echo '<script>swal("Success","Selected record has been deleted from Database.");</script>'; 
	  echo '<script>$("#result").load("customer.php");</script>'; 
	}	  
    else {
	  mssql_close($dbSQL);
      echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
      echo '<script>$("#result").load("customer.php");</script>'; 
	}	  
  }	
</script>