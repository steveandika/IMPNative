<script language="php">
  session_start();
  include("../asset/libs/db.php");
</script>

<div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Port Registration</h2>
  <div class="height-20"></div> 

  <form id="fRegPort" method="post">
    <script language="php">
	  if(isset($_GET['id'])) {
	    $keywrd=$_GET['id'];
		$query="Select * From m_harbour Where portCode='".$keywrd."'";
		$result=mssql_query($query);
		if(mssql_num_rows($result) > 0) {
		  $arr=mssql_fetch_array($result);
	      $namaport=$arr[2];
		  $countryname=$arr[4];
		  $countryid=$arr[3]; }
		mssql_close($dbSQL); 
		echo '<input type="hidden" name="id" value='.$keywrd.'>'; }					
	 </script>
			  
	  <label class="w3-text-teal">Port Name</label>
	  <input class="w3-input w3-border" type="text" maxlength="100" style="text-transform:uppercase;" required name="namaport" value='<?php echo $namaport;?>'>
	  
	  <div class="height-10"></div>
	  <label class="w3-text-teal">Port Code</label>
	  
	  <script language="php">
	    if(isset($_GET['id'])) { echo '<input class="w3-input w3-light-grey" type="text" style="text-transform:uppercase;" readonly name="kodeport" value="'.$keywrd.'">'; }
		else { echo '<input class="w3-input w3-border" type="text" maxlength="5" style="text-transform:uppercase;" required name="kodeport" />'; }
	  </script>
	  
	  <div class="height-10"></div>			
	  <label class="w3-text-teal">Country Name</label>
	  <input class="w3-input w3-border" type="text" maxlength="100" style="text-transform:uppercase;" required name="countryname" value='<?php echo $countryname;?>'>			  
	  <div class="height-10"></div>
	  <label class="w3-text-teal">Country Code</label>
	  <input class="w3-input w3-border" type="text" maxlength="5" style="text-transform:uppercase;" required name="countryid" value='<?php echo $countryid;?>'>			  
	  <div class="height-20"></div>		
      <script language="php">	  	    
	    if(isset($_GET['id'])) { 
		  echo '<input type="submit" class="w3-btn w3-pink" name="update_field" value="Update" />&nbsp;
		        <input type="button" onclick="discharge()" class="w3-btn w3-grey" name="batal" value="Discharge" />'; 
		}
	    else { echo '<input type="submit" class="w3-btn w3-blue" name="register" value="Register" />'; }
	  </script>
  </form>		  
    
  <div class="height-10"></div>	
  <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Registered Ports</h2>
  <div class="height-20"></div> 
		
  <div class="w3-responsive w3-animate-opacity">
	<table class="w3-table w3-border w3-bordered">
	  <thead><tr style="text-transform:uppercase">
		<th>Port Code</th>
		<th>Port Name</th>
		<th>Country Code</th>
		<th>Country Name</th>	  
		<th></th>
		<th></th></tr>
	  </thead>
	  <tbody>
	    <script language="php">
	    $query="Select * From m_harbour Order By countryCode, portCode";
		$result=mssql_query($query);
		while($arr=mssql_fetch_array($result)) {
		  echo '<tr>
		         <td>'.$arr[1].'</td>
		         <td>'.$arr[2].'</td>
			     <td>'.$arr[3].'</td>
				 <td>'.$arr[4].'</td>';
		  if($_SESSION["allowDelete"]==1) { echo '<td><a onclick=confirmDelete("'.$arr[0].'") class="w3-btn w3-red" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Delete</a></td>'; }
		  else { echo '<td><i class="fa fa-locked"></i></a></td>'; }
		  if($_SESSION["allowUpdate"]==1) { echo '<td><a onclick=opendetail("'.$arr[1].'")  class="w3-btn w3-blue" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Update</a></td>'; }	
		  else { echo '<td><i class="fa fa-locked"></i></a></td>'; } 															
		  echo '</tr>'; }	  
		  mssql_close($dbSQL);
		</script>
	  </tbody>
	</table>
  </div>
</div> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../asset/js/sweetalert2.min.js"></script> 
<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegPort").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("dostore_port.php", formValues, function(data){ $("#result").html(data); });
    });	 
  }); 

  function confirmDelete(privVariable) {
    swal({
      title: 'Are you sure?',
      text: 'You will not be able to recover selected record!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, Delete',
	  cancelButtonText: 'No, Cancel',
	  confirmButtonClass: 'btn btn-sucess',
	  cancelButtonClass: 'btn btn-danger',
	  buttonsStyling: true
	}).then(function () {
	  $("#result").load("doremove_port.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled','Selected record is safe :)','error')}
	  })	
  }  	
	
  function opendetail(urlVariable) { $("#result").load("ports.php?id="+urlVariable); }
  function discharge() { $url="/e-imp/master.data/?show=rhrb";  location.replace($url); }   
</script> 