<script language="php">
  session_start();
  include("../asset/libs/db.php");	
  
  if(isset($_GET['id'])) { 
	$keywrd=$_GET['id']; 
		  
	$query="Select * From m_EmployeeFunction Where functionID=".$keywrd;
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);
	  $deskripsi=$row[0]; }
  mssql_free_result($result); } 
  
  $design=$design.' <form id="fRegFunction" method="post">';	
  $design=$design.'  <input type="hidden" name="id" value='.$keywrd.'>';
  $design=$design.'  <div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db;font-weight:600">Function Registration</h2>';
  $design=$design.'   <label class="w3-text-teal">Function Name</label>';
  $design=$design.'   <input class="w3-input w3-border" type="text" name="deskripsi" maxlength="15" style="text-transform:uppercase;" required value="'.$deskripsi.'">'; 	
  $design=$design.'   <div class="height-5"></div>';		
  $design=$design.'   <input type=hidden name="whatToDo" value="" />';  
  if(isset($_GET['id'])) { $design=$design.'  <input type="submit" class="w3-btn w3-blue" name="register" value="Update" />';     
    $design=$design.'   <input type="button" onclick="discharge()" class="w3-btn w3-grey" name="batal" value="Discharge" />';}
  else {
    $design=$design.'  <button type="submit" class="w3-btn w3-pink" value="save_AddNew" name="save_AddNew" 
	                   onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-plus-circle"></i> Save And Add New</button>';
	                    
	$design=$design.'  <button type="submit" class="w3-btn w3-orange" name="save_viewlist" value="save_View"
                       onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-bookmark-o"></i> Save And View List</button>'; }	  
  //$design=$design.'  <input type="submit" class="w3-btn w3-blue" name="register" value="Register" />';
  $design=$design.' </form>';
  $design=$design.'</div>'; 
  
  echo $design;
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script type="text/javascript">
  $(document).ready(function(){	  
    $("#fRegFunction").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("save-query-function.php", formValues, function(data){ $("#result").html(data); });
    });	  
  });  

  function discharge() { $url="/e-imp/personal_data/?show=fcdpt";  location.replace($url); }   
</script>