<script language="php">
  include("../asset/libs/db.php");
</script>  

<div class="w3-container">
 <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Complete Repair Registration</h2>
 <div class="height-20"></div>	

<script language="php">
  $query="Select a.estimateID, a.containerID, b.Size, b.Type, b.Height, b.Constr, Format(c.gateIn,'yyyy-MM-dd') As TanggalIn, 
          a.totalHour, a.totalLabor, a.totalMaterial, d.locationID, e.CompleteName, DATEDIFF(day, c.gateIn, GETDATE()) AS dueDate, 
		  Format(a.estimateDate,'yyyy-MM-dd') As tanggalEst, SPKRepairID 
          From RepairHeader a 
          Inner Join containerLog b On b.ContainerNo=a.ContainerID
		  Inner Join containerJournal c On c.NoContainer=a.ContainerID And c.bookInID=a.BookID
		  Inner Join tabBookingHeader d On d.bookID=a.BookID
		  Inner Join m_Customer e On e.custRegID=d.principle
		  Where isApproved=1  And (isAVRepair = 0 Or isAVRepair Is Null) ";
  if($_SESSION["location"] != 'ALL') { $query=$query." And (d.locationID = '".$_SESSION["location"]."' "; } 			  
  $query=$query." Order By a.estimateDate ";  
  $result=mssql_query($query);
  $totalRow=mssql_num_rows($result);
</script>

 <form id="favrepair" method="get">
   <div class="w3-responsive w3-animate-opacity">
     <table class="w3-table w3-border w3-bordered">
	  <thead><tr>	     
	    <th>Set Approve</th> 
	    <th>Estimate Number</th>
		<th>Word Order</th>
		<th>Container Number</th>
		<th>Size/Type/Height</th>
		<th>Const</th>
		<th>Gate In</th>
		<th>Estimate Date</th>
		<th>Due Date</th>
		<th>Location</th>	
	   </tr>
	  </thead>
	  <tbody>
	  
<script language="php">
  if($totalRow <= 0) {
    echo '<tr>
	        <td colspan="10" style="color:red;letter-spacing:1px">RECORD NOT FOUND</td></tr>';
	       
  }
  
  while($fetcharr=mssql_fetch_array($result)) {
    $isoCode=$fetcharr["Size"]."/".$fetcharr["Type"]."/".$fetcharr["Height"];
	echo '<tr><td><input class="w3-check" type="checkbox" name="isApprove[]">
	              <input type="hidden" name="estimateID[]" value='.$fetcharr[0].'></td>
			  <td>'.$fetcharr[0].'</td>
			  <td>'.$fetcharr["SPKRepairID"].'</td>
			  <td>'.$fetcharr[1].'</td>
			  <td>'.$isoCode.'</td>
			  <td>'.$fetcharr[5].'</td>
			  <td>'.$fetcharr[6].'</td>
			  <td>'.$fetcharr["tanggalEst"].'</td>
			  <td>'.$fetcharr["dueDate"].'</td>
			  <td>'.$fetcharr["locationID"].'</td></tr>'; }
  
  mssql_free_result($result);
</script>	  

	  </tbody>
	</table>

<script language="php">
  if($totalRow > 0) {
</script>	  
	<div class="height-20"></div>
	<input type="hidden" name="whatToDo" value="" />
	<button type="submit" class="w3-btn w3-blue" 
			value="SUBMIT" name="approved" onclick='this.form.whatToDo.value = this.value;'>Register</button>
<script language="php">
  }
</script>
    </div>
  </form>
  <div class="height-10"></div>		
  <div id="content"></div>  
</div>

<script language="php">
  mssql_close($dbSQL);
</script>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script src="../asset/js/sweetalert2.min.js"></script>      
<script type="text/javascript">
  $(document).ready(function(){
    $("#favrepair").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("dofinish_repair.php", formValues, function(data){
        $("#content").html(data);
      });
    });
  });	
</script>	