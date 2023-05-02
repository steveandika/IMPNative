<script language="php">
  include ("../asset/libs/db.php");
  
  function checkMenu($userid, $param) {
    $query="Select * From userMenuProfile Where userID='$userid' And menuTag=".$param;	
    $result=mssql_query($query);
    if(mssql_num_rows($result) >= 1) { $return = 'OK'; }
    else { $return = 'Failed'; }  
    mssql_free_result($result);	  
	
    return $return; }

  $keywrd=trim($_GET['id']);

  $query="Select a.userID, b.completeName ";
  $query=$query."From userProfile a Inner Join m_Employee b On b.empRegID=a.userID ";
  $query=$query."Where a.userID Like '$keywrd' Order By b.completeName"; 
  $result=mssql_query($query);
  if(mssql_num_rows($result) > 0) {
	$row=mssql_fetch_array($result);
	$employee=$row[1]; }
  mssql_free_result($result); 

  $design=$design.'<div class="w3-container w3-animate-zoom">
                    <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db;">Role Access Setup</h2>';

  $design=$design.' <form id="fRegMenu" method="post">';
  $design=$design.' <label class="w3-text-teal">User ID</label>';
  $design=$design.' <input class="w3-input w3-light-grey" type="text" style="text-transform:uppercase;" name="userid" readonly value="'.$keywrd.'"> '; 
  $design=$design.' <div class="height-5"></div>';
  $design=$design.' <label class="w3-text-teal">Employee Name</label>';  	
  $design=$design.' <input class="w3-input w3-light-grey" type="text" style="text-transform:uppercase;" readonly value="'.$employee.'"> '; 
  $design=$design.' <div class="height-10"></div>';
   
  $design=$design.' <div class="w3-responsive" style="max-height:380px;overflow-y:scroll" id="style-4">'; 
  $design=$design.' <table class="w3-table-all">';
  $design=$design.'  <thead>';
  $design=$design.'   <tr>';
  $design=$design.'     <th>Tag</th>';
  $design=$design.'     <th>Sub Menu Title</th>';
  $design=$design.'     <th>Default Set</th>';
  $design=$design.'   </tr>';
  $design=$design.'  </thead>';
  $design=$design.'  <tbody>';
  $design=$design.'   <tr>';
  $design=$design.'     <td style="text-align:left;background-color:#ff9800;color:#fff" colspan="3"> ';
  $design=$design.'      <i class="fa fa-angle-double-right"></i>&nbsp;<strong>PERSONAL DATA</strong></td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';
  $design=$design.'     <td>11</td>';
  $design=$design.'     <td>Employee Detail</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 11) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="employee_detail">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="employee_detail">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>12</td>';
  $design=$design.'     <td>User</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 12) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="user">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="user">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>14</td>';
  $design=$design.'     <td>Repair Group</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 14) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="group_repair">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="group_repair">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  
  $design=$design.'   <tr>';
  $design=$design.'     <td style="text-align:left;background-color:#ff9800;color:#fff" colspan="3">';
  $design=$design.'     <i class="fa fa-angle-double-right"></i>&nbsp;<strong>MASTER DATA</strong></td>';
  $design=$design.'   </tr>';  
  $design=$design.'   <tr>';  
  $design=$design.'     <td>15</td>';
  $design=$design.'     <td>Customer</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 15) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="customer">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="customer">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>16</td>';
  $design=$design.'     <td>Port</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 16) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="port">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="port">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>17</td>';
  $design=$design.'     <td>Vessel</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 17) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="vessel">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="vessel">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>18</td>';
  $design=$design.'     <td>Location</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 18) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="location">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="location">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  
  $design=$design.'   <tr>';  
  $design=$design.'     <td>19</td>';
  $design=$design.'     <td style="text-align:left">Price List</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 19) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="price_list">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="price_list">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  

  $design=$design.'   <tr>';
  $design=$design.'     <td style="text-align:left;background-color:#ff9800;color:#fff" colspan="3"> ';
  $design=$design.'      <i class="fa fa-angle-double-right"></i>&nbsp;<strong>MAINTENANCE and REPAIR</strong></td>';
  $design=$design.'   </tr>';
  $design=$design.'   <tr>';  
  $design=$design.'     <td>30</td>';
  $design=$design.'     <td>Gate In</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 30) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="gate_in">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="gate_in">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  
/*  $design=$design.'   <tr>';  
  $design=$design.'     <td>31</td>';
  $design=$design.'     <td>Container Header</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 31) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="container_header">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="container_header">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  */
  $design=$design.'   <tr>';  
  $design=$design.'     <td>32</td>';
  $design=$design.'     <td>EOR</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 32) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="eor">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="eor">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  
  $design=$design.'   <tr>';  
  $design=$design.'     <td>33</td>';
  $design=$design.'     <td>Container Photo</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 33) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="container_photo">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="container_photo">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  
  $design=$design.'   <tr>';  
  $design=$design.'     <td>34</td>';
  $design=$design.'     <td>Gate Out</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 34) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="gate_out">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="gate_out">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';  
  $design=$design.'   <tr>';  
  $design=$design.'     <td>35</td>';
  $design=$design.'     <td>Estimate Of Repair - Approval</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 35) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="eorapproval">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="eorapproval">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';    
  $design=$design.'   <tr>';  
  $design=$design.'     <td>36</td>';
  $design=$design.'     <td>Cleaning</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 36) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="cleaning">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="cleaning">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';     
  $design=$design.'   <tr>';  
  $design=$design.'     <td>37</td>';
  $design=$design.'     <td>Registration Finish Repair</td>';
  $design=$design.'     <td>';  
  if(checkMenu($keywrd, 37) == 'OK') { $design=$design.'      <input class="w3-check" type="checkbox" Checked name="finishrep">'; }
  else { $design=$design.'      <input class="w3-check" type="checkbox" name="finishrep">'; }
  $design=$design.'     </td>';
  $design=$design.'   </tr>';     

  $design=$design.'  </tbody>';  
  $design=$design.' </table>';
  $design=$design.' </div>';
  
  $design=$design.' <div class="height-20"></div>';
  $design=$design.' <input type="submit" name="submit" class="w3-btn w3-blue" value="Register">';
  $design=$design.' </form>';
  $design=$design.'</div>';
  
  echo $design;
</script>	 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegMenu").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("save-set-menu.php", formValues, function(data){ $("#result").html(data); });
    });	  
  });  
</script> 