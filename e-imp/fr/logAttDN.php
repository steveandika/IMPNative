<script language="php">
  $defHTML = $_SESSION['defurl']."/e-imp";
  
  include_once ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php"); 	
  $connDB = openDB();

  if ($connDB == "connected" && $_GET['is'] != "%"){
	$filtername = $_GET['filter'];
    $filtervalue = $_GET['is'];
    $condition = $_GET['cnd']; 
	if ($condition == "EQUAL"){
	  $condition = "=";
    }	  
	
    $limitPerPage = 25;
	$page = $_POST['page'];
	$reqAccess = $_POST['reqPage'];
		
	$sql = "Select * from C_ListDocumenInvoice with (NOLOCK) where ".$filtername." ".$condition;
    if ($condition == "LIKE") { $sql .= " '%".$filtervalue."%' "; }
	if ($condition == "=") { $sql .= " '".$filtervalue."' "; }
    $sql .=	"order by invoiceDate DESC ";
    $result = mssql_query($sql);

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
	
    $html = '';
	$html .= '<div class="frame border-radius-3">';
	$html .= ' <div class="frame-title"><strong>Awaiting Bill List</strong></div> ';
	$html .= ' <div class="w3-container">';
	$html .= '   <div class="height-10"></div>';	
    $html .= '   <span>Total row(s): '.$totalRows.'</span>';	
	
	$html .= '   <div style="overflow-x:auto;height:70vh">
	              <table>
                   <tr style="background-color:#ddd">
			        <th>Document Name</th>
				    <th>Invoice#</th>
				    <th>Invoice Date</th>
			       </tr>';
				 
	$sql .= "OFFSET $start_from ROWS FETCH NEXT $limitPerPage ROWS ONLY ";

	$result = mssql_query($sql);
	while($arr = mssql_fetch_array($result)){
	  //$defHTML = "//localhost:8080/imp/prod/e-imp";
	  
	  $viewurl = array('src'=>base64_encode("newmnr/EditAttDNDoc.php"),'prm'=>base64_encode($arr['invoiceNumber']),'dcn'=>base64_encode($arr['DocNumber']),'filter'=>$filtername,'cnd'=>$condition,'is'=>$filtervalue);
	  $voidurl = array('src'=>base64_encode("newmnr/voidDocDN.php"),'prm'=>base64_encode($arr['invoiceNumber']),'dcn'=>base64_encode($arr['DocNumber']),'filter'=>$filtername,'cnd'=>$condition,'is'=>$filtervalue);
	  $invurl = array('src'=>base64_encode("newmnr/setInvoice.php"),'prm'=>base64_encode($arr['invoiceNumber']),'dcn'=>base64_encode($arr['DocNumber']),'filter'=>$filtername,'cnd'=>$condition,'is'=>$filtervalue);
	  $pdfurl = array('prm'=>base64_encode($arr['invoiceNumber']),'dcn'=>base64_encode($arr['DocNumber']));
	  $xlsurl = "/fr/logInvoiceDetail_xls?dcn=".base64_encode($arr['DocNumber']);

      /* --- part detail --- */ 
      $html .= "<tr>";
	  $html .= " <td style='border-bottom:0px!important'>".$arr['DocNumber']."</td>";							  
	  $html .= " <td style='border-bottom:0px!important'>".$arr['invoiceNumber']."</td>";
	  $html .= " <td style='border-bottom:0px!important'>".$arr['InvoiceDate']."</td>";
      $html .= "</tr>";
	  
	  /* --- part data navigation --- */
	  $html .= "<tr>";
	  $html .= " <td colspan='3'>";
	  $html .= "   <a href=".$defHTML."/1?".http_build_query($viewurl)." class='w3-button w3-blue border-radius-3'>View</a>&nbsp;";
	  $html .= "   <a href=".$defHTML."/1?".http_build_query($invurl)." class='w3-button w3-green border-radius-3'>Invoice</a>&nbsp;";						  
	  $html .= "   <a href=".$defHTML."/1?".http_build_query($voidurl)." class='w3-button w3-red border-radius-3'>Void Doc.</a>&nbsp;";						  
	  $html .= "   <a href=".$defHTML."/fr/logInvoiceDetail?".http_build_query($pdfurl)." target='wDetail' class='w3-button w3-grey border-radius-3'>PDF</a>";
	  $html .= "   <a href=".$defHTML.$xlsurl." target='wexport' class='w3-button w3-grey border-radius-3'>XLS</a>";
	  $html .= " </td>";
      $html .= "</tr>";					
	}	
	mssql_free_result($result);
	
	$html .='</table></div><div class="height-10"></div>';
	
	if ($totalPage > 1){
	  $html .="<div class='flex-container border-radius-5' style='width:200px;padding:5px 5px;background-color:#f0f3f4;float:left'>	          
	            <div class='flex-item' style='padding:0px!important;'>
                 <form method='post'>
	               <input type='hidden' name='reqPage' value='first' />
	  	           <input type='hidden' name='page' value=".$page." />
      	    	     <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
			    	  <img src='".$defHTML."/asset/img/first_36377.png' width='16' height=16'></button>
	             </form>		  
			    </div>
	            <div class='flex-item' style='padding:0px!important;'>			  
                 <form method='post'>
                   <input type='hidden' name='reqPage' value='prev' />
    	           <input type='hidden' name='page' value=".$page." />				 
      		         <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
			          <img src='".$defHTML."/asset/img/previous_5689.png' width='16' height=16'></button>
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
				    <img src='".$defHTML."/asset/img/next_5689.png' width='16' height=16'></button>
	             </form>		  
			    </div>
	            <div class='flex-item' style='padding:0px!important;'>			  			   
			     <form method='post'>
	               <input type='hidden' name='reqPage' value='last' />
			       <input type='hidden' name='page' value=".$page." />				 
      		         <button type='submit' style='background:none!important;border:0px!important;outline:0px!important'>
			         <img src='".$defHTML."/asset/img/last_36378.png' width='16' height=16'></button>
	             </form>		  
			    </div> 
			   </div>";
	}
	
	echo $html;
  }
</script>	