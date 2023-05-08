<script language="php">
  session_start();  
  include("../asset/libs/db.php");
 
  if(isset($_GET['id'])) {
    $keywrd=RTRIM(LTRIM($_GET['id']));
	$query="Select * From m_Customer Where repairPriceCode='$keywrd'";
	$result=mssql_query($query);
	
	if(mssql_num_rows($result) <= 0) {
	  $query="Delete From m_RepairPriceList_Header Where priceCode='$keywrd'; 
	          Delete From m_RepairPriceList Where priceCode='$keywrd'; ";
	  $query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                 Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Delete Price List : ','$keywrd')); ";
	  $result=mssql_query($query); 
	  echo '<script>swal("Success","Selected record deleted from list","error");</script>'; 
	  echo '<script>$("#result").load("price_list.php");</script>'; }

	else {
	  mssql_free_result($result);
      echo '<script>swal("Error","Selected record failed to remove. In used","error");</script>'; 
      echo '<script>$("#result").load("price_list.php");</script>'; }	  
  }	  
  
  mssql_close($dbSQL);
</script>