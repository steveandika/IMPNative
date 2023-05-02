<script language="php">
  session_start();
  include("../asset/libs/db.php");

  echo '<div class="w3-container">
         <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db;">Registered User</h2>';
  
  $keywrd = '';
  if(isset($_GET['id'])) { $keywrd = strtoupper(trim($_GET['id'])); }

  echo '<form id="cari" method="get" action="/e-imp/personal_data/?">'; 
/*  if($keywrd == '%') { echo '<input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" />'; }
  else { echo '   <input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" value='.$keywrd.' >'; }	*/
  echo '  <input type="hidden" name="show" value="ru">';
  echo '  <input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" value='.$keywrd.' >';
  echo '</form><div class="height-5"></div>';
  
  echo ' <div class="w3-responsive w3-animate-zoom" style="max-height:460px;overflow-y:scroll" id="style-4">';
  echo '  <table class="w3-table-all">
           <thead><tr">
            <th>User ID</th>
            <th>Employee Name</th>
            <th>Status</th>		   
			<th></th>
            <th></th>
            <th></th>
            <th></th>
		    <th></th>
            <th>Allow Insert</th>
            <th>Allow Delete</th>
            <th>Allow Update</th>
           </tr></thead><tbody>';
  
  if($keywrd != '') { 
    $query="Select a.userID, a.isActive, alInsert, alDelete, alEdit, a.locationID, b.completeName, a.isLogin ";
	$query=$query."From userProfile a Left Join m_Employee b On b.empRegID=a.userID ";
	$query=$query."Where (a.userID Like '".'%'.$keywrd.'%'."') Or (b.completeName Like '".'%'.$keywrd.'%'."') Order By b.completeName";  }		
  else { 
    $query="Select a.userID, a.isActive, alInsert, alDelete, alEdit, a.locationID, b.completeName, a.isLogin ";
	$query=$query."From userProfile a Left Join m_Employee b On b.empRegID=a.userID ";
	$query=$query."Order By b.completeName";  }		
  $result = mssql_query($query);	   
  while($rows=mssql_fetch_array($result)) {	
	echo '<tr>';
	echo ' <td>'.$rows[0].'</td>'; 
	echo ' <td>'.$rows[6].'</td>';
	if($rows[1] == 1) { echo ' <td><i class="fa fa-check"></i></td>'; }
	else { echo ' <td>&nbsp;</td>'; }	
	if($rows[7]==1) { echo '<td><a onclick=toggleState("'.$rows[0].'") class="w3-btn w3-pink" style="padding:4px 20px;border-radius:8px">Online</a></td>'; }
	else { echo '<td>Offline</td>'; }
	if($_SESSION['allowDelete'] == 1) {	echo ' <td><a onclick=confirmDelete("'.$rows[0].'") class="w3-btn w3-red w3-round-medium" style="line-height:10px">Delete</a></td>'; }
	else {	echo ' <td><i class="fa fa-lock"></i></td>'; }
	if($_SESSION['allowUpdate'] == 1) {	echo ' <td><a onclick=setupRole("'.$rows[0].'") class="w3-btn w3-blue w3-round-medium" style="line-height:10px">Role</a></a></td>
	                                           <td><a onclick=resetPwd("'.$rows[0].'") class="w3-btn w3-khaki w3-round-medium" style="line-height:10px">Pswd Reset</a></a></td>
	                                           <td><a onclick=opendetail("'.$rows[0].'") class="w3-btn w3-yellow w3-round-medium" style="line-height:10px">Edit Profile</a></a></td>'; }
    else {	echo ' <td><i class="fa fa-lock"></i></a></td>
	               <td><i class="fa fa-lock"></i></a></td>
	               <td><i class="fa fa-lock"></i></a></td>'; }

	if($rows[2] == 1) { echo ' <td><i class="fa fa-check"></i></td>'; }
	else { echo ' <td>&nbsp;</td>'; }
	if($rows[3] == 1) { echo ' <td><i class="fa fa-check"></i></td>'; }
	else { echo ' <td>&nbsp;</td>'; }
	if($rows[4] == 1) { echo ' <td><i class="fa fa-check"></i></td>'; }
	else { echo ' <td>&nbsp;</td>'; }
	echo '</tr>'; }		    
  mssql_free_result($result);
  echo ' </tbody></table>';
  echo '</div>';
  
  echo '</div>';  
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
/*  $(document).ready(function(){
    $("#cari").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("users_list.php", formValues, function(data){ $("#result").html(data); });
    });
  });	*/

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
	  $("#result").load("users_remove.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') { swal('Cancelled', 'Selected record is safe :)', 'error') }
	  })	
  }  
  
  function resetPwd(param) { 
    swal({
      title: 'Are you sure?',
      text: 'You will not be able to recover selected record!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, Reset',
	  cancelButtonText: 'No, Cancel',
	  confirmButtonClass: 'btn btn-sucess',
	  cancelButtonClass: 'btn btn-danger',
	  buttonsStyling: true
	}).then(function () {
	  $("#result").load("reset-pwd.php?id="+param);
	}, function(dismiss) {
		if (dismiss == 'cancel') { swal('Cancelled', 'Selected record is safe :)', 'error') }
	  })	  
  }
  
  function opendetail(param) { $("#result").load("user_reg.php?id="+param); }
  function setupRole(param) { $("#result").load("setup-menu.php?id="+param); }
  function toggleState(param) { $("#result").load("toggleState.php?id="+param); }
  function domanage(urlVariable) { $("#result").load(urlVariable); }
</script>