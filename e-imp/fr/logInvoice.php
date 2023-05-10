<div class="height-10"></div>

<script language="php">
  $connDB = openDB();
  
  if ($connDB == "connected"){
    $limitPerPage = 25;
	$page = $_POST["page"];
	$reqAccess = $_POST["reqPage"];
		
	$query = "Select * from C_ListDocumenInvoice with (NOLOCK) order by invoiceDate DESC ";
    $result = mssql_query($query);
	
	$totalRows = mssql_num_rows($result);
	$totalPage = ceil($totalRows/$limitPerPage);
	mssql_free_result($result);
	
	if ($page != ""){
	  if ($regAccess == "first"){
	    if ($page > 1) { $page = 1; }	  
	  }	
	  if ($regAccess == "prev"){
	   if ($page > 1) { $page = $page -1; }	  
	  } 	
	  if ($regAccess == "next"){
	    if ($page < $totalPage) { $page = $page +1; }	  
  	  }	
	  if ($regAccess == "last"){
	    if ($page < $totalPage)  { $page = $totalPage; }	  
	  }		
	} 
    else { $page = 1; }	

    $start_from = ($page -1) * $limitPerPage;	
	 
	$html = "<div style='overflow-x:auto;height:80vh'>
	          <table class='w3-striped'>
               <tr style='background:#000!important;color:#fff'>
			     <th>Document Name</th>
				 <th>Invoice #</th>
				 <th>Invoice Date</th></tr>";
				 
	$query .= "OFFSET $start_from ROWS FETCH NEXT $limitPerPage ROWS ONLY ";

	$result = mssql_query($query);
	while($arr = mssql_fetch_array($result)){
	  $defHTML="//icons.pt-imp.com";
	  
	  $html .= "<tr>
	              <td><a href=".$defHTML."/e-imp/fr/logInvoiceDetail?prm=".base64_encode($arr["invoiceNumber"]).
							              "&dcn=".base64_encode($arr["DocNumber"])." target='wDetail'>".$arr["DocNumber"]."</a></td>
				  <td>".$arr["invoiceNumber"]."</td>
				  <td>".$arr["InvoiceDate"]."</td>
	            </tr>";
	}	
	mssql_free_result($result);
	
	$html .="</table><div class='height-10'></div>";
	
	$html .="<div class='flex-container border-radius-5' style='width:200px;padding:5px 5px;background-color:#f0f3f4;float:left'>	          
	          <div class='flex-item' style='padding:0px!important;'>
               <form method='post'>
	             <input type='hidden' name='reqPage' value='first' />
	  	         <input type='hidden' name='page' value=".$page." />
      		     <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
				  <img src='".$defHTML."/e-imp/asset/img/first_36377.png' width='16' height=16'></button>
	           </form>		  
			  </div>
	          <div class='flex-item' style='padding:0px!important;'>			  
               <form method='post'>
                 <input type='hidden' name='reqPage' value='prev' />
    	         <input type='hidden' name='page' value=".$page." />				 
      		     <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
			      <img src='".$defHTML."/e-imp/asset/img/previous_5689.png' width='16' height=16'></button>
	           </form>		  
			  </div>
	          <div class='flex-item'>			  			   
               ".$page." of ".$totalPage."
			  </div>
	          <div class='flex-item' style='padding:0px!important;'>			  			   
			   <form method='post'>
	             <input type='hidden' name='reqPage' value='next' />
				 <input type='hidden' name='page' value=".$page." />				 
      		     <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
				  <img src='".$defHTML."/e-imp/asset/img/next_5689.png' width='16' height=16'></button>
	           </form>		  
			  </div>
	          <div class='flex-item' style='padding:0px!important;'>			  			   
			   <form method='post'>
	             <input type='hidden' name='reqPage' value='last' />
			     <input type='hidden' name='page' value=".$page." />				 
      		     <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
			      <img src='".$defHTML."/e-imp/asset/img/last_36378.png' width='16' height=16'></button>
	           </form>		  
			  </div> 
			 </div></div>";
	
	echo $html;
  }
</script>	