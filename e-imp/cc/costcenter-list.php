<?php
  session_start();
  
  if(isset($_GET['update'])) 
  {
	if($_GET['update'] == 0) { echo '<script>swal("","Update was failed");</script>'; }
	else { echo '<script>swal("","Current record has been updated");</script>'; }	
  }	  
  if(isset($_GET['append']))
  {
	if($_GET['append'] == 0) { echo '<script>swal("","Given value was failed insert into Master Cost Center");</script>'; }
	else { echo '<script>swal("","Given value has been append into Master Cost Center");</script>'; }	
  }
  
  include("../asset/libs/db.php");
  
  echo '<div class="form-main w3-round-medium display-form-shadow">
         <div class="form-header">Cost Center List</div> 
         <div class="height-10"></div>
		 
         <div class="w3-container"> 
		  
          <div class="flex-container">
           <a href="?show=list&act=adding" class="w3-button w3-light-grey w3-round-small main-button_light-blue"><i class="fa fa-file"></i>&nbsp;<span class="navbar-label">New Cost Center</span></a>
  	      </div>
		 <div class="height-10"></div>
         <div class="w3-responsive">
          <table class="w3-table w3-bordered">
           <thead>
		    <tr>
		     <th>Index</th>
		 	 <th>Cost Center Code</th>
	 		 <th>Cost Center Name</th>
			 <th>Description</th>
			 <th colspan="2" style="text-align:center">Action</th>
		    </tr>
           </thead>
           <tbody>';
  
  $index = 0;  
  $sql = "Select * From m_CostCenter Order By ccCode; ";
  $rsl = mssql_query($sql);
  while($colArr = mssql_fetch_array($rsl)) {
    $index++;
    echo '<tr>	
	       <td>'.$index.'.'.'</td>
		   <td>'.$colArr["ccCode"].'</td>
		   <td>'.$colArr["ccName"].'</td>
		   <td>'.$colArr["ccDescription"].'<td>';
		   
	if($_SESSION['allowUpdate'] == 1) {echo ' <td style="text-align:center"><a href="?show=list&act=edit&id='.$colArr[0].'" class="w3-btn w3-pink w3-round-medium" style="line-height:10px">Edit</a></td>'; }
	else { echo ' <td style="text-align:center"><i class="fa fa-folder-open-o"></i></td>'; }			   
	
	echo '</tr>';
  }
  mssql_close($dbSQL);
  
  echo '   </tbody>
          </table>
		  <div class="height-10"></div>
         </div></div> <div class="height-10"></div>';
?>