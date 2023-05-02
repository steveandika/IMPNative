<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  $keywrd='';
  $deskripsi='';
  $mode='';
  
  if(isset($_POST['fmode'])) { 
    $keywrd=trim($_POST['id']); 
	$deskripsi=strtoupper($_POST['deskripsi']);
    $mode=trim($_POST['fmode']); }
  	
  if(($keywrd != '') && ($mode=='update')) {
	$query="Update m_GroupRepairHeader Set Description='$deskripsi' Where groupID='$keywrd'";
	$result=mssql_query($query);
    $keywrd='';
    $deskripsi='';	
	echo '<script>swal("Success","Group description updated.")</script>'; }

  if(($keywrd == '') && ($mode=='new')) {	
	$query="DECLARE @TempGroupCode VARCHAR(10), @GroupCode VarChar(50), @LastIndex INT; ";
	$query=$query."SET @TempGroupCode='WRG'; ";
	$query=$query."If Not Exists(Select * From logKeyField Where keyFName Like 'WRG%') Begin ";
	$query=$query."  Set @LastIndex=1; ";
	$query=$query."  Insert Into logKeyField(keyFName,lastNumber) Values(@TempGroupCode, 1); ";
	$query=$query."  Set @GroupCode = CONCAT(@TempGroupCode,'.1'); ";
	$query=$query."End Else Begin ";
	$query=$query."      Select @LastIndex = lastNumber +1 From logKeyField Where keyFName Like 'WRG%'; ";	       
	$query=$query."      Update logKeyField Set lastNumber=@LastIndex Where keyFName Like 'WRG%'; ";
	$query=$query."      Set @GroupCode = CONCAT(@TempGroupCode,RTRIM(LTRIM(CONVERT(VARCHAR(3),'.',@LastIndex)))); ";
	$query=$query."    End; ";
	$query=$query."Insert Into m_GroupRepairHeader(groupID,DTMCreate,Description) Values(@GroupCode,GETDATE(),'$deskripsi'); ";
    $keywrd='';
    $deskripsi='';	  
	$result=mssql_query($query); }
  
  if(isset($_GET['id'])) {
    $keywrd=trim($_GET['id']);
	$query="Select * From m_GroupRepairHeader Where groupID='$keywrd'";
	$result=mssql_query($query);
	while($rowfetch=mssql_fetch_array($result)) {
	  $deskripsi=$rowfetch[2];
	}
	mssql_free_result($result);	
  }

  $design='';
  $design=$design.'<div class="w3-container">';

  $design=$design.' <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Repair Group Registration</h2>';
  $design=$design.' <form id="fRegGroup" method="post">';		
  if($_SESSION["allowInsert"]==1) {
    if($keywrd != '') {
	  $design=$design.'   <input type="hidden" name="fmode" value="update">';  
      $design=$design.'   <label class="w3-text-teal">Group ID</label>';
      $design=$design.'   <input class="w3-input w3-light-grey" type="text" Readonly name="id" value='.$keywrd.'>'; }
    else { $design=$design.'   <input type="hidden" name="fmode" value="new">';  }
  }	
  
  $design=$design.'   <div class="height-10"></div>';
  $design=$design.'   <label class="w3-text-teal">Group Description</label>';  
  $design=$design.'   <input class="w3-input w3-border" type="text" name="deskripsi" maxlength="30" style="text-transform:uppercase;" required value='.$deskripsi.'>'; 	
  $design=$design.'   <div class="height-20"></div>';
  if(isset($_GET['id'])) { $design=$design.'   <input type="submit" class="w3-btn w3-blue" name="register" value="Update" />';
    $design=$design.'   <input type="button" onclick="discharge()" class="w3-btn w3-grey" name="batal" value="Discharge" />';
  }
  else { 
    $design=$design.'  <button type="submit" class="w3-btn w3-pink" value="save_AddNew" name="save_AddNew"><i class="fa fa-plus-circle"></i> Save And Add New</button>'; }
  $design=$design.' </form>';  

  $query="Select a.*, c.completeName From m_GroupRepairHeader a 
          Inner Join m_GroupRepair b On b.groupID=a.groupID
          Inner Join m_Employee c On c.empRegID=b.empRegID 
		  Order By groupID, c.completeName";
  $result=mssql_query($query);
  
  
  $design=$design.' <div class="height-10"></div>';
  $design=$design.' <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc">Registered Repair Group</h2>';
  $design=$design.'<div class="w3-responsive w3-animate-opacity">';  
  $design=$design.' <table class="w3-table w3-border w3-bordered" style="font-size:12px">';
  $design=$design.' <thead><tr style="text-transform:uppercase">';
  $design=$design.'  <th>Index</th>'; 
  $design=$design.'  <th>Group ID</th>';  
  $design=$design.'  <th>Description</th>';  
  $design=$design.'  <th>Employee Name</th>';    
  $design=$design.'  <th></th>'; 
  $design=$design.'  <th></th>'; 
  $design=$design.'  <th></th>';  
  $design=$design.' </tr></thead><tbody>';
  if(mssql_num_rows($result) <= 0) { $design=$design.'<tr><td colspan="7" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>';  }	  	
  
  $index=0;
  $groupID='*'; 
  while($arr=mssql_fetch_array($result)) {
	if($groupID != $arr[0]) {
	  $groupID=$arr[0];
	  $index=0;
	  
	  $design=$design.'<tr class="w3-light-grey">';
	  $design=$design.' <td>&nbsp;</td>';
      $design=$design.' <td>'.$arr[0].'</td>';
	  $design=$design.' <td>'.$arr[2].'</td>';	  
      $design=$design.' <td>&nbsp;</td>'; 	  
	  if($_SESSION["allowDelete"]==1) { $design=$design.'<td><a onclick=dodelete("'.$arr[0].'") class="w3-btn w3-red" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Delete</a></td>'; }
	  else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }
      if($_SESSION["allowUpdate"]==1) { $design=$design.'<td><a onclick=opendetail("'.$arr[0].'")  class="w3-btn w3-yellow" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Edit Profile</a></td>'; }
	  else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }
	  if($_SESSION["allowInsert"]==1 && $_SESSION["allowUpdate"]==1) { $design=$design.'<td><a onclick=manage_team("'.$arr[0].'") class="w3-btn w3-blue" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Setup Team</a></td>'; }	
      else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }																					

      $design=$design.' </tr>';	  
    }
	
	$index++;
	$design=$design.'<tr>
	                   <td>'.$index.'.</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>'.$arr["completeName"].'</td>
					   <td>&nbsp;</td>					  
					   <td>&nbsp;</td>
					   <td>&nbsp;</td></tr>';

