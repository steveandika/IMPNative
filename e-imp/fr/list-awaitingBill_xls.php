<script language="php">	
  $namaFile = "awaitingBill_".$_GET['billingParty']."_MLO".$_GET['mlo'];
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$namaFile.".xls");  
  
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
  
  include_once ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php"); 
  $dbconn = openDB();
  if ($dbconn == "connected"){
    $sql = "Select * from ".$viewname." where (gateIn BETWEEN '$dttm1' and '$dttm2') ";

    if ($workshop != "ALL") { $sql .= "and workshopID = '$workshop' and currencyAS = '$currency' "; }
	else { $sql .= "and currencyAS = '$currency' "; }
	if ($activityStr != "ALL") { $sql .= "and ActivityType = '$activityStr' "; }
	if ($mlo != "ALL") { $sql .= "and shortName = '$mlo' "; }
	
	$sql .= " order by workshopID, gateIn, shortName; ";
    $result = mssql_query($sql);
	
    $html  = '';
	
	$html .= '<table>';
	$html .= '  <tr">';
	$html .= '    <th>Hamparan</th>';		
	$html .= '    <th>Container#</th>';		
	$html .= '    <th>Estimate#</th>';
	$html .= '    <th>Estimate Date</th>';
	$html .= '    <th>Date In</th>';		
	$html .= '    <th style="text-align:right">Value</th>';		
	$html .= '    <th>Activity</th>';		
	$html .= '    <th>Currency</th>';		
	$html .= '    <th>Shipping Line</th>';		
	$html .= '  </tr>';
		
	$result = mssql_query($sql);		
	while ($data = mssql_fetch_array($result)){  
      $html .= '  <tr>';
	  $html .= '    <td>'.$data['workshopID'].'</td>';
	  $html .= '    <td>'.$data['NoContainer'].'</td>';
	  $html .= '    <td>'.$data['estimateID'].'</td>';
	  $html .= '    <td>'.date('Y-m-d', strtotime($data['estimateDate'])).'</td>';
	  $html .= '    <td>'.date('Y-m-d', strtotime($data['gateIn'])).'</td>';
	  $html .= '    <td style="text-align:right">'.number_format($data['TotalEstimate'], 2,",",".").'</td>';	
	  $html .= '    <td>'.$data['ActivityType'].'</td>';	
      $html .= '    <td>'.$data['currencyAS'].'</td>';		
      $html .= '    <td>'.$data['shortName'].'</td>';			  
	  $html .= '  </tr>';			
	}
	mssql_free_result($result);
		
	$html .= '    </table>';
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>I-ConS</title>
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  

  <style>
    table { border:0;padding:0;border-collapse:collapse;border-spacing:0;letter-spacing:1px; }
    table tr { border: 0;padding: 3px; }
    table th, table td { padding: 3px;text-transform:uppercase}
	table td { border:0px }
    table th { border:0px }
  </table>
</head>
  
<body>  
<script language="php">
    echo $html;		
  }	  	  
</script> 
</body>
</html>