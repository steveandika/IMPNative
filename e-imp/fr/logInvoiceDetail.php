<script language="php">
  include_once ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php");	  
  
  $invoiceNo = base64_decode($_GET["prm"]);
  $docNumber = base64_decode($_GET["dcn"]);
  
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

		
  $connDB = openDB();
  if ($connDB == "connected"){
	$errMsg = "";	
	
	$html = $html.'<div class="height-20"></div>
	               <div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	                 Log Invoice Repair
	               </div>
	               <div id="companyTitle" style="font-weight:600">
	                 Container Depot Management System - PT. IMP
	               </div>
	               <div class="height-5"></div>
	               <div id="paramReport1"><strong>Invoice Number</strong>&nbsp;'.$invoiceNo.'</div>
				   <div class="height-5"></div>
	               <div id="paramReport1"><strong>Document Number</strong>&nbsp;'.$docNumber.'</div>
	               <div class="height-10"></div>	

                   <htmlpagefooter name="myfooter">
                     <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
                       Page {PAGENO} of {nb}
                     </div>
                   </htmlpagefooter>
                   <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                   <sethtmlpagefooter name="myfooter" value="on" />';
				   
    $html = $html.'<table>
	                 <tr>
		               <th>Index</th>
		               <th>Estimate #</th>
		               <th>Estimate Date</th>
		               <th>Container #</th>
		               <th>Billing Party</th>
		               <th>Total Value</th>
		               <th>Currency</th>
					   <th>Remark</th>
		             </tr>';
					 
    $qry = "Select a.estimateID, CONVERT(VARCHAR(10), b.estimateDate, 105) estimateDTTM, b.containerID,
                   BillParty, ISNULL(SUM(c.totalValue), 0) totalBill, b.currencyAs
		    from CollectedRepair a left join RepairHeader b on b.estimateID = a.estimateID
			                       left join RepairDetail c on c.estimateID = b.estimateID
			where a.invoiceNumber = '$invoiceNo' or a.DocNumber = '$docNumber' or  c.invoiceNo = '$invoiceNo'
			group by a.estimateID, c.isOwner, b.estimateDate, b.containerID, BillParty, b.currencyAs
			order by b.estimateDate, a.estimateID; ";
    $result = mssql_query($qry);
		  
	if (!$result) {
	  $errMsg = mssql_get_last_message();
  	} else {	
	    $index = 0;
			  
        while($arr = mssql_fetch_array($result)){
	      $index++;	  
		  
		  if ($arr["totalBill"] <= 0) { $NA = "BillParty Not Valid"; }
		  else { $NA = ""; }
		  
		  $html = $html."<tr>
			              <td>".$index.".</td>
			              <td>".$arr["estimateID"]."</td>
			              <td>".$arr["estimateDTTM"]."</td>
			              <td>".$arr["containerID"]."</td>
			              <td>".$arr["BillParty"]."</td>
			              <td>".number_format($arr["totalBill"],2,",",".")."</td>
			              <td>".$arr["currencyAs"]."</td>
						  <td>".$NA."</td>
			            </tr>";
		}
	  }
		
	mssql_free_result($result);
		  
	$html = $html."   </table>
                   </body>
                   </html>";
	
	include ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/MPDF57/mpdf.php");	
    $mpdf = new mPDF();
    $mpdf -> WriteHTML($html);
    $mpdf -> SetDisplayMode('fullpage');
    $mpdf -> Output();
  }
</script>