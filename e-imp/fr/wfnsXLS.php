<script language="php">
  include("../asset/libs/db.php");
  
  $filename = 'wfinish-imp_'.date('YmdHis');
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$filename.".xls");	
  
  $query="Select a.estimateID, a.containerID, b.Size, b.Type, b.Height, b.Constr, Format(c.gateIn,'yyyy-MM-dd') As TanggalIn, 
          a.totalHour, a.totalLabor, a.totalMaterial, d.locationID, e.CompleteName, DATEDIFF(day, c.gateIn, GETDATE()) AS dueDate, 
		  Format(a.estimateDate,'yyyy-MM-dd') As tanggalEst, Format(a.SPKRepairDate, 'yyyy-MM-dd') As tanggalSPK  
          From RepairHeader a 
          Inner Join containerLog b On b.ContainerNo=a.ContainerID
		  Inner Join containerJournal c On c.NoContainer=a.ContainerID And c.bookInID=a.BookID
		  Inner Join tabBookingHeader d On d.bookID=a.BookID
		  Inner Join m_Customer e On e.custRegID=d.principle
		  Where isApproved=1 And Cond='DM' Order By a.estimateDate";
  $result=mssql_query($query);  
</script>

<table style="font-size:12px;border:1px solid #ccc;border-collapse:collapse" >
  <thead>
  <tr><th colspan="8" style="border:1px solid #ccc;font-size:14px;">WAITING (FINISH) REPAIR</th></tr>
  <tr>
	<th style="border:1px solid #ccc;">Estimate Number</th>
	<th style="border:1px solid #ccc;">Container Number</th>
	<th style="border:1px solid #ccc;">Size/Type/Height</th>
	<th style="border:1px solid #ccc;">Const</th>
	<th style="border:1px solid #ccc;">Gate In</th>
	<th style="border:1px solid #ccc;">Estimate Date</th>
	<th style="border:1px solid #ccc;">Work Order Date</th>
	<th style="border:1px solid #ccc;">Due Date</th>
   </tr></thead><tbody>
  
<script language="php">
    if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="8" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left;border:1px solid #ccc;">RECORD NOT FOUND</td></tr>'; }
	
	else {
	  $loc='';
	  while($arr=mssql_fetch_array($result)) {
		$sizeType=$arr[2].'/'.$arr[3].'/'.$arr[4];
		
	    if($loc != $arr[6]) {
		  $loc=$arr[6];
		  echo '<tr><td colspan="8" class="w3-deep-orange" style="text-align:left;border:1px solid #ccc;"><b>Location: '.$loc.'</b></td></tr>'; }       		
	    echo '<tr>
			  <td style="border:1px solid #ccc;">'.$fetcharr[0].'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr[1].'</td>
			  <td style="border:1px solid #ccc;">'.$isoCode.'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr[5].'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr[6].'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr["tanggalEst"].'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr["tanggalSPK"].'</td>
			  <td style="border:1px solid #ccc;">'.$fetcharr["dueDate"].'</td></tr>'; }
	  }
    mssql_free_result($result);
	mssql_Close($dbSQL);
</script>
  
  </tbody>
</table>