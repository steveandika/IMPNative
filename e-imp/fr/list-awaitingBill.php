<script language='php'>
  $defHTML = $_SESSION['defurl'];
  
  include_once ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php"); 	
  
  $mlo = $_GET['mlo'];
  $dttm1 = $_GET['activityDTTM1'];
  $dttm2 = $_GET['activityDTTM2'];
  $activity = $_GET['activityType']; 
  $billParty = $_GET['billingParty'];
  $workshop = $_GET['hamparanName'];
  $currency = $_GET['currency'];
     
  if ($billParty == "U1" || $billParty == "U2"){ $viewname = "C_WaitingInvoiceRepair_User"; }
  if ($billParty == "O"){ $viewname = "C_WaitingInvoiceRepair_Owner"; }
  if ($billParty == "T"){ $viewname = "C_WaitingInvoiceRepair_ThirdParty"; }
  
  if ($activity == 1) { $activityStr = "RP"; }
  if ($activity == 2) { $activityStr = "CL"; }
  if ($activity == 3) { $activityStr = "ALL"; }  
  
  $dbconn = openDB();
  if ($dbconn == "connected"){
    $sql = "Select * from ".$viewname." where (gateIn BETWEEN '$dttm1' and '$dttm2') ";

    if ($workshop != "ALL") { $sql .= "and workshopID = '$workshop' and currencyAS = '$currency' "; }
	else { $sql .= "and currencyAS = '$currency' "; }
	if ($activityStr != "ALL") { $sql .= "and ActivityType = '$activityStr' "; }
	if ($mlo != "ALL") { $sql .= "and shortName = '$mlo' "; }
	
	$sql .= " order by workshopID, gateIn, shortName; ";
    $result = mssql_query($sql);
	
    if ($postconfirm == ""){
      $numrows = mssql_num_rows($result);
      mssql_free_result($result);  
	  
	  $html  = '';
	  $html .= '<div class="frame border-radius-3">';
	  $html .= ' <div class="frame-title"><strong>Awaiting Bill List</strong></div> ';
	  $html .= ' <div class="w3-container">';
	  $html .= '   <div class="height-10"></div>';
	  	  
	  if ($numrows <= 0){ showMessage("0 record has found", "error"); }		  
	  else {	
	    $dataurl = $defHTML.'/e-imp/fr/list-awaitingBill_xls?mlo='.$mlo.'&activityDTTM1='.$dttm1.'&activityDTTM2='.$dttm2.'&activityType='.$activity.'&billingParty='.$billParty.'&hamparanName='.$workshop.'&currency='.$currency;
        $html .= '   <span>Total row(s): '.$numrows.'</span>';
		
		$html .= '   <div class="height-5"></div>';
		$html .= '   <a href='.$dataurl.' target="wexport" class="w3-button w3-grey border-radius-3">Export to XLS</a>';
		
		$html .= '   <div class="height-5"></div>';
		$html .= '   <div style="overflow-x:auto;height:80vh">';
		$html .= '    <table class="w3-striped">';
		$html .= '     <tr style="background:#000!important;color:#fff">';
		$html .= '      <th>Hamparan</th>';		
		$html .= '      <th>Container#</th>';		
		$html .= '      <th>Estimate#</th>';
		$html .= '      <th>Estimate Date</th>';
		$html .= '      <th>Date In</th>';		
		$html .= '      <th style="text-align:right">Value</th>';		
		$html .= '      <th>Activity</th>';		
		$html .= '      <th>Currency</th>';		
		$html .= '      <th>Shipping Line</th>';		
		$html .= '     </tr>';
		
		$result = mssql_query($sql);		
		while ($data = mssql_fetch_array($result)){
		  $val_send = $data['estimateID'];
		  
 		  $html .= '     <tr>';
		  $html .= '      <td>'.$data['workshopID'].'</td>';
		  $html .= '      <td>'.$data['NoContainer'].'</td>';
		  $html .= '      <td>'.$data['estimateID'].'</td>';
		  $html .= '      <td>'.date('Y-m-d', strtotime($data['estimateDate'])).'</td>';
		  $html .= '      <td>'.date('Y-m-d', strtotime($data['gateIn'])).'</td>';
		  $html .= '      <td style="text-align:right">'.number_format($data['TotalEstimate'], 2,",",".").'</td>';	
		  $html .= '      <td>'.$data['ActivityType'].'</td>';	
          $html .= '      <td>'.$data['currencyAS'].'</td>';		
          $html .= '      <td>'.$data['shortName'].'</td>';			  
		  $html .= '     </tr>';			
		}
		mssql_free_result($result);
		
		$html .= '    </table>';
		$html .= '   </div>';		
	    $html .= ' </div>';
	    $html .= ' <div class="height-10"></div>';
		$html .= '</div>';
        echo $html;		
	  }	  
    }	
  }	  
</script> 