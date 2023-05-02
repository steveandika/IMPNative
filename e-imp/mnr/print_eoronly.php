<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  if (!isset($_SESSION["uid"])) {
    $url="/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 
	
  else 
  { 	

    if(isset($_GET['id']) && isset($_GET['cnt']) && isset($_GET['bookid'])) {
	  $kodeEstimate=$_GET['id'];
	  $keywrd=$_GET['cnt'];
	  $kodeBooking=$_GET['bookid'];
	  
	  if(isset($_GET['party'])) { $haveParty = $_GET['party']; }
	  else { $haveParty = -1; }
	  
	  $query = "select * from view_EOR_API where estimateID = '$kodeEstimate' and NoContainer = '$keywrd' ";

/*
    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID, 
	        Format(a.tanggalSurvey, 'yyyy-MM-dd') As surveyDate, a.Surveyor,
			b.principle, CONCAT(b.vessel,' ',b.voyageID) AS vessel, b.consignee, d.LabourRateCost, d.repairPriceCode, e.locationDesc, c.Constr,
            Format(estimateDate, 'yyyy-MM-dd') As estimateDate, f.nilaiDPP, f.totalHour, f.totalLabor, f.totalMaterial, f.currencyAs, Format(a.GIPort, 'yyyy-MM-dd') As GIDate 			
            From containerJournal a 
		    Inner Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner Join containerLog c On c.ContainerNo=a.NoContainer 
			Inner Join m_Customer d On d.custRegID=b.principle 
			Left Join m_location e On e.locationID=a.workshopID 
			Inner Join RepairHeader f On f.bookID=a.bookInID  And f.containerID=a.NoContainer
		    Where a.NoContainer='$keywrd' And a.bookInID='$kodeBooking'";		*/
	$result=mssql_query($query);

	while($arr=mssql_fetch_array($result)) 
	{
      $sizeCode=$arr[2].'/'.$arr[3].'/'.$arr[4];
	  $size=$arr[2];
	  $tipe=$arr[3];
	  $height=$arr[4];
	  $tglMasuk=$arr['DateIn'];
	  $tglSurvey=$arr['surveyDate'];
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
	  $PortIn = $arr['GIDate'];	  
	  $PPN = $arr['PPN'];  
	}	  
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

<body style="font-family:Arial!Important;font-size:13px!important;"> 
  <div class="w3-container">  
      <table style="width:1100px">
	    <tr>
		  <td style="width:70%;vertical-align:top;border:0;"><img src="../asset/img/pt-imp.png" height="50" width="120"></td>
		  <td style="vertical-align:top;border:0">
		    <h5 style="color:#000000!important"><strong>PT. INDO MAKMUR PRATAMA </strong></h5>			    
			<p>Jl. Kulim No. 35 C Lt. 2</p>
			<p>Pekanbaru - Indonesia</p>
			<p>Phone  : +62 761 22589</p>
			<p style="text-transform:none!important">E-mail : admprw@pt-imp.com</p>
		  </td>	
		</tr>
	    <tr><td colspan="2" style="height:56px;text-align:center;border:0">ESTIMATE of REPAIR</td></tr>
	  </table>
	  	  
      <table style="width:1100px">
        <tr> 
          <th colspan="4">PRINCIPLE</th>		    
          <th colspan="5">CUSTOMER</th>
		  <th colspan="2">LOCATION</th>
		  <th colspan="2">EOR NO.</th>
		  <th colspan="2">SURVEY DATE</th>			
	    </tr>
	    <tr> 
          <td colspan="4" style="text-align:center;"><?php echo $principle; ?></td>		    
          <td colspan="5" style="text-align:center;"><?php echo $consignee; ?></td>		    
          <td colspan="2" style="text-align:center;"><?php echo $lokasi; ?></td>		    
          <td colspan="2" style="text-align:center;"><?php echo $kodeEstimate; ?></td>		    
          <td colspan="2" style="text-align:center;"><?php echo $tglSurvey;?></td>			
		</tr>	
		  
        <tr> 
          <th colspan="2" >PREFIX</th>		    
          <th colspan="2" >SERIAL</th>		    
          <th >CD</th>		    
          <th >SIZE</th>		    
          <th >TYPE</th>		    
          <th >HEIGHT</th>		    
          <th >CONST</th>		    
          <th >CURRENCY</th>		    
          <th colspan="3" >VESSEL NAME & VOYAGE</th>		    
          <th >PORT IN</th>			
		  <th >GATE IN</th>			
		</tr>		  
        <tr> 
          <td colspan="2" style="text-align:center;"><?php echo substr($keywrd,0,4)?></td>		    
          <td colspan="2" style="text-align:center;"><?php echo substr($keywrd,4,6)?></td>		    
          <td style="text-align:center;"><?php echo substr($keywrd,10,1)?></td>		    
          <td style="text-align:center;"><?php echo $size;?></td>		    
          <td style="text-align:center;"><?php echo $tipe;?></td>		    
          <td style="text-align:center;"><?php echo $height;?></td>		    
          <td style="text-align:center;"><?php echo $constr;?></td>		    
          <td style="text-align:center;"><?php echo $currency;?></td>		    
          <td colspan="3" style="text-align:center;"><?php echo $vessel;?></td>		    
          <td style="text-align:center;"><?php echo $PortIn;?></td>					  
          <td style="text-align:center;"><?php echo $tglMasuk;?></td>			
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
  if($haveParty == -1) { $query="Select * From repairDetail Where estimateID='$kodeEstimate' Order By idItem"; }
  else { $query="Select * From repairDetail Where estimateID='$kodeEstimate' And isOwner=$haveParty Order By idItem"; }
  $result=mssql_query($query);

  $DPP=0;

  $totalMH=0;
  $totalLabor=0;
  $totalMaterial=0; 
  
  while($arr=mssql_fetch_array($result)) {
  //  if($arr["isOwner"]==0) { $Party='O'; }
//	if($arr["isOwner"]==1) { $Party='U'; }
//	if($arr["isOwner"]==2) { $Party='T'; }
    if($arr["isOwner"]==0) { $Party='O'; }
	if($arr["isOwner"]==1) { $Party='U1'; }
	if($arr["isOwner"]==2) { $Party='T'; }
	if($arr["isOwner"]==3) { $Party='U2'; }
	
	if($Party=='O') { $total_owner=$total_owner +$arr["totalValue"]; }
	if($Party=='U1' || $Party=='U2') { $total_user=$total_user +$arr["totalValue"]; }
	if($Party=='T') { $total_third=$total_third +$arr["totalValue"]; }
	
	$hm=0;
	$labor=0;
	$mtrl=0;
	$subtotal=0;
	if($arr['totalValue']!=0) {
	   $hm=$arr["hoursValue"];
       $labor=$arr["laborValue"];
       $mtrl=$arr["materialValue"];
	   $subtotal=$arr["totalValue"];
	}	
	/*
	$DPP = $DPP +$arr["totalValue"];
	$totalMH = $totalMH +$arr["hoursValue"];
	$totalLabor = $totalLabor +$arr["laborValue"];
	$totalMaterial = $totalMaterial +$arr["materialValue"];
    */
	$DPP = $DPP +$subtotal;
	$totalMH = $totalMH +$hm;
	$totalLabor = $totalLabor +$labor;
	$totalMaterial = $totalMaterial +$mtrl;
	
	echo '<tr>
	       <td style="text-align:right;">'.$i.'</td>
		   <td style="text-align:left;">'.$arr["locationID"].'</td>
		   <td style="text-align:left;">'.$arr["damageID"].'</td>
		   <td style="text-align:left;">'.$arr["repairID"].'</td>
		   <td style="text-align:right;">'.number_format($arr["lengthValue"],2,",",".").'</td>
		   <td style="text-align:right;">'.number_format($arr["widthValue"],2,",",".").'</td>
		   <td style="text-align:right;">'.number_format($arr["Quantity"],2,",",".").'</td>
		   <td style="text-align:center;">'.$Party.'</td>
           <td style="text-align:left;" colspan="3">'.$arr["Remarks"].'</td>
		   <td style="text-align:right;">'.number_format($hm,2,",",".").'</td>
		   <td style="text-align:right;">'.number_format($labor,2,",",".").'</td>
		   <td style="text-align:right;">'.number_format($mtrl,2,",",".").'</td>
		   <td style="text-align:right;">'.number_format($subtotal,2,",",".").'</td>
	     </tr>';
    $i++;	
  }
  mssql_free_result($result);
  
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
</script>	
		
        <tr> 
          <td colspan="11" style="border:0;border-top:1px solid #ccc;text-align:right;"><b>SUB TOTAL<b></td>		    
          <td style="text-align:right;"><?php echo number_format($totalMH,2,",",".");?></td>	            
          <td style="text-align:right;"><?php echo number_format($totalLabor,2,",",".");?></td>	            
          <td style="text-align:right;"><?php echo number_format($totalMaterial,2,",",".");?></td>							
          <td style="text-align:right;"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>				  
        <tr> 
          <td colspan="15" style="border:0">&nbsp;</td>
		</tr>			  		  
        <tr> 
          <td colspan="11" style="border:0">&nbsp;</td>		    
          <td colspan="3" style="text-align:right;"><b>TOTAL OWNER CHARGES (O)</b></td>				
          <td style="text-align:right;"><?php echo number_format($total_owner,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="11" style="border:0;">&nbsp;</td>		    
          <td colspan="3" style="text-align:right;"><b>TOTAL USER CHARGES (U)</b></td>				
          <td style="text-align:right;"><?php echo number_format($total_user,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="11" style="border:0;">&nbsp;</td>		   
          <td colspan="3" style="text-align:right;"><b>TOTAL 3RD PARTY CHARGES (T)</b></td>				
          <td style="text-align:right;"><?php echo number_format($total_third,2,",",".");?></td>				
		</tr>				 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><b>TOTAL</b></td>				
          <td style="text-align:right;"><?php echo number_format($DPP,2,",",".");?></td>				
		</tr>			  		 
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><b>VAT</b>&nbsp;<?php echo $PPN*100 ?>%</td>				
          <td style="text-align:right;"><?php echo number_format($DPP*$PPN,2,",",".");?></td>				
		</tr>		  
		  
        <tr> 
          <td colspan="13" style="border:0;">&nbsp;</td>		    
          <td style="text-align:right;"><b>GRAND TOTAL</b></td>				
          <td style="text-align:right;"><?php $grand = $DPP +($DPP*$PPN); echo number_format($grand,2,",",".");?></td>				
		</tr>		  
		</tbody>		
	  </table>
      <div class="height-20"></div>
      <table style="width:1100px">
        <tr>
	      <td style="width:50%;vertical-align:top;text-align:center;border:0;">On Behalf PT.INDO MAKMUR PRATAMA,</td>
		  <td style="vertical-align:top;text-align:center;border:0;">Customer Approval,</td>	
		</tr>
        <tr>
	      <td style="width:50%;vertical-align:top;text-align:center;border:0;"><?php echo $tglEstimate?></td>
		  <td style="vertical-align:top;text-align:center;border:0;"></td>	
		</tr>			
	  </table>	  
	  <div class="height-40"></div>
  </div>
</body>
</html> 

<script language="php">
    mssql_close($dbSQL); }
</script>

<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>