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

	if ($workshopID == "ALL")
	{
		$query="Select * From view_Summary_Hamparan Where NoContainer='$noCnt' order by gateIn DESC; ";
	}
	else
	{
		$query="Select * From view_Summary_Hamparan Where NoContainer='$noCnt' and workshopID= '$workshopID' order by gateIn DESC; ";
	}	
	//echo strtoupper($_SESSION["uid"]).$query."<br>";  
	$result = mssql_query($query);    	
	
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
	<table class="w3-table w3-table-all w3-striped">
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