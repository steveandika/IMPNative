<script language='php'>
  session_start();
  include_once ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php"); 	  
  $defHTML = $_SESSION['defurl'];
  
  $mlo = $_POST['mlo'];
  $dttm1 = $_POST['activityDTTM1'];
  $dttm2 = $_POST['activityDTTM2'];
  $activity = $_POST['activityType']; 
  $billParty = $_POST['billingParty'];
  $workshop = $_POST['hamparanName'];
  $currency = $_POST['currency'];
    
  if (isset($_POST['whatToDo'])){	  
    $postconfirm = $_POST['whatToDo'];
  } else {
	  $postconfirm = "";
    }  
  
  if ($billParty == "U1" || $billParty == "U2"){ $viewname = "C_WaitingInvoiceRepair_User"; }
  if ($billParty == "O"){ $viewname = "C_WaitingInvoiceRepair_Owner"; }
  if ($billParty == "T"){ $viewname = "C_WaitingInvoiceRepair_ThirdParty"; }
  
  if ($activity == 1) { $activityStr = "RP"; }
  if ($activity == 2) { $activityStr = "CL"; }
  if ($activity == 3) { $activityStr = "ALL"; }  
  
  $dbconn = openDB();
  
  if ($postconfirm == "confirm"){
    if ($dbconn == "connected"){
      $NoDoc = "";
	  $execResult = "";
	  $msgResult = "";
	  
	  $stmt = mssql_init("C_NewDNDocument"); 
      mssql_bind($stmt, "@mlo", $mlo, SQLVARCHAR, false, false, 15);	  
	  mssql_bind($stmt, "@Currency", $currency, SQLVARCHAR, false, false, 3);
      mssql_bind($stmt, "@Result", $NoDoc, SQLVARCHAR, true, false, 150);		  
	  $result = mssql_execute($stmt); 
	  
	  $counter = COUNT($_POST['select-item']);
	  for ($i=0; $i<$counter; $i++){
	    if (isset($_POST['select-item'][$i])){   	 
          $EOR = $_POST['select-item'][$i];
		  $mode = "INSERT";
		  $InvNum = "";
		  $date = new datetime('NOW');
		  
	      $stmt = mssql_init("C_DNDocumentDetail"); 
          mssql_bind($stmt, "@uid", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);	  
	      mssql_bind($stmt, "@mode", $mode, SQLVARCHAR, false, false, 10);	  
	      mssql_bind($stmt, "@DocName", $NoDoc, SQLVARCHAR, false, false, 100);
	      mssql_bind($stmt, "@InvNum", $InvNum, SQLVARCHAR, false, false, 30);
          mssql_bind($stmt, "@InvDate", $date, SQLINT4, false, false);
          mssql_bind($stmt, "@EOR", $EOR, SQLVARCHAR, false, false, 30);
          mssql_bind($stmt, "@BillParty", $billParty, SQLVARCHAR, false, false, 2);
	      mssql_bind($stmt, "@Result", $execResult, SQLVARCHAR, true, false, 150);	
	      $result = mssql_execute($stmt); 
	  
	      $msgResult .= $execResult."<br>";
		}  
	  }
    }		
  }	 
</script> 

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>I-ConS</title>
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />    
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
</head>

<body>  
  <div class="height-10"></div>
  <div id="frmMsg" class="frame boxshadow" style="max-width:500px;border-color:#2471a3!important;margin:0 auto">
    <div class="frame-title" style="background-color:#2471a3!important;color:#fff"><strong>I-ConS Notification</strong></div>
    <div class="height-20"></div>
    <div class="w3-container">
	  <?php echo $msgResult; ?>
	  <div class="padding-top-20 padding-bottom-20">
	    <form method="get" action="<?php echo $defHTML.'/e-imp/1'; ?>">
		  <input type="hidden" name="src" value="<?php echo base64_encode('newmnr/newDocDN.php'); ?>" />
		  
		  <button type="submit" class="imp-button-grey-blue">Confirm</button>
		</form>
	  </div>
    </div>	
  </div> 
</body>
</html>  