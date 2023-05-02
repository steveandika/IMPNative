<script language="php">
  session_start();
  include("../asset/libs/db.php");
</script>  
  
<div class="w3-container w3-belize-hole w3-text-white">
  <h2><i class="fa fa-table"></i>&nbsp; Approval Form</h2>
</div>

<script language="php">
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
  $totalRow=mssql_num_rows($result);
</script>

<div class="height-20"></div>
<div class="addon-form">
  <div id="content">
  <form id="fapproval" method="get">
    <table class="w3-table w3-striped w3-table-all" style="font-size:12px">
	  <thead><tr>
	    <th>Approve</th>
		<th>Drop</th>
	    <th>Estimate Number</th>
		<th>Container Number</th>
		<th>Size/Type/Height</th>
		<th>Const</th>
		<th>Gate In</th>
		<th>Estimate Date</th>
		<th>Due Date</th>
		<th>Location</th>	
	  </tr></thead><tbody>
	  
<script language="php">
  if($totalRow <= 0) {
    echo '<tr>
	        <td colspan="10" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>';	       
  }
  
  $loc='';
  while($fetcharr=mssql_fetch_array($result)) {
    $isoCode=$fetcharr["Size"]."/".$fetcharr["Type"]."/".$fetcharr["Height"];
	if($loc != $fetcharr["locationID"]) {
	  $loc=$fetcharr["locationID"];
	  echo '<tr>
	        <td colspan="10" style="text-align:left" class="w3-orange">Location: '.$fetcharr["locationID"].'</td></tr>'; }		
			
	echo '<tr><td><input class="w3-check" type="checkbox" name="isApprove[]">
	              <input type="hidden" name="estimateID[]" value='.$fetcharr[0].'></td>
			  <td><a href="?do=trash&id='.$fetcharr[0].'" style="cursor:pointer"><i class="fa fa-trash"></i></a></td>
			  <td>'.$fetcharr[0].'</td>
			  <td>'.$fetcharr[1].'</td>
			  <td>'.$isoCode.'</td>
			  <td>'.$fetcharr[5].'</td>
			  <td>'.$fetcharr[6].'</td>
			  <td>'.$fetcharr["tanggalEst"].'</td>
			  <td>'.$fetcharr["dueDate"].'</td></tr>'; }
  
  mssql_free_result($result);
</script>	  

	  </tbody>
	</table>

<script language="php">
  if($totalRow > 0) {
</script>	  
	<div class="height-20"></div>
	<input type="hidden" name="whatToDo" value="" />
	<button type="submit" class="w3-btn w3-border w3-light-grey w3-text-blue" 
			value="SUBMIT" name="approved" onclick='this.form.whatToDo.value = this.value;'>Register</button>
<script language="php">
  }
</script>
  
  </form>
  <div class="height-10"></div>		
  </div>  
</div>

<script language="php">
  mssql_close($dbSQL);
</script>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
<script type="text/javascript">
  $(document).ready(function(){
    $("#fapproval").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("doapprove.php", formValues, function(data){ $("#result").html(data);  });
    });
  });	
</script>