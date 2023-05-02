<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  if(isset($_GET['emp'])) {
    $keywrd=$_GET['emp'];
	$id='';
	
	$query="Select groupID From m_GroupRepair Where empRegID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result)) {
	  $arr=mssql_fetch_array($result);
	  $id=$arr[0];
	  mssql_free_result($result); }
	
	$query="Delete From m_GroupRepair Where empRegID='$keywrd'; ";
	$query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	$query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Workgroup Member: ','$keywrd')); ";
	$result=mssql_query($query); 
	echo '<script>$("#result").load("setup_team.php?id=+'.$id.'");</script>'; }	  
</script>