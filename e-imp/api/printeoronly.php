<?php
	include($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");	
	$msg = openDB();
	
	if($msg == "connected")
	{
		if(isset($_GET['IDEstimate']) && isset($_GET['NoContainer']) && isset($_GET['Party']))
		{
			$kodeEstimate = trim(str_replace("'"," ",$_GET['IDEstimate']));
			$NoContainer = trim(str_replace("'"," ",$_GET['NoContainer']));
			$Party = trim(str_replace("'"," ",$_GET['Party']));
			
			$PPN = 0;
            
			$qry = "select * from view_EOR_API where estimateID = '$kodeEstimate' and NoContainer = '$NoContainer' "; 
            $stmt = mssql_query($qry);			
			if(mssql_num_rows($stmt) > 0) 
			{
				while($arr = mssql_fetch_array($stmt)) 
				{
					$sizeCode = $arr[2].'/'.$arr[3].'/'.$arr[4];
					$size = $arr[2];
					$tipe = $arr[3];
					$height = $arr[4];
					$tglMasuk = $arr['DateIn'];
					$tglSurvey = $arr['surveyDate'];
					$surveyor = $arr[8];
					$principle = $arr[9];
					$vessel = $arr[10];
					$consignee = $arr[11]; 
					$labour = $arr[12];
					$priceCode = $arr[13]; 
					$lokasi = $arr[6].' - '.$arr[14];
					$constr = $arr['Constr']; 
					$tglEstimate = $arr['estimateDate'];
					$currency = $arr['currencyAs']; 
					$PortIn = $arr['GIDate'];	
					$PPN = $arr['PPN'];
				}	  
				mssql_free_result($stmt);
	
				$have_principle = haveCustomerName($principle);
				$have_consignee = haveCustomerName($consignee);
				$principle = $have_principle;
				$consignee = $have_consignee;				
            }
            
            $msg = "view";			
        }			
		else
		{
		  $msg = "cancel";
		  
		  $url = "http://pt-imp.com"; 
		  echo "<script type='text/javascript'>location.replace('$url');</script>"; 			
		}
	}		
	else
	{
		$msg = "cancel";
		  
		$url = "http://pt-imp.com"; 
		echo "<script type='text/javascript'>location.replace('$url');</script>"; 			
	}	

	if($msg == "view")
	{	
?>

<!DOCTYPE html>
<html style="font-family:Arial!Important;font-size:13px!important;">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="SA" />
  <title>I-ConS | EOR</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />

  <style>
    table { border:0;padding:0;border-collapse:collapse;border-spacing:0;letter-spacing:1px; }
    table tr { border: 0;padding: 5px; }
    table th, table td { padding: 5px;text-transform:uppercase}
	table td { border:1px solid #ccc; }
    table th { border:1px solid #ccc; }  
	p { line-height:4px }
	style-container{padding:0.01em 5px}
	h6 { color:#000000!important; }
  </style>  
  <style type="text/css" media="print">
    @page 
    {
      size: auto;   /* auto is the current printer page size */
      margin: 2mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
      margin: 0px;  /* the margin on the content before printing */
    }
  </style>  
</head>

<body> 
  <div class="w3-container">  
      <table style="width:1100px">
	    <tr>
		  <td style="width:70%;vertical-align:top;border:0;"><img src="../asset/img/pt-imp.png" height="50" width="120"></td>
		  <td style="vertical-align:top;border:0">
		    <h5 style="color:#000000!important"><b>PT. INDO MAKMUR PRATAMA </b></h5>			    
			<p>Jl. Kulim No. 35 C Lt. 2</p>
			<p>Pekanbaru - Indonesia</p>
			<p>Phone  : +62 761 22589</p>
			<p style="text-transform:none!important">E-mail : admprw@pt-imp.com</p>
		  </td>	
		</tr>
	    <tr><td colspan="2" style="text-align:center;border:0"><h5 style="color:#000000"><b>ESTIMATE of REPAIR</b></h5></td></tr>
	  </table>
	  	  
      <table style="width:1100px">
        <tr> 
          <th colspan="4">PRINCIPLE</th>		    
          <th colspan="5">CUSTOMER</th>
		  <th colspan="2">WORKSHOP</th>
		  <th colspan="2">EOR NO.</th>
		  <th colspan="2">SURVEY DATE</th>			
	    </tr>
	    <tr> 
          <td colspan="4"><?php echo $principle; ?></td>		    
          <td colspan="5"><?php echo $consignee; ?></td>		    
          <td colspan="2"><?php echo $lokasi; ?></td>		    
          <td colspan="2"><?php echo $kodeEstimate; ?></td>		    
          <td colspan="2"><?php echo $tglSurvey;?></td>			
		</tr>	
		  
        <tr> 
          <th colspan="2">PREFIX</th>		    
          <th colspan="2">SERIAL</th>		    
          <th>CD</th>		    
          <th>SIZE</th>		    
          <th>TYPE</th>		    
          <th>HEIGHT</th>		    
          <th>CONST</th>		    
          <th>CURRENCY</th>		    
          <th colspan="3" >VESSEL NAME & VOYAGE</th>		    
          <th>PORT IN</th>			
		  <th>GATE IN</th>			
		</tr>		  
        <tr> 
          <td colspan="2" ><?php echo substr($NoContainer,0,4)?></td>		    
          <td colspan="2" ><?php echo substr($NoContainer,4,6)?></td>		    
          <td><?php echo substr($NoContainer,10,1)?></td>		    
          <td><?php echo $size;?></td>		    
          <td><?php echo $tipe;?></td>		    
          <td><?php echo $height;?></td>		    
          <td><?php echo $constr;?></td>		    
          <td><?php echo $currency;?></td>		    
          <td colspan="3"><?php echo $vessel;?></td>		    
          <td><?php echo $PortIn;?></td>					  
          <td><?php echo $tglMasuk;?></td>			
		</tr>		  
		  
        <tr> 
          <td colspan="15" style="border-width:0"></td>
		</tr>		
		  
        <tr> 
          <th>ITEM NO.</th>		    
          <th>LOCATION</th>		    
          <th>DAMAGE</th>		    
          <th>REPAIR</th>		    
          <th>LENGTH (cm)</th>		    
          <th>WIDTH (cm)</th>		    
          <th>QUANTITY</th>		   
          <th>PARTY</th>		    
          <th colspan="3">DESCRIPTION</th>		    
          <th>M/H</th>	            
          <th>LABOUR</th>	            
          <th>MATERIAL</th>							
          <th>TOTAL</th>				
		</tr>

<?php
	$i = 1;
	$total_user = 0;
	$total_owner = 0;
	$total_third = 0;
	$DPP = 0;
	$totalMH = 0;
	$totalLabor = 0;
	$totalMaterial = 0; 

	if($Party == 'O') { $partyInt = 0; }
	if($Party == 'U1') { $partyInt = 1; }
	if($Party == 'T') { $partyInt = 2; }
	if($Party == 'U2') { $partyInt = 3; }

	$qry = "select * from repairDetail where estimateID='$kodeEstimate' and isOwner=$partyInt Order By idItem"; 
	$stmt = mssql_query($qry);

  
	while($arr = mssql_fetch_array($stmt)) 
	{
		if($Party=='O') { $total_owner=$total_owner +$arr["totalValue"]; }
		if($Party=='U1' || $Party=='U2') { $total_user=$total_user +$arr["totalValue"]; }
		if($Party=='T') { $total_third=$total_third +$arr["totalValue"]; }
	
		$hm = 0;
		$labor = 0;
		$mtrl = 0;
		$subtotal = 0;
		
		if($arr['totalValue'] != 0) 
		{
			$hm = $arr["hoursValue"];
			$labor = $arr["laborValue"];
			$mtrl = $arr["materialValue"];
			$subtotal = $arr["totalValue"];
		}	
	
		$DPP = $DPP +$subtotal;
		$totalMH = $totalMH +$hm;
		$totalLabor = $totalLabor +$labor;
		$totalMaterial = $totalMaterial +$mtrl;
	
		echo '<tr>
				<td>'.$i.'</td>
				<td style="text-align:center">'.$arr["locationID"].'</td>
				<td style="text-align:center">'.$arr["damageID"].'</td>
				<td style="text-align:center">'.$arr["repairID"].'</td>
				<td style="text-align:right">'.number_format($arr["lengthValue"],2,",",".").'</td>
				<td style="text-align:right">'.number_format($arr["widthValue"],2,",",".").'</td>
				<td style="text-align:right">'.number_format($arr["Quantity"],2,",",".").'</td>
				<td style="text-align:center">'.$Party.'</td>
				<td colspan="3">'.$arr["Remarks"].'</td>
				<td style="text-align:right">'.number_format($hm,2,",",".").'</td>
				<td style="text-align:right">'.number_format($labor,2,",",".").'</td>
				<td style="text-align:right">'.number_format($mtrl,2,",",".").'</td>
				<td style="text-align:right">'.number_format($subtotal,2,",",".").'</td>
			</tr>';
		
		$i++;	
	}
	mssql_free_result($stmt);
  
	for($j=$i; $j<=22; $j++)
	{
		echo '<tr>
				<td style="border:0;border-left:1px solid #ccc">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0" colspan="3">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0">&nbsp;</td>
				<td style="border:0;border-right:1px solid #ccc">&nbsp;</td>
			</tr>';	  
	}	
	
?>
		
        <tr> 
			<td colspan="11" style="border:0;border-top:1px solid #ccc;text-align:right;"><h6><b>SUB TOTAL<b></h6></td>		    
			<td style="text-align:righ"><?php echo number_format($totalMH,2,",",".");?></td>	            
			<td style="text-align:right"><?php echo number_format($totalLabor,2,",",".");?></td>	            
			<td style="text-align:right"><?php echo number_format($totalMaterial,2,",",".");?></td>							
			<td style="text-align:right"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>				  
        <tr> 
			<td colspan="15" style="border:0">&nbsp;</td>
		</tr>			  		  
        <tr> 
			<td colspan="11" style="border:0">&nbsp;</td>		    
			<td colspan="3" style="text-align:right;"><h6><b>TOTAL OWNER CHARGES (O)</b></h6></td>				
			<td style="text-align:right;"><?php echo number_format($total_owner,2,",",".");?></td>				
		</tr>				 
        <tr> 
			<td colspan="11" style="border:0;">&nbsp;</td>		    
			<td colspan="3" style="text-align:right"><h6><b>TOTAL USER CHARGES (U)</b><h6></td>				
			<td style="text-align:right"><?php echo number_format($total_user,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="11" style="border:0;">&nbsp;</td>		   
          <td colspan="3" style="text-align:right"><h6><b>TOTAL 3RD PARTY CHARGES (T)</b></h6></td>				
          <td style="text-align:right;"><?php echo number_format($total_third,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><h6><b>TOTAL</b></h6></td>				
          <td style="text-align:right;"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>			  		 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><h6><b>VAT</b>&nbsp;<?php echo $VAT*100 ?>%</h6></td>				
          <td style="text-align:right;"><?php echo number_format($DPP*$PPN,2,",",".");?></td>				
		</tr>		  
		  
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><h6><b>GRAND TOTAL</b></h6></td>				
          <td style="text-align:right;"><?php $grand = $DPP +($DPP*$PPN); echo number_format($grand,2,",",".");?></td>				
		</tr>		  
		</tbody>		
	  </table>
	  
      <div class="height-10"></div>
      <table style="width:940px">
        <tr>
	      <td style="width:50%;vertical-align:top;text-align:center;border:0;">On Behalf PT.INDO MAKMUR PRATAMA,</td>
		  <td style="vertical-align:top;text-align:center;border:0;">Customer Approval,</td>	
		</tr>
        <tr>
	      <td style="width:50%;vertical-align:top;text-align:center;border:0;"><?php echo $tglEstimate?></td>
		  <td style="vertical-align:top;text-align:center;border:0;"></td>	
		</tr>			
	  </table>	  
	  
	  <div class="height-10"></div>
  </div>
</body>
</html> 

<?php
	} 
?>

<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>