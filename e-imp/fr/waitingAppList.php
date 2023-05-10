<?php
	include ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php");
  
	$filter = $_POST["hamparanName"];
  
	$html ='<html>
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
	if ($connDB == "connected")
	{ 
		$query = "Select Top(1000) * from C_WaitingApproval with (NOLOCK) where workshopID ='$filter'
				  order by dueDate DESC; ";
		$result = mssql_query($query);
		$totalRows = mssql_num_rows($result);
		if ($totalRows > 0) 
		{
			$dataIndex = 0;

			$html = $html.'<div class="height-20"></div>
	                 <div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	                   Waiting Appoval of EOR
	                 </div>
	                 <div id="companyTitle" style="font-weight:600">
	                   Container Depot Management System - PT. IMP
	                 </div>
	                 <div class="height-5"></div>
	                 <div id="paramReport1"><strong>Hamparan Name</strong>&nbsp;'.$filter.'</div>
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
		               <th>Estimate #</th>
		               <th>Liners</th>
		               <th>Gate In</th>	
		  			   <th>Estimate Date</th>	
		               <th></th>
                     </tr>';
		  
			while ($arr = mssql_fetch_array($result)) 
			{
				$dataIndex++;  
		
				$html = $html.'<tr>
		                <td>'.$dataIndex.'.</td>
		                <td>'.$arr["containerID"].'</td>
		                <td>'.$arr["Size"].'</td>
		                <td>'.$arr["estimateID"].'</td>
		                <td>'.$arr["shortName"].'</td>
		                <td>'.$arr["TanggalIn"].'</td>
		                <td>'.$arr["tanggalEst"].'</td>
		                <td>'.$arr["dueDate"].'</td>
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
?>