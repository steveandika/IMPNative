<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  if(isset($_GET['id'])) {
    $keywrd=$_GET['id'];
	
	$query="Declare @PortCode VarChar(5); 
	        Select @PortCode=portCode From m_harbour Where portID=".$keywrd."; 
			Delete From m_harbour Where portID=".$keywrd."; 
			Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	        Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Data Port: ','".$keywrd."')); ";	  
    $result=mssql_query($query); }
	
  mssql_close($dbSQL);
  echo '<script>$("#content").load("content.php");</script>'; 
</script>  