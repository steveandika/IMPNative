<script language="php">
  session_start();  
  
  include_once ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php");   
  $connectDB = openDB();
  
  $defHTML = $_SESSION['defurl'];  
  
  if ($connectDB == "connected"){
    $invoiceNo = base64_decode($_GET['prm']);
    $docNumber = base64_decode($_GET['dcn']);	
    $filtervalue = $_GET['is'];		
	$condition = $_GET['cnd'];
	$filtername = $_GET['filter'];		

    $sql = "Select TOP(1) BillParty from CollectedRepair where DocNumber = '$docNumber' ";
    $result = mssql_query($sql);
    if (mssql_num_rows($result) > 0){
	  $colArr = mssql_fetch_array($result);
      $billParty = $colArr['BillParty'];	  
    } 
	else {
	  $billParty = "NA";
	}  
    mssql_free_result;	
	
    $EOR = "NA";
    $mode = "DELETE";
    $InvNum = "NA";
    $date = new datetime('NOW');
		
	$execResult = "";
		  
	$stmt = mssql_init("C_DNDocumentDetail"); 
    mssql_bind($stmt, "@uid", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);	  
	mssql_bind($stmt, "@mode", $mode, SQLVARCHAR, false, false, 10);	  
	mssql_bind($stmt, "@DocName", $docNumber, SQLVARCHAR, false, false, 100);
	mssql_bind($stmt, "@InvNum", $invoiceNo, SQLVARCHAR, false, false, 30);
    mssql_bind($stmt, "@InvDate", $date, SQLINT4, false, false);
    mssql_bind($stmt, "@EOR", $EOR, SQLVARCHAR, false, false, 30);
    mssql_bind($stmt, "@BillParty", $billParty, SQLVARCHAR, false, false, 2);
	mssql_bind($stmt, "@Result", $execResult, SQLVARCHAR, true, false, 150);	
	$result = mssql_execute($stmt); 
  }	  
</script>

<div class="height-40"></div>
<div class="height-10"></div>
<div id="frmMsg" class="frame boxshadow" style="max-width:500px;border-color:#2471a3!important;margin:0 auto">
    <div class="frame-title" style="background-color:#2471a3!important;color:#fff"><strong>I-ConS Notification</strong></div>
    <div class="height-20"></div>
    <div class="w3-container">
	  <?php echo $execResult; ?>
	  <div class="padding-top-20 padding-bottom-20">
	    <form method="get">
		  <input type="hidden" name="src" value="<?php echo base64_encode("newmnr/logDebetNote.php"); ?>" />
		  <input type="hidden" name="filter" value="<?php echo $filtername; ?>" />
		  <input type="hidden" name="cnd" value="<?php echo $condition; ?>" />
		  <input type="hidden" name="is" value="<?php echo $filtervalue; ?>" />
		  
		  <button type="submit" class="imp-button-grey-blue">Confirm</button>
		</form>
	  </div>
    </div>	
 </div>