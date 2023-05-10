<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  if (!isset($_SESSION["uid"])) {
    $url="/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 
	
  else { 	
    if(isset($_GET['estimate']) && isset($_GET['unit']) && isset($_GET['book'])) {
	  $kodeEstimate=$_GET['estimate'];
	  $keywrd=$_GET['unit'];
	  $kodeBooking=$_GET['book'];

    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, b.locationID, 
	        Format(a.tanggalSurvey, 'yyyy-MM-dd') As surveyDate, a.Surveyor,
			b.principle, b.vessel, b.consignee, d.LabourRateCost, d.repairPriceCode, e.locationDesc, c.Constr,
            Format(estimateDate, 'yyyy-MM-dd') As estimateDate, nilaiDPP, totalHour, totalLabor, totalMaterial 			
            From containerJournal a 
		    Inner Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner Join containerLog c On c.ContainerNo=a.NoContainer 
			Inner Join m_Customer d On d.custRegID=b.principle 
			Inner Join m_location e On e.locationID=b.locationID 
			Inner Join RepairHeader f On f.bookID=a.bookInID And f.estimateID='$kodeEstimate' 
		    Where (a.NoContainer='$keywrd') And (a.bookInID='$kodeBooking') ";		
	$result=mssql_query($query);
	while($arr=mssql_fetch_array($result)) {
      $sizeCode=$arr[2].'/'.$arr[3].'/'.$arr[4];
	  $size=$arr[2];
	  $tipe=$arr[3];
	  $height=$arr[4];
	  $tglMasuk=$arr[5];
	  $tglSurvey=$arr[7];
	  $surveyor=$arr[8];
	  $principle=$arr[9];
	  $vessel=$arr[10];
	  $consignee=$arr[11]; 
	  $labour=$arr[12];
	  $priceCode=$arr[13]; 
	  $lokasi=$arr[6].' - '.$arr[14];
	  $constr=$arr["Constr"]; 
	  $tglEstimate=$arr["estimateDate"];
	  $currency=$arr["currencyAs"]; 
	  $DPP=$arr["nilaiDPP"];
	  $totalMH=$arr["totalHour"];
	  $totalLabor=$arr["totalLabor"];
	  $totalMaterial=$arr["totalMaterial"]; }	  
	mssql_free_result($result);
	
	$have_principle=haveCustomerName($principle);
	$have_consignee=haveCustomerName($consignee);
	$principle=$have_principle;
	$consignee=$have_consignee;
  }
</script>  

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/normalize.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/googlenexus.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/component.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/w3.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/common.css" />
  <style>
    table { border:0;width:90%;margin:0 auto;padding:0;border-collapse:collapse;border-spacing:0;letter-spacing:1px; }
    table tr { border: 0;padding: 5px; }
    table th, table td { padding: 10px;text-transform:uppercase}
	table td { font-size:13px; border:1px solid #ccc; }
    table th { font-size:13px;border:1px solid #ccc; }
	p { line-height:4px }
</style>  
</head>

<body> 
  <div class="container">  
   <header>   
    <div class="w3-container">      
      <table>
	    <tr>
		  <td style="width:60%;vertical-align:top;border:0;"><img src="../asset/img/pt-imp.png" height="70" width="140"></td>
		  <td style="vertical-align:top;border:0">
		    <h2><strong>PT. INDO MAKMUR PRATAMA </strong></h2>			    
			<p>Jl. Kulim No. 35 C Lt. 2</p>
			<p>Pekanbaru - Indonesia</p>
			<p>Phone  : +62 761 22589</p>
			<p>E-mail : admprwi@pt-imp.com</p>
		  </td>	
		</tr>
	    <tr><td colspan="2" style="height:56px;font-size:40px;text-align:center;border:0">ESTIMATE of REPAIR</td></tr>
	  </table>
	  	  
      <table>
        <tr> 
          <th colspan="4">PRINCIPLE</th>		    
          <th colspan="5">CUSTOMER</th>
		  <th colspan="2">LOCATION</th>
		  <th colspan="2">EOR NO.</th>
		  <th colspan="2">EOR DATE</th>			
	    </tr>
	    <tr> 
          <td colspan="4" style="text-align:center"><?php echo $principle; ?></td>		    
          <td colspan="5" style="text-align:center"><?php echo $consignee; ?></td>		    
          <td colspan="2" style="text-align:center"><?php echo $lokasi; ?></td>		    
          <td colspan="2" style="text-align:center"><?php echo $kodeEstimate; ?></td>		    
          <td colspan="2" style="text-align:center"><?php echo $tglEstimate; ?></td>			
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
          <th colspan="3">VESSEL NAME & VOYAGE</th>		    
          <th colspan="2">DATE IN</th>			
		</tr>		  
        <tr> 
          <td colspan="2" style="text-align:center"><?php echo substr($keywrd,0,4)?></td>		    
          <td colspan="2" style="text-align:center"><?php echo substr($keywrd,4,6)?></td>		    
          <td style="text-align:center"><?php echo substr($keywrd,10,1)?></td>		    
          <td style="text-align:center"><?php echo $size;?></td>		    
          <td style="text-align:center"><?php echo $tipe;?></td>		    
          <td style="text-align:center"><?php echo $height;?></td>		    
          <td style="text-align:center"><?php echo $constr;?></td>		    
          <td style="text-align:center"><?php echo $currency;?></td>		    
          <td colspan="3" style="text-align:center"><?php echo $vessel;?></td>		    
          <td colspan="2" style="text-align:center"><?php echo $tglMasuk;?></td>			
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

<script language="php">
  $i=1;
  $total_user=0;
  $total_owner=0;
  $total_third=0;
  $query="Select * From RepairDetail Where estimateID='$kodeEstimate'";
  $result=mssql_query($query);
  
  while($arr=mssql_fetch_array($result)) {
    if($arr["isOwner"]==0) { $Party='O'; }
	if($arr["isOwner"]==1) { $Party='U'; }
	if($arr["isOwner"]==2) { $Party='T'; }
	
	if($Party=='O') { $total_owner=$total_owner +$arr["totalValue"]; }
	if($Party=='U') { $total_user=$total_user +$arr["totalValue"]; }
	if($Party=='T') { $total_third=$total_third +$arr["totalValue"]; }

	echo '<tr>
	       <td style="text-align:right">'.$i.'</td>
		   <td style="text-align:left">'.$arr["locationID"].'</td>
		   <td style="text-align:left">'.$arr["damageID"].'</td>
		   <td style="text-align:left">'.$arr["repairID"].'</td>
		   <td style="text-align:right">'.number_format($arr["lengthValue"],2,",",".").'</td>
		   <td style="text-align:right">'.number_format($arr["widthValue"],2,",",".").'</td>
		   <td style="text-align:right">'.number_format($arr["Quantity"],2,",",".").'</td>
		   <td style="text-align:center">'.$Party.'</td>
           <td style="text-align:left" colspan="3">'.$arr["Remarks"].'</td>
		   <td style="text-align:right">'.number_format($arr["hoursValue"],2,",",".").'</td>
		   <td style="text-align:right">'.number_format($arr["laborValue"],2,",",".").'</td>
		   <td style="text-align:right">'.number_format($arr["materialValue"],2,",",".").'</td>
		   <td style="text-align:right">'.number_format($arr["totalValue"],2,",",".").'</td>
	     </tr>';
    $i++;	
  }
  mssql_free_result($result);
</script>	
		
        <tr> 
          <td colspan="11" style="border-width:0;text-align:right"><b>SUB TOTAL<b></td>		    
          <td style="text-align:right"><?php echo number_format($totalMH,2,",",".");?></td>	            
          <td style="text-align:right"><?php echo number_format($totalLabor,2,",",".");?></td>	            
          <td style="text-align:right"><?php echo number_format($totalMaterial,2,",",".");?></td>							
          <td style="text-align:right"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>				  
        <tr> 
          <td colspan="15" style="border:0">&nbsp;</td>
		</tr>			  		  
        <tr> 
          <td colspan="11" style="border:0">&nbsp;</td>		    
          <td colspan="3" style="text-align:right"><b>TOTAL OWNER CHARGES (O)</b></td>				
          <td style="text-align:right"><?php echo number_format($total_owner,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="11" style="border:0;">&nbsp;</td>		    
          <td colspan="3" style="text-align:right"><b>TOTAL USER CHARGES (U)</b></td>				
          <td style="text-align:right"><?php echo number_format($total_user,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="11" style="border:0;">&nbsp;</td>		   
          <td colspan="3" style="text-align:right"><b>TOTAL 3RD PARTY CHARGES (T)</b></td>				
          <td style="text-align:right"><?php echo number_format($total_third,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right"><b>TOTAL</b></td>				
          <td style="text-align:right"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>			  		 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right"><b>TAX</b></td>				
          <td style="text-align:right"><?php echo number_format($DPP*0.1,2,",",".");?></td>				
		</tr>		  
		  
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right"><b>GRAND TOTAL</b></td>				
          <td style="text-align:right"><?php echo number_format($DPP +($DPP*0.1),2,",",".");?></td>				
		</tr>		  
		</tbody>		
	  </table>
      <div class="height-20"></div>
      <table>
        <tr>
	      <td style="width:50%;vertical-align:top;text-align:center;border:0;">On Behalf PT.INDO MAKMUR PRATAMA,</td>
		  <td style="vertical-align:top;text-align:center;border:0;">Customer Approval,</td>	
		</tr>
	  </table>	  
	  <div class="height-40"></div>
	  <div class="height-40"></div>
	  <div class="height-40"></div>
	  
	  <table style="border-collapse:collapse">
	    <!--index -->
<script language="php">
  $query="Select * From containerPhoto Where estimateID='$kodeEstimate' And statusPhoto Like 'INDEX%'";
  $result=mssql_query($query);
  while($arr=mssql_fetch_array($result)) {
	$photoDir='../mnr/photo/'.$arr["directoryName"];	  
    echo '<tr><td colspan="2" style="border:0px;border-bottom:1px solid #ccc;text-align:center"><img src='.$photoDir.' height="200" width="280"></td>';
  }
  mssql_free_result($result);
  
  echo '<tr><td colspan="2" style="border:0px;border-bottom:1px solid #ccc;text-align:center">BEFORE REPAIR</td>';
  
  $query="Select * From containerPhoto Where estimateID='$kodeEstimate' And statusPhoto Like 'BEFORE%'";
  $result=mssql_query($query);
  $recCount=mssql_num_rows($result);
  $i=0;
  while($i <$recCount) {
	echo '<tr>';
	for($col=1; $col<=2; $col++) {
	  if($i < $recCount) {
	    $photoDir='../mnr/photo/'.mssql_result($result, $i, 'directoryName');	  
        echo '<td style="border:0px; border-bottom:1px solid #ccc;text-align:center""><img src='.$photoDir.' height="200" width="280"></td>';
		$i++;
	  }	
	}
    echo '</tr>'; 	
  }
  mssql_free_result($result);
  
  echo '<tr><td colspan="2" style="border:0px;border-bottom:1px solid #ccc;text-align:center">AFTER REPAIR</td>';
  
  $query="Select * From containerPhoto Where estimateID='$kodeEstimate' And statusPhoto Like 'AFTER%'";
  $result=mssql_query($query);
  $recCount=mssql_num_rows($result);
  $i=0;
  while($i <$recCount) {
	echo '<tr>';
	for($col=1; $col<=2; $col++) {
	  if($i < $recCount) {
	    $photoDir='../mnr/photo/'.mssql_result($result, $i, 'directoryName');	  
        echo '<td style="border:0px; border-bottom:1px solid #ccc;text-align:center""><img src='.$photoDir.' height="200" width="280"></td>';
		$i++;
	  }	
	}
    echo '</tr>'; 	
  }
  mssql_free_result($result);  
</script>		
	  </table>
	</div>
	
   </header>   
  </div>
</body>
</html> 

<script language="php">
    mssql_close($dbSQL); }
</script>