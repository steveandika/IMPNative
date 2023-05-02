<script language="php">
  session_start();
  
  $defHTML = $_SESSION['defurl'];
  
  if (isset($_GET['prn'])) { $param = $_GET['prn']; }
  if (isset($_GET['filter'])) { $filter = $_GET['filter']; }
  if (isset($_GET['cnd'])) { $condition = $_GET['cnd']; }
  if (isset($_GET['is'])) { $filterVal = $_GET['is']; }
  if (isset($_GET['dcn'])) { $docNumber = base64_decode($_GET['dcn']); }
  
  if (isset($_GET['whatToDo'])){
	if ($_GET['whatToDo'] == "apply"){
	  $date = $_GET['invdate']; 
	  $invoiceNo = $_GET['invNumb'];
	  $mode = "UPDATE";	  
	  $execResult = "";

	  $sql = "Select estimateID, BillParty from CollectedRepair where DocNumber = '$docNumber' order by estimateID; ";	
	  $sqlRes = mssql_query($sql);
	  
	  while($colArr = mssql_fetch_array($sqlRes)){        	  
	    $EOR = $colArr['estimateID'];
	    $billParty = $colArr['BillParty'];	          
		  
  	    $stmt = mssql_init("C_DNDocumentDetail"); 
        mssql_bind($stmt, "@uid", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);	  
	    mssql_bind($stmt, "@mode", $mode, SQLVARCHAR, false, false, 10);	  
	    mssql_bind($stmt, "@DocName", $docNumber, SQLVARCHAR, false, false, 100);
	    mssql_bind($stmt, "@InvNum", $invoiceNo, SQLVARCHAR, false, false, 30);
        mssql_bind($stmt, "@InvDate", $date, SQLVARCHAR, false, false, 10);
        mssql_bind($stmt, "@EOR", $EOR, SQLVARCHAR, false, false, 30);
        mssql_bind($stmt, "@BillParty", $billParty, SQLVARCHAR, false, false, 2);
	    mssql_bind($stmt, "@Result", $execResult, SQLVARCHAR, true, false, 150);	
	    $result = mssql_execute($stmt); 		
	  }
	  mssql_free_result($sqlRes);
	  
	  if ($execResult != ""){
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
 
<script language="php">		  
	  }
      else {
	    $dataurl = $defHTML.'/e-imp/1?src='.base64_encode("newmnr/logDebetNote.php").'&filter='.$filter.'&cnd='.$condition.'&is='.$filterVal;	
	    echo "<script type='text/javascript'>location.replace('$dataurl');</script>";		  
      }		  
	}	
  }
  else {  
    $sql = "Select TOP(1) invoiceNumber, invoiceDTTM from CollectedRepair where DocNumber = '$docNumber' ";
	$result = mssql_query($sql);
	while($colArr = mssql_fetch_array($result)){
	  $invoiceNo = $colArr['invoiceNumber'];	
	}	
    mssql_free_result($result);
</script>

    <div class="height-40"></div>	
    <div id="pageTitle">SET INVOICE</div> 
    <div class="height-20"></div>	
    <div class="w3-container" style="max-width:800px;margin:0 auto">
      <div class="frame">	
       <div class="height-10"></div>
	
	   <form method="get">
	    <input type="hidden" name="src" value="<?php echo base64_encode('newmnr/setInvoice.php') ?>" />
		<input type="hidden" name="prn" value="<?php echo $param; ?>" />
		<input type="hidden" name="filter" value="<?php echo $filter; ?>" />
		<input type="hidden" name="cnd" value="<?php echo $condition; ?>" />
		<input type="hidden" name="is" value="<?php echo $filterVal; ?>" />
		<input type="hidden" name="dcn" value="<?php echo base64_encode($docNumber); ?>" />
	    <input type="hidden" name="whatToDo" value="" />
	  
        <div class="w3-container">
	     <div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-quarter">Document Number</div>
		  <div id="privateStyleLabel" class="w3-threequarter"><strong><?php echo $docNumber ?></strong></div>
	     </div>
	     <div class="height-3"></div>
	  
	     <div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-quarter">Invoice Number</div>
		  <div class="w3-threequarter" style="padding:0px"><input type="text" id="privateStyleInput" style="width:100%" maxlength="30" name="invNumb" value="<?php echo $invoiceNo; ?>" style="text-transform:uppercase" required /> </div>
	     </div>
	     <div class="height-3"></div>
	  
	     <div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-quarter">Invoice Date</div>
		  <div class="w3-threequarter" style="padding:0px"><input type="date" id="privateStyleInput" name="invdate" required /> </div>
	     </div>	 
	    </div>
		<div class="height-10"></div>
		
		<script language="php">
          $sql = "Select a.estimateID, b.containerID,
                         BillParty, ISNULL(SUM(c.totalValue), 0) totalBill, b.currencyAs, a.CostCenter
	              from CollectedRepair a left join RepairHeader b on b.estimateID = a.estimateID
		                                 left join RepairDetail c on c.estimateID = b.estimateID 
		          where a.DocNumber = '$docNumber'
		          group by a.estimateID, c.isOwner, b.estimateDate, b.containerID, BillParty, b.currencyAs, a.CostCenter
			      order by b.estimateDate, a.estimateID; ";
          $result = mssql_query($sql);				  
		  
		  $html  = '<div style="height:40vh;overflow-x:auto">';		  
		  $html .= ' <table style="w3-striped">';
		  $html .= '  <tr style="background-color:#000;color:#fff">';
		  $html .= '   <th>Estimate#</th>';
		  $html .= '   <th>Container#</th>';
		  $html .= '   <th>Bill Party</th>';
		  $html .= '   <th style="text-align:right">Value</th>';
		  $html .= '  </tr>';
		  
		  while($colArr = mssql_fetch_array($result)){
		    $html .= '  <tr>';
		    $html .= '   <td style="padding:3px">'.$colArr['estimateID'].'</td>';			
			$html .= '   <td style="padding:3px">'.$colArr['containerID'].'</td>';			
			$html .= '   <td style="padding:3px">'.$colArr['BillParty'].'</td>';			
			$html .= '   <td style="padding:3px;text-align:right">'.number_format($colArr['totalBill'],2,",",".").'</td>';			
		    $html .= '  </tr>';			
		  }
		  
		  $html .= ' </table>';
		  $html .= '</div>';
		  
		  mssql_free_result($result);
		  
		  echo $html;
		</script>
		
		
	    <div class="height-20" style="border-bottom:1px solid #ddd"></div>
	    <div class="height-10"></div>
	    <div class="w3-container">
	      <button type="submit" class="imp-button-grey-blue" value="apply" onclick="this.form.whatToDo.value = this.value;" >Apply</button>
		  <script language="php">
		    $dataurl = $defHTML.'/e-imp/1?src='.base64_encode("newmnr/logDebetNote.php").'&filter='.$filter.'&cnd='.$condition.'&is='.$filterVal;	
			echo '<a href="'.$dataurl.'" class="imp-button-grey-blue">Cancel</a>';
		  </script>
		  
	    </div>
	   </form>
	
	   <div class="height-10"></div>
      </div>	
    </div>
	
<script language="php">
  }
</script>	