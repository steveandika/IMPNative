<script language="php">
  include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php");
  openDB();
  
  $mlo = $_POST["mlo"];
  $dttm1 = $_POST["activityDTTM1"];
  $dttm2 = $_POST["activityDTTM2"];
  $activity = $_POST["activityType"];
		
  $billParty = "";
  $workshop = "";
  $currency = "IDR";		
  
  if (isset($_POST["billingParty"])) {$billParty = $_POST["billingParty"];}
  if (isset($_POST["hamparanName"])) {$workshop = $_POST["hamparanName"];}
  if (isset($_POST["currency"])) {$currency = $_POST["currency"];}  
  
  $html = '<html>
           <head>  
             <style>
             html,body {
               -moz-osx-font-smoothing:grayscale;
               font-smooth:auto;
               -webkit-font-smoothing:antialiased;
               text-rendering:optimizeLegibility;
               font-family: "Segoe UI","Arial",sans-serif;
               font-size:11px;
               letter-spacing:0.02em;
               font-weight:400;
               line-height:normal;
               height:100%;
             }	
			 
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
             td {border-bottom:1px solid #ddd}      
			 
			 .height-5{height:5px}
			 .height-10{height:10px}
             .height-20{height:20px}
			 </style>
           </head>

          <body>';
  
  $html .= '<htmlpagefooter name="myfooter">
              <div style="text-align: center; padding-top: 3mm; ">
                Page {PAGENO} of {nb}
              </div>
            </htmlpagefooter>
            <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
            <sethtmlpagefooter name="myfooter" value="on" />';
			 
  if ($activity == 1) {$activityName = "Repair Only";} 
  else {$activityName = "Cleaning Only";}		
  
  $html .= '<div class="height-20"></div>
	        <div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	          Summary Repair and Cleaning
	        </div>
	        <div id="companyTitle" style="font-weight:600">
	          Container Depot Management System - PT. IMP
	        </div>
	        <div class="height-5"></div>
	        <div id="paramReport1"><strong>Hamparan Name</strong>&nbsp;'.$workshop.'</div>
	        <div class="height-3"></div>
	        <div id="paramReport2"><strong>Periode</strong>&nbsp;'.$dttm1.'&nbsp;&nbsp;<strong>until</strong>&nbsp;'.$dttm2.'</div>
	        <div class="height-3"></div>
	        <div id="paramReport3"><strong>Shipping Line</strong>&nbsp;'.$mlo.'</div>
	        <div class="height-3"></div>
	        <div id="paramReport4"><strong>Currency</strong>&nbsp;'.$currency.'</div>
	        <div class="height-3"></div>
	        <div id="paramReport5"><strong>Billing Party</strong>&nbsp;'.$billParty.'</div>
	        <div class="height-3"></div>
	        <div id="paramReport6"><strong>Activity</strong>&nbsp;'.$activityName.'</div>	
	        <div class="height-10"></div>';

  $html .= '<table>
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
              </tr>';	
			  
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
		  		
  $result = mssql_query($qry);
  $indexRow = 0;
  while ($arr = mssql_fetch_array($result)){
    $indexRow++;	

    $html .= '<tr>
	   	       <td>'.$indexRow.'</td>
	   	       <td>'.$arr["estimateID"].'</td>
	   	       <td>'.$arr["NoContainer"].'</td>
	   	       <td>'.$arr["ContProfile"].'</td>
	   	       <td>'.$arr["TanggalMasuk"].'</td>
	   	       <td>'.$arr["tanggalApp"].'</td>
	   	       <td>'.$arr["FinishEOR"].'</td>
	   	       <td style="text-align:right">'.number_format($arr["totalLabor"],2,",",".").'</td>
	   	       <td style="text-align:right">'.number_format($arr["totalHour"],2,",",".").'</td>
	   	       <td style="text-align:right">'.number_format($arr["totalMaterial"],2,",",".").'</td>
	   	       <td style="text-align:right">'.number_format($arr["totalValue"],2,",",".").'</td>
    	      </tr>';
  }

  $html .= '  <table>
            </body>
			</html>';

  mssql_free_result($result);
  
  include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/MPDF57/mpdf.php");	
  $mpdf = new mPDF();
  $mpdf -> WriteHTML($html);
  $mpdf -> SetDisplayMode('fullpage');
  $mpdf -> Output();  
</script>