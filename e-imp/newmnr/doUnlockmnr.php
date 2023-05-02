<script language="php">
  $errMsg = "";
  
  if (isset($_POST["enbr"]) && isset($_POST["user"])){
    include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php");
    $conn = openDB();
	
	if ($conn == "connected"){
	  $nbr_estimate = $_POST["enbr"];	
	  $requester_name = $_POST["user"];
	  $reason = $_POST["desc"];
	  $spResult = "";
      
      $stmt = mssql_init("C_ChangeMNRStatus");
      mssql_bind($stmt, "@estimateID", $nbr_estimate, SQLVARCHAR, false, false, 30);
      mssql_bind($stmt, "@requester", $requester_name, SQLVARCHAR, false, false, 20);	  
	  mssql_bind($stmt, "@reason", $reason, SQLVARCHAR, false, false, 150);
	  mssql_bind($stmt, "@reviewSP", $spResult, SQLVARCHAR, true, false);
	  $result = mssql_execute($stmt);
	  mssql_free_statement($stmt);
	}	
	else {
	  $errMsg = "Main Database was failed to reached. Try to re submit the entry form or refresh your browser by pressing F5 button.";	
	}	
  } 
  else {
	$errMsg = "Invalid parameter has been found. Make sure all field parameter fullfil with proper data.";  
  } 	  
</script>

<div class="frame-borderless border-radius-3" style="height:61.5vh;">
  <div class="frame" style="border-color:#ecf0f1!important">
  
    <script language="php"> 
	  if ($errMsg != "") { echo $errMsg; }
	  else { echo $spResult; } 
    </script> 

  </div>  
</div>