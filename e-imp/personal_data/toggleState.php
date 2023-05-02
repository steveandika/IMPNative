<script language="php">
  include ("../asset/libs/db.php");
  $keywrd = $_GET['id'];
  
  $query="Update userProfile Set isLogin=0 Where userID='$keywrd'";
  $result=mssql_query($query);
  mssql_close($dbSQL);
  
  echo '<script>swal("Success","Login state has been reset.");</script>'; 
  echo '<script>$("#result").load("users_list.php");</script>'; 

</script>