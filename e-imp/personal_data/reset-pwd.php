<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  $keywrd=strtoupper(trim($_GET['id']));
  
  $query="Update userProfile set accessKey=userID Where userID='$keywrd'; ";
  $query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
  $query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Reset Password: ','$keywrd')); ";	  
  $result=mssql_query($query);		 
  
  echo '<script>swal("Success","Password has been reset.");</script>'; 
  echo '<script>$("#result").load("users_list.php");</script>';
</script>