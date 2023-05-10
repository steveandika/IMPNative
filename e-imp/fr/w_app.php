<script language="php">
  include("../asset/libs/db.php");
  
  $query="Select a.estimateID, a.containerID, b.Size, b.Type, b.Height, b.Constr, Format(c.gateIn,'yyyy-MM-dd') As TanggalIn, 
          a.totalHour, a.totalLabor, a.totalMaterial, d.locationID, e.CompleteName, DATEDIFF(day, c.gateIn, GETDATE()) AS dueDate, 
		  Format(a.estimateDate,'yyyy-MM-dd') As tanggalEst 
          From RepairHeader a 
          Inner Join containerLog b On b.ContainerNo=a.ContainerID
		  Inner Join containerJournal c On c.NoContainer=a.ContainerID And c.bookInID=a.BookID
		  Inner Join tabBookingHeader d On d.bookID=a.BookID
		  Inner Join m_Customer e On e.custRegID=d.principle
		  Where (isApproved=0 Or isApproved Is Null) And (estimateID Not Like 'DRF%') Order By a.estimateDate";
  $result=mssql_query($query);  
</script>


<div class="w3-container">
 <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Waiting Approval</h2>

 <div class="height-20"></div>
 <div class="w3-responsive w3-animate-opacity">
 <table class="w3-table w3-border w3-bordered">
  <thead>
  <tr><th colspan="13"><button class="w3-btn w3-green" onclick=exportxls()><i class="fa fa-file-excel-o"></i>&nbsp; Export To XLS</button></th></tr>
  <tr>
	<th>Estimate Number</th>
	<th>Container Number</th>
	<th>Size/Type/Height</th>
	<th>Const</th>
	<th>Gate In</th>
	<th>Estimate Date</th>
	<th>Due Date</th>
   </tr></thead><tbody>
  
<script language="php">
    if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="7" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; }
	
	else {
	  $loc='';
	  while($arr=mssql_fetch_array($result)) {
		$sizeType=$arr[2].'/'.$arr[3].'/'.$arr[4];
		
	    if($loc != $arr[6]) {
		  $loc=$arr[6];
		  echo '<tr><td colspan="7" class="w3-deep-orange" style="text-align:left"><b>Location: '.$loc.'</b></td></tr>'; 
		}
        		
	    echo '<tr>
			  <td>'.$fetcharr[0].'</td>
			  <td>'.$fetcharr[1].'</td>
			  <td>'.$isoCode.'</td>
			  <td>'.$fetcharr[5].'</td>
			  <td>'.$fetcharr[6].'</td>
			  <td>'.$fetcharr["tanggalEst"].'</td>
			  <td>'.$fetcharr["dueDate"].'</td></tr>'; 
	  }
	}
    mssql_free_result($result);
	mssql_close($dbSQL);
</script>
  
  </tbody>
 </table>
 </div> 
</div> 

<script type="text/javascript">
  function exportxls() {  
    var w=window.open("wappXLS.php"); 
 	$(w.document.body).html(response);}	 
</script>