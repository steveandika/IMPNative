<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  if(isset($_GET['id'])) {
     $keywrd=trim($_GET['id']);
	 
	 $allowDel = 0;	 
     $query="Select * From userProfile Where userID='$keywrd'";
     $result=mssql_query($query);	 
	 if(mssql_num_rows($result) <=0) { $allowDel = 1; }
	 mssql_free_result($result);
	 
	 if($allowDel == 1) {
	  $query="Delete From m_Employee Where empRegID='$keywrd'; 
	          Delete From m_EmployeeFunctionLog Where empRegID='$keywrd'; 		  
	          Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Delete Employee : ','$custid')); ";	  
	  $result=mssql_query($query);		 
	  mssql_close($dbSQL);
	  echo '<script>$("#result").load("emp_list.php");</script>'; }	  
	  
     else {	  	 
	   mssql_close($dbSQL); 
       echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 	  
       echo '<script>$("#result").load("emp_list.php");</script>'; }	  
  }	   
</script>