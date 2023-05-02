<script language="php">
  session_start();
  
  if(isset($_GET['file_n']) && isset($_GET['size']) && isset($_GET['type']) && isset($_GET['book'])) {
	include("../asset/libs/db.php");
	 
    $file_Name = $_GET['file_n'];
	$contSize = $_GET['size'];
	$contType = $_GET['type'];
	$bookID = $_GET['book'];
	
	$query = "Delete From containerJournal Where bookInID In (Select bookID As bookInID From tabBookingHeader 
	                                                          Where SLDFileName = '$file_Name' And bookID = '$bookID')
			  And gateIn Is Null;			
			  
              Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Waiting In Container, SLDFileName : ','$file_Name')); ";
	$result = mssql_query($query);
	mssql_close($dbSQL);
	echo '<script>swal("Success","Selected record has been deleted from Database.");</script>'; 
	$var = 'key='.$file_Name;
	echo '<script type="text/javascript">$("#summary_sld").load("summary_sld.php?'.$var.'");</script>';
  }
</script>