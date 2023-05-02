<script language="php">
  session_start();
  include ("../asset/libs/db.php");

  $keywrd=strtoupper(trim($_GET['id']));
  
  $query="Delete From userMenuProfile Where userID Like '$keywrd'; 
          Delete From userProfile Where userID Like '$keywrd';
  
          Insert Into userLogAct(userID, dateLog, DescriptionLog) 
          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Delete User : ','$keywrd')); ";	  
  $result=mssql_query($query);		 
  
  echo '<script>swal("Success","Profile has been deleted from list.");</script>'; 
  echo '<script>$("#result").load("user_list.php");</script>';
</script>