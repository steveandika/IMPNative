<script language="php">
  include("../asset/libs/db.php");
</script>

<div class="w3-container w3-belize-hole">
  <h2><i class="fa fa-ship"></i>&nbsp;Listed Port</h2>
</div> 
<div class="clear height-20"></div>	

<div class="addon-form">
  <form id="fRegPort" method="post">
    <div class="w3-container">
	
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
	  <input type="submit" class="w3-btn w3-border w3-light-grey w3-text-blue" name="register" value="Register" />
	</div> <!-- end of w3-container -->
  </form>		  

  <div class="height-10"></div>	
		

  <div class="w3-responsive">
	<table class="w3-striped w3-table-all">
	  <thead><tr>
		<th>Delete</th>
		<th>Update</th>
		<th>Port Code</th>
		<th>Port Name</th>
		<th>Country Code</th>
		<th>Country Name</th></tr>
	  </thead>
	  <tbody>
	    <script language="php">
	    $query="Select * From m_harbour Order By countryCode, portCode";
		$result=mssql_query($query);
		while($arr=mssql_fetch_array($result)) {
		  echo '<tr>';
		  if($_SESSION["allowDelete"]==1) { echo '<td><a onclick=confirmDelete("'.$arr[0].'") style="cursor: pointer;"><i class="fa fa-trash"></i></a></td>'; }
		  else { echo '<td><i class="fa fa-locked"></i></a></td>'; }
		  if($_SESSION["allowUpdate"]==1) { echo '<td><a onclick=opendetail("'.$arr[1].'") style="cursor: pointer;"><i class="fa fa-folder-open-o"></i></a></td>'; }	
		  else { echo '<td><i class="fa fa-locked"></i></a></td>'; } 															
		  echo ' <td>'.$arr[1].'</td>
		         <td>'.$arr[2].'</td>
			     <td>'.$arr[3].'</td>
				 <td>'.$arr[4].'</td></tr>'; }	  
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
      $.post("dostore.php", formValues, function(data){ $("#content").html(data); });
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
	  $("#content").load("doremove.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled','Selected record is safe :)','error')}
	  })	
  }  	
	
  function opendetail(urlVariable) { $("#content").load("content.php?id="+urlVariable); }
</script> 