/*	$design=$design.'<tr>';
	if($_SESSION["allowDelete"]==1) { $design=$design.'<td><a onclick=dodelete("'.$arr[0].'") style="cursor: pointer;"><i class="fa fa-trash"></i></a></td>'; }
	else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }
    if($_SESSION["allowUpdate"]==1) { $design=$design.'<td><a onclick=opendetail("'.$arr[0].'") style="cursor: pointer;" title="Description Update"><i class="fa fa-pencil-square"></i></a></td>'; }
	else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }
	if($_SESSION["allowInsert"]==1 && $_SESSION["allowUpdate"]==1) { $design=$design.'<td><a onclick=manage_team("'.$arr[0].'") 
		                                                                               style="cursor: pointer;" title="Setup Person">
																				        <i class="fa fa-users"></i></a></td>'; }	
    else { $design=$design.'<td><i class="fa fa-lock"></i></a></td>'; }																					

    $design=$design.' <td>'.$arr[0].'</td>';
	$design=$design.' <td>'.$arr[2].'</td></tr>'; */
  }		
  $design=$design.'</tbody></table>'; 
  $design=$design.'</div>';	
  $design=$design.'</div>';	
  echo $design;  
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../asset/js/sweetalert2.min.js"></script> 
<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegGroup").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("reg_group.php", formValues, function(data){ $("#result").html(data); });
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
	  $("#result").load("drop-group.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') { swal('Cancelled','Selected record is safe :)','error' )}
	  })	
  }  
  
  function discharge() { $url="/e-imp/personal_data/?show=gr_rp";  location.replace($url); } 
  function opendetail(param) { $("#result").load("reg_group.php?id="+param); }  
  function manage_team(param) { $("#result").load("setup_team.php?id="+param); }  
</script>     