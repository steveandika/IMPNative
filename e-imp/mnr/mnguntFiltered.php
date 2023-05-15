<?php
	include ("../asset/libs/common.php");
	openDB();
  
	if(isset($_POST['location'])) {$workshopID = $_POST['location'];}
	if(isset($_POST['tglIn1'])) {$filter1 = $_POST['tglIn1'];}
	if(isset($_POST['tglIn2'])) {$filter2 = $_POST['tglIn2'];}
	if(isset($_POST['noCnt']))  {$noCnt = strtoupper($_POST['noCnt']);}  
  
	if(isset($_GET['location']) && trim($_GET['location']) != '')   {$workshopID = $_GET['location'];}
	if(isset($_GET['noCnt']) && trim($_GET['noCnt']) != '')         {$noCnt = strtoupper($_GET['noCnt']);}    
  
	$query = "Select TOP(1) * from containerLog with (NOLOCK) where ContainerNo = '$noCnt'; ";

	$result = mssql_query($query);          
	while($arr = mssql_fetch_array($result)) 
	{
		$size = $arr['Size'];
		$type =$arr['Type'];
		$height = $arr['Height'];
		$mnfr = $arr['Mnfr']; 	 
	}
	mssql_free_result($result);
?>

<div style="overflow-x:auto;height:65vh;">
	<table class="w3-table w3-bordered">
		<tr>
			<td colspan="8" style="font-weight:600;background:#ddd">Container Info</td>
		</tr>	
		<tr>
			<td style="font-weight:600">Size</td>
			<td><?php echo $size;?></td>
			<td style="font-weight:600">Container Type</td>
			<td><?php echo $type;?></td>
			<td style="font-weight:600">Height</td>
			<td><?php echo $height;?></td>
			<td style="font-weight:600">Manufacture Year</td>
			<td><?php echo $mnfr;?></td>	  
		</tr>
	</table>
	<div class="height-5"></div>
	<table class="w3-table w3-striped">
		<thead>
			<tr>
				<th></th>
				<th>Ticket No.</th>
				<th>Workshop In</th>
				<th>Estimate Date</th>
				<th>Estimate No.</th>
				<th>Approval</th>
				<th>C/R</th>
				<th>C/C</th>
				<th>Shipping Line</th>
				<th>Workshop Out</th>
				</tr></thead><tbody>	
	   
<?php
	if ($workshopID == "ALL")
	{
		$query="Select * From view_Summary_Hamparan Where NoContainer='$noCnt' order by gateIn DESC; ";
	}
	else
	{
		$query="Select * From view_Summary_Hamparan Where NoContainer='$noCnt' and workshopID= '$workshopID' order by gateIn DESC; ";
	}	
	$result=mssql_query($query);          
	while($arr = mssql_fetch_array($result)) 
	{
		$principle = "";
		$consigne = "";
		$estimateDate = "";
  
		if($arr['principle']!="") { $principle = haveCustomerName($arr['principle']); }
		
		$kodeBooking=str_replace(" ","",$arr["bookInID"]);
		echo '<tr>';
	
		if ($arr['InvoiceNumber'] != '') 
		{ 
			$html = '';
			$html .= '<td><i class="fa fa-lock" aria-hidden="true" style="font-size:15px"></i></td>';
			echo $html;
		} 
		else 
		{
			echo '<td></td>';
		}		
?>				         
        
		<td>
			<a onclick=openDetail('<?php echo $arr["NoContainer"]?>&transid=<?php echo $kodeBooking?>') class="w3-text-blue"  style="cursor:pointer" ><?php echo $arr["bookInID"];?></a>
		</td>
					
<?php
		echo '	<td>'.date('Y-m-d', strtotime($arr['gateIn'])).'</td>';
			
		if (date('Y-m-d', strtotime($arr['estimateDate'])) != '1970-01-01')
		{
			echo '<td>'.date('Y-m-d', strtotime($arr['estimateDate'])).'</td>';
		} 
		else 
		{
			echo '<td></td>';
		}	
	
		echo '	<td>'.$arr['estimateID'].'</td>
				<td>'.$arr['tanggalApprove'].'</td>
				<td>'.$arr['CRDate'].'</td>
				<td>'.$arr['CCleaning'].'</td>
				<td>'.$principle.'</td>
				<td>'.$arr['DTMOut'].'</td>
				</tr>';
	}
	mssql_free_result($result);  
?>
	
	</tbody>
  </table>
</div>  

<script language="text/JavaScript">   
  function openDetail(urlVar) { 
    /*var w=window.open("cont_list.php?noCnt="+urlVar); 
 	$(w.document.body).html(response);  	
	*/
	$('#loader-icon').show();
    $("#mnr_form").load("overview.php?noCnt="+urlVar);
	$('#loader-icon').hide();
	window.location.hash = "edit_content";	
  }
</script>  