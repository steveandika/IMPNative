<script language="php">
  include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php");
  
  $filter1 = base64_decode($_GET["dttm"]);
  $filter2 = base64_decode($_GET["wh"]);
  
  $html = '<html>
           <head>  
             <style>
             html,body {
               -moz-osx-font-smoothing:grayscale;
               font-smooth:auto;
               -webkit-font-smoothing:antialiased;
               text-rendering:optimizeLegibility;
               font-family: "Segoe UI","Arial",sans-serif;
               font-size:10px;
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
    $query = "select 
                NoContainer, Format(gateIn, 'yyyy-MM-dd') gateIn, Size, Type, Height, Format(gateOut, 'yyyy-MM-dd') gateOut, 
                CONVERT(VARCHAR(10), tanggalSurvey, 105) surveyDate, CONVERT(VARCHAR(10), CRDate, 105) CRDate, 
		        CONVERT(VARCHAR(10), CCleaning, 105) CCleaning, 
				CONVERT(VARCHAR(10), AVCond, 105) AVCond,
                c.principle, c.consignee, a.bookInID, cleaningType, d.estimateID, 
                CONVERT(VARCHAR(10), d.estimateDate, 105) submittedDate
              from containerJournal a 
		      INNER JOIN containerLog b ON b.ContainerNo = a.NoContainer
		      INNER JOIN tabBookingHeader c ON c.BookId = a.bookInID
		      LEFT JOIN RepairHeader d ON d.containerID = a.NoContainer And d.BookID = a.bookInID
			  where
			    gateIn = '".$filter1."' and workshopID = '".$filter2."' order by Size, NoContainer ";
    $result = mssql_query($query);
	$totalRows = mssql_num_rows($result);
    if ($totalRows > 0) {
      $dataIndex = 0;
		  
	
      $html = $html.'<div class="height-20"></div>
	                 <div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	                   Daily Summary Hamparan - Detail
	                 </div>
	                 <div id="companyTitle" style="font-weight:600">
	                   Container Depot Management System - PT. IMP
	                 </div>
	                 <div class="height-5"></div>
	                 <div id="paramReport1"><strong>Hamparan Name</strong>&nbsp;'.$filter2.'</div>
					 <div class="height-5"></div>
					 <div id="paramReport2"><strong>Hamparan Date In</strong>&nbsp;'.$filter1.'</div>
	                 <div class="height-10"></div>	

                     <htmlpagefooter name="myfooter">
                       <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
                         Page {PAGENO} of {nb}
                       </div>
                     </htmlpagefooter>
                     <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                     <sethtmlpagefooter name="myfooter" value="on" />
				   
	                 <table>
                      <tr>
		               <th>Index</th>			  
		               <th>Container #</th>			  
		               <th>Size</th>
		               <th>Shipping Line</th>
		               <th>Ex. User</th>
		               <th>Hamp. In</th>			  			   
		               <th>Survey Date</th>
		               <th>Estimate #</th>			  
		               <th>Cleaning</th>			  
		               <th>AV Repair</th>
		               <th>Hamp. Out</th>	
                     </tr>';
		  
	  while ($arr = mssql_fetch_array($result)) {
		$dataIndex++;  
        $liners = haveCustomerName($arr["principle"]);
		$consignee = haveCustomerName($arr["consignee"]);			
		
		$html = $html.'<tr>
		                <td>'.$dataIndex.'.</td>
		                <td>'.$arr["NoContainer"].'</td>
		                <td>'.$arr["Size"].'</td>
		                <td>'.$liners.'</td>
		                <td>'.$consignee.'</td>
		                <td>'.$arr["gateIn"].'</td>
		                <td>'.$arr["surveyDate"].'</td>
		                <td>'.$arr["submittedDate"].'</td>
		                <td>'.$arr["cleaningType"].'</td>
		                <td>'.$arr["AVCond"].'</td>
		                <td>'.$arr["gateOut"].'</td>
		               </tr>';
      }	

	  mssql_free_result($result);	  
	  $html = $html."   </table>
                     </body>
                     </html>";
	
  	  include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/MPDF57/mpdf.php");		  
      $mpdf = new mPDF();
      $mpdf -> WriteHTML($html);
      $mpdf -> SetDisplayMode('fullpage');
      $mpdf -> Output();
	  
	}	
  }	
</script>	