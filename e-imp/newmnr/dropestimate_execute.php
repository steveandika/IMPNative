<script language="php"> 
  include ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php");
  $dbconn = openDB();
  
  if (isset($_POST['reason']) && $dbconn == "connected") {
	$estimateNo = $_POST['estimate'];
    $remark = $_POST['reason'];
    $hasilEksekusi = '';
	
    $stmt = mssql_init("C_DropEstimate");
    mssql_bind($stmt, "@estimateID", $estimateNo, SQLVARCHAR, false, false, 30);
    mssql_bind($stmt, "@remark", $remark, SQLVARCHAR, false, false, 100);	  
    mssql_bind($stmt, "@result", $hasilEksekusi, SQLVARCHAR, true, false, 30);
    $result = mssql_execute($stmt);
    mssql_free_statement($stmt);	  
	
	if ($result){ echo $hasilEksekusi; }	
  }
</script>