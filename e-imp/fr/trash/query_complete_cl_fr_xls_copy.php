<script language="php">
  session_start();  
  if (!isset($_SESSION["uid"])) {
    $URL="/"; 
	echo "<script type='text/javascript'>location.replace('$URL');</script>"; } 	
  else 
  {   
    include("../asset/libs/db.php"); 
    include("../asset/libs/common.php");
    $namaFile="Summary_CR_Cleaning_".$_POST['instart']."_".$_POST['inlast']."_LOC_".$_POST['loc'];
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=".$namaFile.".xls");    
	
    $index=0;
    $totalDPP=0;
    $totalMH=0;
    $totalLabor=0;
    $totalMtrl=0;
    $totalTax=0;
    $grandTotal=0;
    $tgl1=$_POST['instart'];
    $tgl2=$_POST['inlast'];
    $loc=$_POST['loc'];
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <meta name="author" content="Edmund" />
  <title>I-ConS | Export Report</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  

  <style>
    table { border:0;padding:0;border-collapse:collapse;border-spacing:0;letter-spacing:1px; }
    table tr { border: 0;padding: 5px; }
    table th, table td { padding: 5px;text-transform:uppercase}
	table td { border:1px solid #ccc; }
    table th { border:1px solid #ccc; }
	p { line-height:4px }
	style-container{padding:0.01em 5px}
    h3{font-size:24px;font-family:"Open Sans",Roboto,sans-serif;color:#000;}
   .w3-text-grey{font-family:"Open Sans",Roboto,sans-serif;font-size:13px;color:#757575!important}
</style>
</head>

<body> 
<div class="height-10"></div>
<h3 style="padding:0 0 5px 0;border-bottom:1px solid #839192">&nbsp;&nbsp;Summary C/R and C/Cleaning</h3> 			
<?php if($_POST['do']=="REPAIR") {
	    echo '<label class="w3-text-grey">Range AV Repair From:'.$tgl1.'&nbsp; Until:'.$tgl2.'</label><br>';
      }
	  else {
		echo '<label class="w3-text-grey">Range Complete Cleaning From:'.$tgl1.'&nbsp; Until:'.$tgl2.'</label><br>';  
	  } ?>
<label class="w3-text-grey">Workshop Location:<?php echo $loc?></label><br>
<div class="height-10"></div>	
 <table>
  <thead>
    <tr> 
      <th rowspan="2">Index</th>
      <th rowspan="2">Container No.</th>
      <th rowspan="2">CD</th>
      <th rowspan="2">Principle</th>
	  <th rowspan="2">Hamp. In</th>
      <th colspan="3">Estimate</th>      
<!--      <th rowspan="2">Submitted</th>-->
	  <th rowspan="2">Type</th>
	  <th rowspan="2"><?php if($_POST['do']=="REPAIR") { echo "C/R"; } else { echo "C/C"; }?></th>
      <th colspan="6" style="text-align:center;border-right:1px solid #ccc;border-left:1px solid #ccc;" >Original</th>
<!--      <th rowspan="2">Approved On</th>-->      
    </tr>
    <tr> 
	  <th style="background: #fff;border-left:1px solid #ccc;text-align:center">Number</th>
	  <th style="background: #fff;border-left:1px solid #ccc;text-align:center">Submitted</th>
	  <th style="background: #fff;border-left:1px solid #ccc;text-align:center">Approved</th>
      <th style="background: #fff;border-left:1px solid #ccc;text-align:right">M/H</th>
      <th style="background: #fff;text-align:right">Labor</th>
      <th style="background: #fff;text-align:right">Mtrl</th>
      <th style="background: #fff;text-align:right">Sub Total</th>
      <th style="background: #fff;text-align:right">Tax</th>
      <th style="background: #fff;border-right:1px solid #ccc;text-align:right">Total</th>
    </tr>
  </thead><tbody>
   

<script language="php">  
  /* Finished Repair */

  if($_POST['do']=="REPAIR") {  
    $query="Select a.estimateID, a.containerID, b.Size, b.Type, b.Height, b.Constr, Format(c.gateIn,'yyyy-MM-dd') As TanggalIn, 
                   a.totalHour, a.totalLabor, a.totalMaterial, c.workshopID, CASE WHEN shortName IS NULL THEN e.CompleteName
		                                                                          WHEN shortName='' THEN e.CompleteName
							   											     ELSE shortName END AS CompleteName,
		           Format(a.estimateDate,'yyyy-MM-dd') As tanggalEst, Format(c.CRDate, 'yyyy-MM-dd') As AVDate,
                   a.nilaiDPP, Format(a.SPKRepairDate, 'yyyy-MM-dd') As tanggalSPK, Format(a.tanggalApprove, 'yyyy-MM-dd') As tglApprove, 
				   IsNull(f.nilaiDPP,0) As cleaningDPP		  
            From   RepairHeader a 
            Inner Join containerLog b On b.ContainerNo=a.ContainerID
		    Inner Join containerJournal c On c.NoContainer=a.ContainerID And c.bookInID=a.BookID
		    Inner Join tabBookingHeader d On d.bookID=a.BookID
		    Inner Join m_Customer e On e.custRegID=d.principle
		    Left Join  CleaningHeader f On f.BookID=a.BookID And f.ContainerID=a.ContainerID
		    Where  Cond='AV' And (CRDate Between '$tgl1' And '$tgl2') And c.workshopID='$loc' And (c.CRDate Is Not Null And Format(c.CRDate,'yyyy-MM-dd') != '1900-01-01')
		           And a.tanggalApprove !='1900-01-01' And (a.nilaiDPP > f.nilaiDPP Or f.nilaiDPP Is Null)
		    Order By e.CompleteName,c.gateIn, b.Size,b.Type";    
    $result=mssql_query($query);  
    $design="";
    while($arr=mssql_fetch_array($result)) {
      $index++;
	  $tax=($arr["nilaiDPP"] -$arr["cleaningDPP"]) *0.1;
	  $subTotal=($arr["nilaiDPP"] -$arr["cleaningDPP"])+$tax;
    
	  $totalDPP=$totalDPP +$arr["nilaiDPP"]; 
      $totalMH=$totalMH +$arr["totalHour"];
      $totalLabor=$totalLabor +$arr["totalLabor"];
      $totalMtrl=$totalMtrl +$arr["totalMaterial"];
      $totalTax=$totalTax +$tax;
      $grandTotal=$grandTotal +$subTotal;
	
	  $design=$design.'<tr>
	        <td style="text-align: right">'.$index.'</td>
			<td>'.substr($arr["containerID"],0,4).' '.substr($arr["containerID"],4,6).'</td>
			<td>'.substr($arr["containerID"],10,1).'</td>
			<td>'.$arr["CompleteName"].'</td>
			<td>'.$arr["TanggalIn"].'</td>
			<td>'.strtoupper(substr($arr["estimateID"],0,10)).'..'.'</td>			
			<td>'.$arr["tanggalEst"].'</td>
			<td>'.$arr["tglApprove"].'</td>
			<td><strong>Repair</strong></td>
			<td>'.$arr["AVDate"].'</td>
			<td style="text-align: right;border-left:1px solid #ccc;">'.number_format($arr["totalHour"],2,",",".").'</td>
			<td style="text-align: right">'.number_format($arr["totalLabor"],2,",",".").'</td>
			<td style="text-align: right">'.number_format($arr["totalMaterial"],2,",",".").'</td>
			<td style="text-align: right">'.number_format($arr["nilaiDPP"] -$arr["cleaningDPP"],2,",",".").'</td>
			<td style="text-align: right">'.number_format($tax,2,",",".").'</td>
			<td style="text-align: right;border-right:1px solid #ccc;">'.number_format($subTotal,2,",",".").'</td>			
	      </tr>';
    }
    mssql_free_result($result);
/* 

 $design=$design.'<tr style="background:#ccc">
	      <td colspan="8" style="text-align: right"><strong>Total</strong></td>
		  <td style="text-align: right;border-left:1px solid #ccc;">'.number_format($totalMH,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalLabor,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalMtrl,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalDPP,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalTax,2,",",".").'</td>
		  <td style="text-align: right;border-right:1px solid #ccc;">'.number_format($grandTotal,2,",",".").'</td>			
		  <td colspan="2"></td>
	    </tr>';*/
    echo $design;
  }
  
  
  /* Finished Cleaning */
  if($_POST['do']=="CLEANING") {
    $totalDPP_Cleaning=0;
    $totalTax_Cleaning=0;
    $grandTotal_Cleaning=0;
    $subTotal_Cleaning=0;
    $totalMtrl_Cleaning=0;
    $Labor_Cleaning=0;
  	 
    $index=0;
    $design="";
    $query="Select g.estimateID, a.containerID, b.Size, b.Type, b.Height, b.Constr, Format(c.gateIn,'yyyy-MM-dd') As TanggalIn, 
          c.workshopID, CASE WHEN e.shortName IS Not NULL THEN e.shortName
		                     WHEN e.shortName != '' THEN e.shortName
                             ELSE e.CompleteName END AS CompleteName,
		  '' As tanggalEst, Format(a.CleaningDate, 'yyyy-MM-dd') As CleaningDate,
          0 As totalHour, 0 As totalLabor, IsNull(f.materialValue,0) As totalMaterial, a.nilaiDPP, '' As tanggalSPK, Format(g.tanggalApprove, 'yyyy-MM-dd') As tglApprove 
          From CleaningHeader a 
          Inner Join containerLog b On b.ContainerNo=a.ContainerID
		  Inner Join containerJournal c On c.NoContainer=a.ContainerID And c.bookInID=a.BookID
		  Inner Join tabBookingHeader d On d.bookID=a.BookID
		  Inner Join m_Customer e On e.custRegID=d.principle
		  Inner Join CleaningDetail f On f.cleaningID=a.cleaningID
		  Left Join repairHeader g On g.bookID=a.bookID And g.ContainerID=a.ContainerID
		  Where (CCleaning Between '$tgl1' And '$tgl2') And c.workshopID='$loc' 
		  Order By e.CompleteName, c.gateIn,  b.Size, b.Type";
  //echo $query;
    $result=mssql_query($query);  
    while($arr=mssql_fetch_array($result)) {
	  $validToView=1;
	
	  if($arr["estimateID"] != "") {
	    if($arr["tglApprove"]=="1900-01-01" || $arr["tglApprove"]=="") { $validToView=0; } 	
	  }
	
	  if($validToView==1) {
        $index++;
	    $tax=$arr["nilaiDPP"] *0.1;
	    $subTotal_Cleaning=$arr["nilaiDPP"]+$tax;
    
	    $totalDPP_Cleaning=$totalDPP_Cleaning +$arr["nilaiDPP"]; 
        $totalMH_Cleaning=0;
	    $Labor_Cleaning=$arr["nilaiDPP"]-$arr['totalMaterial'];
        $totalLabor_Cleaning=$totalLabor_Cleaning + $Labor_Cleaning;
        $totalMtrl_Cleaning=$totalMtrl_Cleaning+$arr['totalMaterial'];
        $totalTax_Cleaning=$totalTax_Cleaning + $tax;
        $grandTotal_Cleaning=$grandTotal_Cleaning + $subTotal_Cleaning;
	
	    $design=$design.'<tr>
	          <td style="text-align: right">'.$index.'</td>
		  	  <td>'.substr($arr["containerID"],0,4).' '.substr($arr["containerID"],4,6).'</td>
			  <td>'.substr($arr["containerID"],10,1).'</td>
			  <td>'.$arr["CompleteName"].'</td>
			  <td>'.$arr["TanggalIn"].'</td>
			  <td>'.$arr["estimateID"].'</td>
			  <td>'.$arr["tglApprove"].'</td>
			  <td><strong>Cleaning</strong></td>
			  <td>'.$arr["CleaningDate"].'</td>
			  <td style="text-align: right;border-left:1px solid #ccc;">'.number_format($arr["totalHour"],2,",",".").'</td>
			  <td style="text-align: right">'.number_format($Labor_Cleaning,2,",",".").'</td>
			  <td style="text-align: right">'.number_format($arr["totalMaterial"],2,",",".").'</td>
			  <td style="text-align: right">'.number_format($arr["nilaiDPP"],2,",",".").'</td>
			  <td style="text-align: right">'.number_format($tax,2,",",".").'</td>
			  <td style="text-align: right;border-right:1px solid #ccc;">'.number_format($subTotal_Cleaning,2,",",".").'</td>			
	        </tr>';
	  }		
    }
    mssql_free_result($result);
/*
  $design=$design.'<tr style="background:#ccc">
	      <td colspan="8" style="text-align: right"><strong>Total</strong></td>
		  <td style="text-align: right;border-left:1px solid #ccc;">'.number_format($totalMH_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalLabor_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalMtrl_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalDPP_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalTax_Cleaning,2,",",".").'</td>
		  <td style="text-align: right;border-right:1px solid #ccc;">'.number_format($grandTotal_Cleaning,2,",",".").'</td>			
		  <td colspan="2"></td>
	    </tr>';
/* 
  $design=$design.'<tr style="background:#ccc">
	      <td colspan="8" style="text-align: right"><strong>GRAND TOTAL</strong></td>
		  <td style="text-align: right;border-left:1px solid #ccc;">'.number_format($totalMH +$totalMH_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalLabor +$totalLabor_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalMtrl +$totalMtrl_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalDPP +$totalDPP_Cleaning,2,",",".").'</td>
		  <td style="text-align: right">'.number_format($totalTax +$totalTax_Cleaning,2,",",".").'</td>
		  <td style="text-align: right;border-right:1px solid #ccc;">'.number_format($grandTotal +$grandTotal_Cleaning,2,",",".").'</td>			
		  <td colspan="2"></td>
	    </tr>';		
		*/
    echo $design;
  }
  mssql_close($dbSQL);  
</script>

  </tbody>
 </table>

<div class="height-10"></div> 
</body>
</html>

<script language="php">
  }
</script>