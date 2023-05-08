<script language="php">
  session_start();
  include ("../asset/libs/db.php");

  if(isset($_GET['id'])){
    $keywrd=$_GET['id'];
	
	$allowDel = 1;
	$query="Select vesselid From MNRHeader Where vesselid='$keywrd' ";
	$result=mssql_query($query);
	if(mssql_num_rows($result) >= 1) { $allowDel = 0; }
	mssql_free_result($result);
	
    if($allowDel==1) {
	  $query="Delete From m_vessel Where vesselid='$keywrd'; 
  			  Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Delete Vessel Detail: ','".$keywrd."')); ";
	  $result=mssql_query($query);	 
	  mssql_close($dbSQL);
	  echo '<script>$("#content").load("list-vessel.php");</script>'; }	  	  
  
	else {
	  mssql_close($dbSQL);
      echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
      echo '<script>$("#content").load("list-vessel.php");</script>'; }	  
  }
</script>