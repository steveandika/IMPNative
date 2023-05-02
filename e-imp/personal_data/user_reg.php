<script language="php">
  include("../asset/libs/db.php");
  
  if(isset($_GET['id'])) { 
	$keywrd=$_GET['id']; 
		  
    $query="Select a.userID, a.isActive, alInsert, alDelete, alEdit, a.locationID, b.completeName ";
	$query=$query."From userProfile a Inner Join m_Employee b On b.empRegID=a.userID ";
	$query=$query."Where a.userID Like '$keywrd' Order By b.completeName";  
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);
	  $userid=$row[0];
	  $status=$row[1];
	  $alinsert=$row[2];
	  $aldelete=$row[3];
	  $aledit=$row[4];
	  $locationid=$row[5];
	  $employee=$row[6]; }
  mssql_free_result($result); }
  
  $design='';
  $design=$design.' <form id="fRegUser" method="post" >';		
  $design=$design.'  <input type="hidden" name="id" value='.$keywrd.'>';
  $design=$design.'  <div class="w3-container">';
  $design=$design.'   <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">User Registration</h2>';
  if(isset($_GET['id'])) {
    $design=$design.'   <label class="w3-text-teal">User ID</label>';
    $design=$design.'   <input class="w3-input w3-light-grey" type="text" style="text-transform:uppercase;" readonly value="'.$keywrd.'"> '; 
	$design=$design.'   <div class="height-10"></div>';
    $design=$design.'   <label class="w3-text-teal">Employee Name</label>';  	
    $design=$design.'   <input class="w3-input w3-light-grey" type="text" style="text-transform:uppercase;" readonly value="'.$employee.'"> '; 
	$design=$design.'   <div class="height-10"></div>';	}
  else {
    $design=$design.'   <select name="employee" class="w3-select w3-border">';	  	
	$query="Select empRegID, completeName From m_Employee 
	        Where empRegID Not In (Select userID As empRegID From userProfile) Order By completeName";
	$result=mssql_query($query);
	while($rows=mssql_fetch_array($result)) { $design=$design.'<option value='.$rows[0].'>&nbsp;'.$rows[1].'</option>'; }
	$design=$design.'   </select>';
	$design=$design.'   <div class="height-10"></div>';
	mssql_free_result($result); }
 /* 
  $design=$design.'   <label class="w3-text-teal">Work Location</label>';
  $design=$design.'   <select name="location" class="w3-select w3-border">';	  
  $query="Select * From m_Location Order By locationDesc";
  $result=mssql_query($query);
  while($rows=mssql_fetch_array($result)) {
    if($rows[0] == $locationid) { $design=$design.'<option selected value='.$rows[0].'>&nbsp;'.$rows[1].'</option>'; }
	else { $design=$design.'<option value='.$rows[0].'>&nbsp;'.$rows[1].'</option>'; }
  }	  
  $design=$design.'   </select>';
  $design=$design.'   <div class="height-10"></div>';
  $result=mssql_query($query);    
  */
  $design=$design.'   <label class="w3-text-teal">Status</label>';
  $design=$design.'   <p style="line-height: 21px;" >';
  if($status != 1) { $design=$design.'<input class="w3-check" type="checkbox" name="isAktif">&nbsp<label class="w3-text-teal">Active</label>'; }
  else {  $design=$design.'<input class="w3-check" type="checkbox" Checked="Checked" name="isAktif">&nbsp<label class="w3-text-teal">Active</label>'; }

  $design=$design.'   <label class="w3-text-teal">Role</label>';
  $design=$design.'   <p style="line-height: 21px;" >';  
  if($alinsert != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="alInsert">&nbsp<label class="w3-text-teal">Allow Insert</label>&nbsp;&nbsp;'; }
  else {	$design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="alInsert">&nbsp<label class="w3-text-teal">Allow Insert</label>&nbsp;&nbsp;'; }	
  
  $design=$design.'   <p style="line-height: 21px;" >';  
  if($aldelete != 1) { $design=$design.'      <input class="w3-check" type="checkbox" name="alDelete">&nbsp<label class="w3-text-teal">Allow Delete</label></p>'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" Checked="Checked" name="alDelete">&nbsp<label class="w3-text-teal">Allow Delete</label></p>'; }
  
  $design=$design.'   <p style="line-height: 21px;" >';  
  if($aledit != 1) { $design=$design.'   <input class="w3-check" type="checkbox" name="alUpdate">&nbsp<label class="w3-text-teal">Allow Update</label></p>'; }
  else { $design=$design.'   <input class="w3-check" type="checkbox" Checked="Checked" name="alUpdate">&nbsp<label class="w3-text-teal">Allow Update</label></p>'; }
  
  $design=$design.'   <div class="height-10"></div>';
  $design=$design.'   <input type=hidden name="whatToDo" value="" />';  
  if(isset($_GET['id'])) { $design=$design.'  <input type="submit" class="w3-button w3-blue w3-round-small" name="register" value="Update" />'; }
  else { 
    $design=$design.'  <button type="submit" class="w3-button w3-pink w3-round-small" value="save_AddNew" name="save_AddNew" 
	                   onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-plus-circle"></i> Save And Add New</button>';
	                    
	$design=$design.'  <button type="submit" class="w3-button w3-orange w3-round-small" name="save_viewlist" value="save_View"
                       onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-bookmark-o"></i> Save And View List</button>'; }
  $design=$design.' </div></form>';  		
  //$design=$design.'  <input type="submit" class="w3-button w3-orange" name="register" value="Register" />';
  $design=$design.'  </div>';
  $design=$design.' </form>';
  
  echo $design;  
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegUser").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("user_dosave.php", formValues, function(data){ $("#result").html(data); });
    });
	  
  });  
</script> 

    