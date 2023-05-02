<script src="../asset/js/sweetalert2.min.js"></script> 
<script language="php">
  if(isset($_GET['id'])) {
	include("../asset/libs/db.php");  
    $bookID = $_GET['id'];
	$keywrd = $_GET['unit'];
	$file_name = $_GET['dirname'];
	
	$query = "Delete From containerPhoto Where BookID='$bookID' And directoryName='$file_name' And containerID='$keywrd'";
	$result = mssql_query($query);
	$dirfile = 'photo/'.$file_name;

	if(!unlink($dirfile)) {
	  echo '<script>swal("Error","There was an error while trying to delete selected file.","error");</script>'; }
	else {
	  echo '<script>swal("Success","File deleted, please refresh list by press View Photo button.");</script>'; }	
	mssql_close($dbSQL);	
  }
</script>