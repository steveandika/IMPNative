<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  if (!isset($_SESSION["uid"])) {
    $url="/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 
	
  else 
  { 	
    if(isset($_GET['eq']) && isset($_GET['reg'])) {
	  $kodeEstimate="";
	  $keywrd=strtoupper(base64_decode($_GET['eq']));
	  $kodeBooking=base64_decode($_GET['reg']);

    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID, 
	        Format(a.tanggalSurvey, 'yyyy-MM-dd') As surveyDate, a.Surveyor,
			b.principle, b.vessel, b.consignee, d.LabourRateCost, d.repairPriceCode, e.locationDesc, c.Constr,
            Format(estimateDate, 'yyyy-MM-dd') As estimateDate, f.nilaiDPP, f.totalHour, f.totalLabor, f.totalMaterial, f.currencyAs, Format(a.GIPort, 'yyyy-MM-dd') As GIDate 			
            From containerJournal a 
		    Inner Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner Join containerLog c On c.ContainerNo=a.NoContainer 
			Inner Join m_Customer d On d.custRegID=b.principle 
			Left Join m_location e On e.locationID=a.workshopID 
			Left Join RepairHeader f On f.bookID=a.bookInID  And f.containerID=a.NoCOntainer
		    Where a.NoContainer='$keywrd' And a.bookInID='$kodeBooking'";	
	$result=mssql_query($query);

	while($arr=mssql_fetch_array($result)) 
	{
      $sizeCode=$arr[2].'/'.$arr[3].'/'.$arr[4];
	  $size=$arr[2];
	  $tipe=$arr[3];
	  $height=$arr[4];
	  $tglMasuk=$arr['GIDate'];
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
	  $DPP=$arr["nilaiDPP"];

	  $totalMH=$arr["totalHour"];
	  $totalLabor=$arr["totalLabor"];
	  $totalMaterial=$arr["totalMaterial"]; 
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
  <title>I-ConS | Print Preview</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/common.css" />

  <style>
    table { border:0;padding:0;border-collapse:collapse;border-spacing:0;letter-spacing:1px;font-family:sans-serif;font-size:11px;font-weight:500 }
    table tr { border:1px solid #ccc;padding: 5px; }
    table th, table td { padding: 5px;text-transform:uppercase}
	table td { border:1px solid #ccc }
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

<body> 
  <div class="style-container">  
<!--
     <table style="width:910px">
	    <tr>
		  <td style="width:70%;vertical-align:top;border:0;"><img src="../asset/img/pt-imp.png" height="50" width="120"></td>
		  <td style="vertical-align:top;border:0">
		    <h2 style="font-size:.840rem!important"><strong>PT. INDO MAKMUR PRATAMA </strong></h2>			    
			<p>Jl. Kulim No. 35 C Lt. 2</p>
			<p>Pekanbaru - Indonesia</p>
			<p>Phone  : +62 761 22589</p>
			<p style="text-transform:none!important">E-mail : admprw@pt-imp.com</p>
		  </td>	
		</tr>
	    <tr><td colspan="2" style="height:56px;font-size:20px;text-align:center;border:0">ESTIMATE of REPAIR</td></tr>
	  </table>
-->
      <table style="width:900px;">
        <tr> 
          <th colspan="4" style="font-size:.580rem!important">PRINCIPLE</th>		    
          <th colspan="5" style="font-size:.580rem!important">CUSTOMER</th>
		  <th colspan="2" style="font-size:.580rem!important">LOCATION</th>
		  <!--<th colspan="2" style="font-size:.580rem!important">EOR NO.</th>-->
		  <th colspan="4" style="font-size:.580rem!important">SURVEY DATE</th>			
	    </tr>
	    <tr> 
          <td colspan="4" style="text-align:center;font-size:.620rem!important"><?php echo $principle; ?></td>		    
          <td colspan="5" style="text-align:center;font-size:.620rem!important"><?php echo $consignee; ?></td>		    
          <td colspan="2" style="text-align:center;font-size:.620rem!important"><?php echo $lokasi; ?></td>		    
          <!--<td colspan="2" style="text-align:center;font-size:.620rem!important"><?php echo $kodeEstimate; ?></td>		    -->
          <td colspan="4" style="text-align:center;font-size:.620rem!important"><?php echo $tglSurvey; ?></td>			
		</tr>	
		  
        <tr> 
          <th colspan="2" style="font-size:.580rem!important">PREFIX</th>		    
          <th colspan="2" style="font-size:.580rem!important">SERIAL</th>		    
          <th style="font-size:.580rem!important">CD</th>		    
          <th style="font-size:.580rem!important">SIZE</th>		    
          <th style="font-size:.580rem!important">TYPE</th>		    
          <th style="font-size:.580rem!important">HEIGHT</th>		    
          <th style="font-size:.580rem!important">CONST</th>		    
          <th style="font-size:.580rem!important">CURRENCY</th>		    
          <th colspan="3" style="font-size:.580rem!important">VESSEL NAME & VOYAGE</th>		    
          <th colspan="2" style="font-size:.580rem!important">GATE IN</th>			
		</tr>		  
        <tr> 
          <td colspan="2" style="text-align:center;font-size:.620rem!important"><?php echo substr($keywrd,0,4)?></td>		    
          <td colspan="2" style="text-align:center;font-size:.620rem!important"><?php echo substr($keywrd,4,6)?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo substr($keywrd,10,1)?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo $size;?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo $tipe;?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo $height;?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo $constr;?></td>		    
          <td style="text-align:center;font-size:.620rem!important"><?php echo $currency;?></td>		    
          <td colspan="3" style="text-align:center;font-size:.620rem!important"><?php echo $vessel;?></td>		    
          <td colspan="2" style="text-align:center;font-size:.620rem!important"><?php echo $tglMasuk;?></td>			
		</tr>		  
	  </table>	  
	  
	  <table style="border-collapse:collapse;width:900px">
	    <!--index -->
<script language="php">
  $remark_status="";
  $query="Select RemarkStatus
          From   containerPhoto_Remark
   	      Where   BookID='$kodeBooking' And containerID='$keywrd' And statusPhoto = 0";
  $result=mssql_query($query);
  if(mssql_num_rows($result) > 0) {
	$arr=mssql_fetch_array($result);
    $remark_status=$arr['RemarkStatus']; 			 
  }
  mssql_free_result($result);
  echo '<tr><td colspan="3" style="border:0px;border0; height:25px;text-align:center;font-family:Sans-serif"><strong>'.$remark_status.'</strong></td>';		   	   
  
  $query="Select Top(1) * From containerPhoto Where containerID='$keywrd' And bookID='$kodeBooking' And statusPhoto Like 'INDEX%'";
  $result=mssql_query($query);
  while($arr=mssql_fetch_array($result)) {	  
	$photoDir='../mnr/photo/'.$arr["directoryName"];	  
    echo '<tr><td colspan="3" style="border:0px;text-align:center"><img src="'.$photoDir.'" height="200" width="280"></td>';
  }
  mssql_free_result($result);
  
  if(isset($_GET['id']) && ($_GET['id']==1 || $_GET['id']==2)) {	  
    
  
    $remark_status="";
    $query="Select RemarkStatus
            From   containerPhoto_Remark
   	        Where   BookID='$kodeBooking' And containerID='$keywrd' And statusPhoto = 1";
    $result=mssql_query($query);
    if(mssql_num_rows($result) > 0) {
	  $arr=mssql_fetch_array($result);
      $remark_status=": ".$arr['RemarkStatus']; 			 
    }
    mssql_free_result($result);
	echo '<tr><td colspan="3" style="border:1px solid #ccc;text-align:center">BEFORE REPAIR'.$remark_status.'</td>';
    $query="Select * From containerPhoto Where containerID='$keywrd' And bookID='$kodeBooking' And statusPhoto Like 'BEFORE%'";
    $result=mssql_query($query);
    $recCount=mssql_num_rows($result);
    $i=0;
    while($i <$recCount) {
	  echo '<tr>';
	  for($col=1; $col<=3; $col++) {
	    if($i < $recCount) {
	      $photoDir='../mnr/photo/'.mssql_result($result, $i, 'directoryName');	 
          echo '<td style="border:0px;text-align:center""><img src="'.$photoDir.'" height="200" width="280"></td>';
		  $i++;
	    }	
	  }
      echo '</tr>'; 	
    }
    mssql_free_result($result);
  }
  
  if(isset($_GET['id']) && ($_GET['id']==1 || $_GET['id']==3)) {  
    $remark_status="";
    $query="Select RemarkStatus
            From   containerPhoto_Remark
   	        Where   BookID='$kodeBooking' And containerID='$keywrd' And statusPhoto = 2";
    $result=mssql_query($query);
    if(mssql_num_rows($result) > 0) {
	  $arr=mssql_fetch_array($result);
      $remark_status=": ".$arr['RemarkStatus']; 			 
    }
    mssql_free_result($result);
	echo '<tr><td colspan="3" style="border:1px solid #ccc;text-align:center">AFTER REPAIR'.$remark_status.'</td>';  
	
    $query="Select * From containerPhoto Where containerID='$keywrd' And bookID='$kodeBooking' And statusPhoto Like 'AFTER%'";
    $result=mssql_query($query);
    $recCount=mssql_num_rows($result);
    $i=0;
    while($i <$recCount) {
	  echo '<tr>';
	  for($col=1; $col<=3; $col++) {
	    if($i < $recCount) {
	      $photoDir='../mnr/photo/'.mssql_result($result, $i, 'directoryName');	  
          echo '<td style="border:0px;text-align:center""><img src="'.$photoDir.'" height="200" width="280"></td>';
		  $i++;
	    }	
	  }
      echo '</tr>'; 	
    }
    mssql_free_result($result);  
  }	
</script>		
	  </table>
  </div>
</body>
</html> 

<script language="php">
    mssql_close($dbSQL); }
</script>

<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>