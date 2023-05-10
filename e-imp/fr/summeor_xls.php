<?php
  session_start();  
?>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />    
  <title>I-ConS | Export Data</title>
  <style>
    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
      border: 1px solid #ddd;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }  
  </style>
</head>

<body>
  
  <script language="php">
    if (!isset($_SESSION["uid"])){
      $url="../"; 
 	  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
    } 
	else { 
      include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 		  
	  
      if (isset($_POST["mlo"]) && isset($_POST["activityDTTM1"]) && isset($_POST["activityType"])){
		$mlo = $_POST["mlo"];
		$dttm1 = $_POST["activityDTTM1"];
		$dttm2 = $_POST["activityDTTM2"];
		$activity = $_POST["activityType"];
		
		$namaFile="Summary_Repair_Cleaning_".$mlo."_".$dttm1."_".$dttm2;
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=".$namaFile.".xls");  		
		
		$billParty = "";
		$workshop = "";
        $currency = "IDR";		
		if (isset($_POST["billingParty"])) {$billParty = $_POST["billingParty"];}
		if (isset($_POST["hamparanName"])) {$workshop = $_POST["hamparanName"];}
		if (isset($_POST["currency"])) {$currency = $_POST["currency"];}
  </script>
  
	<div class="height-20"></div>
	<div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	  Summary Repair and Cleaning
	</div>
	<div id="companyTitle" style="font-weight:600">
	  Container Depot Management System - PT. IMP
	</div>
	<div style="height:5px"></div>
	<div id="paramReport1"><strong>Hamparan Name</strong>&nbsp;<?php echo $workshop?></div>
	<div id="paramReport2"><strong>Periode</strong>&nbsp;<?php echo $dttm1?>&nbsp;&nbsp;<strong>until</strong>&nbsp;<?php echo $dttm2;?></div>
	<div id="paramReport3"><strong>Shipping Line</strong>&nbsp;<?php echo $mlo?></div>
	<div id="paramReport4"><strong>Currency</strong>&nbsp;<?php echo $currency?></div>
	<div id="paramReport5"><strong>Billing Party</strong>&nbsp;<?php echo $billParty?></div>
	<div id="paramReport6"><strong>Activity</strong>&nbsp;<?php if ($activity == 1) {echo "Repair Only";} else {echo "Cleaning Only";}?></div>
	
	<div style="height:10px"></div>	
	<table class="w3-striped">
        <tr>
		  <th>Index</th>			  
		  <th>EOR #</th>			  
		  <th>Container #</th>
		  <th>Size/Type</th>
		  <th>Hamparan In</th>
		  <th>Approve Date</th>			  			   
		  <th>Finish Date</th>
		  <th style="text-align:right">Total Labor</th>			  
		  <th style="text-align:right">Total Hour</th>			  
		  <th style="text-align:right">Total Material</th>
		  <th style="text-align:right">Total Repair</th>			  
        </tr>
		
     <script language="php">  
		$connDB = openDB();
		
		if ($connDB == "connected"){		  
          if ($billParty == ""){		
		    if ($activity == 1){
		      $qry = "select * from C_Summary_EOR with (NOLOCK)
		              where (gateIn BETWEEN '$dttm1' AND '$dttm2') and workshopID = '$workshop' and currencyAS = '$currency' and Liner = '$mlo' 
					  order by gateIn "; 
		    } else{
		        $qry = "select * from C_Summary_Cleaning with (NOLOCK)
		                where (gateIn BETWEEN '$dttm1' AND '$dttm2') and workshopID = '$workshop' and currencyAS = '$currency' and Liner = '$mlo'
			  	        order by gateIn "; 
		      }	
	      } else {
              if ($billParty =="O")  {$indexbillParty = 0;}
	          if ($billParty =="U1") {$indexbillParty = 1;}	
	          if ($billParty =="T")  {$indexbillParty = 2;}	
              if ($billParty =="U2") {$indexbillParty = 3;}					
			  
		      if ($activity == 1){
		        $qry = "select * from C_Summary_Finish_Repair a
			            Inner Join (SELECT 
                                      estimateID, SUM(hoursValue) totalHour, SUM(laborValue) totalLabor, SUM(materialValue) totalMaterial, SUM(totalValue) totalValue 
                                    FROM RepairDetail with (NOLOCK)
                                    WHERE repairID NOT IN ('WW','DW','CC','SC','SW') AND isOwner = $indexbillParty
                                    GROUP BY estimateID) x ON x.estimateID = a.estimateID
		                where (gateIn BETWEEN '$dttm1' AND '$dttm2') and workshopID = '$workshop' and currencyAS = '$currency' and Liner = '$mlo'
			            order by gateIn "; 
		      } else {
		          $qry = "select * from C_Summary_Finish_Repair a
			              Inner Join (SELECT 
                                        estimateID, SUM(hoursValue) totalHour, SUM(laborValue) totalLabor, SUM(materialValue) totalMaterial, SUM(totalValue) totalValue 
                                      FROM RepairDetail with (NOLOCK)
                                      WHERE repairID IN ('WW','DW','CC','SC','SW') AND isOwner = $indexbillParty
                                      GROUP BY estimateID) x ON x.estimateID = a.estimateID
		                  where (gateIn BETWEEN '$dttm1' AND '$dttm2') and workshopID = '$workshop' and currencyAS = '$currency' and Liner = '$mlo'
			              order by gateIn "; 				  
			    }				  
		    }  
		  		
		  $indexRow = 0;
		  $result = mssql_query($qry);	

		  while ($arr = mssql_fetch_array($result)){
		    $indexRow++;	
      </script>
       
  	        <tr>
		     <td><?php echo $indexRow; ?>.</td>
		     <td><?php echo $arr["estimateID"]; ?></td>
		     <td><?php echo $arr["NoContainer"]; ?></td>
		     <td><?php echo $arr["ContProfile"]; ?></td>
		     <td><?php echo $arr["TanggalMasuk"]; ?></td>
		     <td><?php echo $arr["tanggalApp"]; ?></td>
		     <td><?php echo $arr["FinishEOR"];  ?></td>
		     <td style="text-align:right"><?php echo $arr["totalLabor"]; ?></td>
		     <td style="text-align:right"><?php echo $arr["totalHour"];  ?></td>
		     <td style="text-align:right"><?php echo $arr["totalMaterial"]; ?></td>
		     <td style="text-align:right"><?php echo $arr["totalValue"];  ?></td>
    	    </tr>    
		
   <script language="php"> 
          }	  
		  mssql_free_result($result);
		}
       		
   </script>
   
    </table>
	    
   <script language="php"> 
	  }	  
    }	
  </script>

</body>
</html>