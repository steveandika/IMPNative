<script language="php">	    	
  include("../asset/libs/db.php");	
  
  if(isset($_GET['id'])) { 
	$keywrd=$_GET['id']; 
		  
	$query="Select *, FORMAT(DTMIn, 'yyyy-MM-dd') As dateIn, FORMAT(dateOfBirth, 'yyyy-MM-dd') As TglLahir From m_Employee Where empRegID='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);
	  $nama=$row[1];
	  $ktp=$row[2];
	  $sim=$row[3];
	  $homeph=$row[4];
	  $handphone=$row[5];
	  $tempat_lahir=$row[6];
	  $tanggal_lahir=$row['TglLahir'];
	  $tanggal_join=$row['dateIn'];
	  $initial=$row[9];
	  $current=$row[10];
	  $isResign=$row[12]; 
	  $homeaddr=$row[13]; 
	  $locationid=$row[14]; }
  mssql_free_result($result); }
  
  $design='';  
  if(isset($_GET['id'])) {
	$design .= '<div class="w3-container">';	
    $design .= ' <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc">Function Log</h2>';  	
    $design .= ' <div class="w3-responsive">';
    $design .= '  <table class="w3-table-all">';
    $design .= '   <tr>';
    $design .= '    <th>Function Name</th>';
    $design .= '    <th>Log Date</th>';
    $design .= '  </tr>';
	$query="Select a.*, b.Description, FORMAT(DTMLog, 'yyyy-MM-dd') As DateLog ";
	$query=$query."From m_EmployeeFunctionLog a Inner Join m_EmployeeFunction b On b.functionID=a.currentFunction Where empRegID='$keywrd'";
	$result=mssql_query($query);
	while($rows=mssql_fetch_array($result)) {
	  $design .= '<tr>';
	  $design .= ' <td>'.$rows[3].'</td>';
	  $design .= ' <td>'.$rows[4].'</td>';
	  $design .= '</tr>';
	}
    $design .= '  </table>'; 
	$design .= ' </div>';
    $design .= '</div>';
	$design .= '<div class="w3-container">';	
    $design .= ' <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Employee Form</h2>';  	
	$design .= ' <form id="fRegEmp" method="post" style="max-height:370px;overflow-y:scroll" id="style4">';		
  }
  else
  {
	$design .= '<div class="w3-container">';	  
    $design .= ' <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Employee Form</h2>';  	  
	$design .= ' <form id="fRegEmp" method="post" style="max-height:580px;overflow-y:scroll" id="style4">';		  
  }	  
  
  
  $design .= '  <input type="hidden" name="empregid" value='.$keywrd.'>';
  $design .= '  <div class="w3-container">';

  $design .= '   <label>Employee Name</label>';
  $design .= '   <input class="style-input style-border" type="text" name="empname" maxlength="100" style="text-transform:uppercase;" required value="'.$nama.'" />'; 	
  $design .= '   <div class="height-5"></div>';		

  $design .= '   <label>Home Address</label>';
  $design .= '   <input class="style-input style-border" type="text" name="homeaddr" maxlength="80" style="text-transform:uppercase;" required value="'.$homeaddr.'" />'; 			
  $design .= '   <div class="height-5"></div>';
  
  $design .= '   <div class="height-10"></div>';  
  $design .= '   <label>Citizen Number</label>';		
  $design .= '   <input class="style-input style-border" type="text" name="noktp" maxlength="20" style="text-transform:uppercase;" required value="'.$ktp.'" />'; 		
  $design .= '   <div class="height-5"></div>';

  $design .= '   <label>Driving License Number</label>';
  $design .= '   <input class="style-input style-border" type="text" name="nosim" maxlength="20" value="'.$sim.'" />'; 		
  $design .= '   <div class="height-5"></div>';
  
  $design .= '   <label>Phone Number (Home)</label>';
  $design .= '   <input class="style-input style-border" type="text" name="phone_home" maxlength="20" value="'.$homeph.'" />'; 				
  $design .= '   <div class="height-5"></div>';
  
  $design .= '   <label>Mobile Phone Number</label>';
  $design .= '   <input class="style-input style-border" type="text" name="handphone" maxlength="20" value="'.$handphone.'" />'; 				
  $design .= '   <div class="height-5"></div>';

  $design .= '   <label>Birth Date</label>';
  $design .= '   <input class="style-input style-border" type="text" id="fDate" required name="birth_date" title="Year-Month-Date" value="'.$tanggal_lahir.'"  onKeyUp=dateSeparator("fDate") />'; 				
  $design .= '   <div class="height-5"></div>';

  $design .= '   <label>Birth Place</label>';
  $design .= '   <input class="style-input style-border" type="text" name="birth_place" maxlength="80" style="text-transform:uppercase;" required value="'.$tempat_lahir.'" />'; 					
  $design .= '   <div class="height-5"></div>';
  
  $design .= '   <label>Date In Company</label>';
  $design .= '   <input class="style-input style-border" type="text" id="fDate" required name="datein" title="Year-Month-Date" value="'.$tanggal_join.'"  onKeyUp=dateSeparator("fDate") />'; 				
  $design .= '   <div class="height-5"></div>';
  
  $design .= '   <label>Initial Function</label>';
  if(!isset($_GET['id'])) {
    $design .= '   <select name="initial" class="style-select">';
    $query="Select * From m_EmployeeFunction Order By Description";
    $result=mssql_query($query);
    while($rows=mssql_fetch_array($result)) {
      $design .= '<option value='.$rows[1].'>&nbsp;&nbsp;'.$rows[0].'</option>'; }
    mssql_free_result($result);
    $design .= '  </select>'; }    
  else {  
    $query="Select * From m_EmployeeFunction Where functionID=".$initial;
    $result=mssql_query($query);  
	if(mssql_num_rows($result) > 0) {
	  $row=mssql_fetch_array($result);	
	  $design .= ' <input class="style-input style-border" type="text" style="text-transform:uppercase;" readonly value="'.$row[0].'"> '; }
	else { $design .= ' <input class="style-input style-border" type="text" style="text-transform:uppercase;" readonly /> '; }
    mssql_free_result($result); }
  $design .= '   <div class="height-5"></div>';
   
  if(isset($_GET['id'])) {
    $design .= '   <label>Current Function</label>';
    $design .= '   <select name="current" class="style-select">';
    $query="Select * From m_EmployeeFunction Order By Description";
    $result=mssql_query($query);
    while($rows=mssql_fetch_array($result)) {
      if($rows[1] ==$current) { $design .= '<option selected value='.$rows[1].'>&nbsp;&nbsp;'.$rows[0].'</option>'; }
	  else { $design .= '<option value='.$rows[1].'>&nbsp;&nbsp;'.$rows[0].'</option>'; } 
    }
    mssql_free_result($result);
    $design .= '  </select>'; }
  
  $design .= '   <label>Work Location</label>';
  $design .= '   <select name="location" class="style-select">';	  
  $query="Select * From m_Location Order By locationDesc";
  $result=mssql_query($query);
  while($rows=mssql_fetch_array($result)) {
    if($rows[0] == $locationid) { $design .= '<option selected value='.$rows[0].'>&nbsp;'.$rows[1].'</option>'; }
	else { $design .= '<option value='.$rows[0].'>&nbsp;'.$rows[1].'</option>'; }
  }	  
  $design .= '   </select>';
  $result=mssql_query($query);   	
	
  $design .= '   <div class="height-20"></div>';  		
  $design .= '   <input type=hidden name="whatToDo" value="" />';
  if(isset($_GET['id'])) { $design .= '  <input type="submit" class="w3-button w3-blue w3-round-small" name="register" value="Update" />';     
    $design .= '   <input type="button" onclick="discharge()" class="w3-button w3-grey w3-round-small" name="batal" value="Discharge" />';}
  else { 
    $design .= '  <button type="submit" class="w3-button w3-pink w3-round-small" value="save_AddNew" name="save_AddNew" 
	                   onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-plus-circle"></i> Save And Add New</button>';
	                    
	$design .= '  <button type="submit" class="w3-button w3-orange w3-round-small" name="save_viewlist" value="save_View"
                       onclick="this.form.whatToDo.value = this.value;"><i class="fa fa-bookmark-o"></i> Save And View List</button>'; }
  $design .= ' </div></form></div><div class="height-10"></div>';  		
  //$design .= '</div>';
  
  echo $design;
</script>

<script type="text/javascript">
  $(document).ready(function(){	  
    $("#fRegEmp").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("emp_dosave.php", formValues, function(data){ $("#result").html(data); });
    });	  
  });  
  
  function discharge() { $url="/e-imp/personal_data/?show=ltem";  location.replace($url); } 
</script>   
