<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  $keywrd = '';
  $deskripsi = '';
  
  if(isset($_GET['id'])) {
    $keywrd=trim($_GET['id']);
	$query="Select * From m_GroupRepairHeader Where groupID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) >=1 ) {
	  $arr=mssql_fetch_array($result);
	  $deskripsi=$arr[2]; }
	mssql_free_result($result); }
  
  if(isset($_POST['employee'])) {
	$keywrd=$_POST['id'];
	$employee=$_POST['employee'];
	$query="Insert Into m_GroupRepair(groupID, empRegID) Values('$keywrd', '$employee')";
	$result=mssql_query($query); }
  
  $design='<div class="w3-container">';
  $design=$design.' <form id="fRegTeam" method="post">';		
  $design=$design.'  <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Setup Team - Repair Group</h2>  ';
  $design=$design.'  <label class="w3-text-teal">Group ID</label>';
  $design=$design.'  <input class="w3-input w3-light-grey" type="text" Readonly name="id" value='.$keywrd.'>'; 
  $design=$design.'  <div class="height-10"></div>';   
  $design=$design.'  <label class="w3-text-teal">Group Description</label>';
  $design=$design.'  <input class="w3-input w3-light-grey" type="text" Readonly name="deskripsi" value='.$deskripsi.'>'; 
  $design=$design.'  <div class="height-10"></div>';
  
  $design=$design.'  <label class="w3-text-teal">Employee Name</label>';
  $design=$design.'  <select name="employee" class="w3-select w3-border">';
  
  $query="Select empRegID, completeName, b.Description From m_Employee a ";
  $query=$query."Inner Join m_EmployeeFunction b On b.functionID=a.currentFunction ";
  $query=$query."Where empRegID Not In (Select empRegID From m_GroupRepair) ";
  $query=$query."Order By b.Description, completeName;";
  $result=mssql_query($query);
  while($arr=mssql_fetch_array($result)) {
    $design=$design.'<option value='.$arr[0].'>&nbsp;&nbsp;'.$arr[1].' - '.$arr[2].'</option>'; }
  mssql_free_result($result);
  $design=$design.'  </select>'; 
  
  $design=$design.'  <div class="height-20"></div>';
  $design=$design.'  <input type="submit" class="w3-btn w3-blue" name="register" value="Register" />';    
  $design=$design.' </form>';
  
  $design=$design.' <div class="height-20"></div>';
  
  $query="Select a.empRegID, b.completeName, c.Description From m_GroupRepair a ";
  $query=$query."Inner Join m_Employee b On b.empRegID=a.empRegID ";
  $query=$query."Inner Join m_EmployeeFunction c On c.functionID=b.currentFunction ";
  $query=$query."Where a.groupID='$keywrd' Order By c.Description, b.completeName ";
  $result=mssql_query($query);
  
  $design=$design.'<div class="w3-responsive">';
  $design=$design.'<table class="w3-table w3-border w3-bordered" style="font-size:12px;">';
  $design=$design.' <thead><tr style="text-transform:uppercase">';
  $design=$design.'  <th>Index</th>'; 
  $design=$design.'  <th>Employee RegID</th>';
  $design=$design.'  <th>Employee Name</th>';
  $design=$design.'  <th>Function</th>';  
  $design=$design.'  <th>Delete</th>'; 
  $design=$design.' </tr></thead><tbody>';

  if(mssql_num_rows($result) <= 0) { $design=$design.'<tr><td colspan="5" style="text-align:left;letter-spacing: 1px;color:Red"></td></tr>'; }
  $index=0;
  while($arr=mssql_fetch_array($result)) {
	$index++;
    $design=$design.'<tr>
	                   <td>'.$index.'.</td>';
	$design=$design.' <td>'.$arr[0].'</td>';
	$design=$design.' <td>'.$arr[1].'</td>';	
	$design=$design.' <td>'.$arr[2].'</td>';					   
	if($_SESSION["allowDelete"]==1) { $design=$design.'<td><a onclick=dodelete("'.$arr[0].'") onclick=dodelete("'.$arr[0].'") class="w3-btn w3-red" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Delete</a>&nbsp;&nbsp;</td></tr>'; }
	else { $design=$design.'<td><i class="fa fa-locked"></i></a>&nbsp;&nbsp;</td></tr>'; } 
  }
  mssql_free_result($result); 
	
  $design=$design.'</tbody></table>';
  $design=$design.'</div></div>';  
  
  echo $design;
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../asset/js/sweetalert2.min.js"></script> 
<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegTeam").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("setup_team.php", formValues, function(data){ $("#result").html(data); });
    });
	  
  });  

  function dodelete(privVariable) {
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
	  $("#result").load("drop-member.php?emp="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled','Selected record is safe :)','error' )
		}
	  })	
  }  

  function manage_team(param) { $("#result").load("setup_team.php?id="+param); }  
</script> 