<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  if(isset($_GET['id'])) {
    $keywrd=$_GET['id'];
	
	$query="Select MNRLogID From MNRHeader Where groupID='$keywrd'";
	$result=mssql_query($query);
    if(mssql_num_rows($result) <= 0) {
	  $query="Delete From m_GroupRepair Where groupID='$keywrd'; ";
	  $query=$query."Delete From m_GroupRepairHeader Where groupID='$keywrd'; ";
	  $query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	  $query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Workgroup : ','$keywrd')); ";
	  $result=mssql_query($query); 
	  echo '<script>$("#result").load("reg_group.php");</script>'; }	  

	else {
	  mssql_free_result($result);
      echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
      echo '<script>$("#result").load("reg_group.php");</script>'; }	  
  }
</script>