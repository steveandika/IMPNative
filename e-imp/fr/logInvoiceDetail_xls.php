<script language="php">
  $namaFile = base64_decode($_GET['dcn']);
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$namaFile.".xls");  
  include_once ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php");	  
  
  $docNumber = base64_decode($_GET['dcn']);
  $connDB = openDB();
  
  if ($connDB == "connected"){					 
    $qry = "Select a.estimateID, a.invoiceNumber, CONVERT(VARCHAR(10), invoiceDTTM, 105) invoiceDate, b.containerID, BillParty, CostCenter
		    from CollectedRepair a left join RepairHeader b on b.estimateID = a.estimateID
			where DocNumber = '$docNumber'
			order by 1; ";
    $result = mssql_query($qry);	  
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
  </style>
</head>
  
<body>  	  
  <script language="php">
    $html  = "<table>
	            <tr>
		          <th>INVOICE_NUMBER</th>
		          <th>INVOICE_DATE_YYYY-MM-DD</th>
		          <th>EOR_NUMBER</th>
		          <th>DOCUMENT_NUMBER</th>
                  <th>BILLING__PARTY_O_U1_U2_T</th>
	              <th>VOID_(Y/N)</th>
				  <th>COSTCENTER</th>
	            </tr>";

    while($arr = mssql_fetch_array($result)){	
      $html .= "<tr>
			      <td>".$arr['invoiceNumber']."</td>";
	  if ($arr['invoiceNumber'] == "") { $html .= "  <td></td>"; }
	  else { $html .= "  <td>".$arr['invoiceDate']."</td>"; }
	
  	  $html .= "  <td>".$arr['estimateID']."</td>
			      <td>".$docNumber."</td>
			      <td>".$arr['BillParty']."</td>
			      <td>N</td>
			      <td>".$arr['CostCenter']."</td>
			     </tr>";
	}
	$html .= " </table>";
	
	echo $html;
    mssql_free_result($result);	
  }
  </script>
</body>
</html>