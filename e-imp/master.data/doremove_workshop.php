<script language="php">
  session_start();
  if(isset($_GET['id'])) {	   
    include ("../asset/libs/db.php");  
  
    $keywrd=trim($_GET['id']);
	echo $_GET['id'];
	$query="Select locationID From MNRHeader Where locationID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) <= 0) {	
	  mssql_free_result($result);	  
	  $query="Select locationID From userProfile Where locationID='$keywrd'";
  	  $result=mssql_query($query);
	  if(mssql_num_rows($result) <= 0) {	
	    mssql_free_result($result);		
	    $query="Delete From m_Location Where LocationID='$keywrd'; ";
	    $query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	    $query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Workshop : ','$keywrd')); ";
		echo $query;
	    $result=mssql_query($query); 
	    echo '<script>$("#result").load("home.php");</script>'; }
     
	  else {
		mssql_free_result($result);  
        echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
        echo '<script>$("#result").load("home.php");</script>'; }	  
	}  	
	else {
	  mssql_free_result($result);
      echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
      echo '<script>$("#result").load("home.php");</script>'; }	  
  }	
</script>