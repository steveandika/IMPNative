<?php
  session_start();
  include ("../asset/libs/db.php");
    
  if(isset($_POST['workshopID'])) {
	$keywrd=strtoupper($_POST['workshopID']);
	$query="";
    $query=$query."If Exists(Select * From m_Location Where locationID='$keywrd') Begin";
	$query=$query."  Update m_Location Set locationDesc='".strtoupper($_POST['workshopLocation'])."' Where locationID='$keywrd'; ";
    $query=$query."  Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	$query=$query."  Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Workshop Detail ','$keywrd')); ";
	$query=$query."End Else Begin";
	$query=$query."      Insert Into m_Location(locationID, locationDesc) ";
	$query=$query."      Values('".$keywrd."', '".strtoupper($_POST['workshopLocation'])."'); ";
	$query=$query."    End;";
	$result=mssql_query($query);
	echo '<script>swal("Success","Record has been saved.");</script>';
  }    
 
  
  if(($_SESSION['allowInsert'] == 1) || ($_SESSION['allowUpdate'] == 1)) {			 
	$workshopid='%';
	$deskripsi='';
		   
	if(isset($_GET['id']) && !isset($_POST['workshopID'])) { 
	  $workshopid = $_GET['id']; 
	  $query="Select locationDesc From m_Location Where locationID='$workshopid' ";
	  $result=mssql_query($query);
 	  if(mssql_num_rows($result) ==1) 
	  {
	    $row=mssql_fetch_array($result);
		$deskripsi=$row[0]; 
	  } 
	  mssql_free_result($result); 
	
      $design= '<div class="w3-container w3-animate-zoom" style="max-height:400px;overflow-y:scroll" id="style-4"> 
	             <h3 style="padding:10px 0 10px 0;border-bottom:1px solid #b3b6b7;color:#b3b6b7;margin-top:0">&nbsp;&nbsp;Workshop Registration</h3>
	             <form id="fRegWorkshop" method="post">
		   	      <div class="w3-row-padding">
				   <div class="w3-half"> 
 			        <label>Workshop Identifier</label>';
				
	  if($workshopid != '%') { $design=$design.'<input class="style-input" type="text" name="workshopID" style="text-transform:uppercase;" readonly value="'.$workshopid.'" />'; }
	  else { $design=$design.'<input class="style-input style-border" type="text" name="workshopID" maxlength="4" style="text-transform:uppercase;" Required />'; }
			 
      $design=$design.'   </div>
	                      <div class="w3-half"></div>
		 			     </div>	
	                     <div class="height-5"></div>
                         <div class="w3-row-padding">
                          <div class="w3-half">
                           <label>Short Description</label>
                           <input class="style-input style-border" type="text" name="workshopLocation" maxlength="100" style="text-transform:uppercase;" required value="'.$deskripsi.'" />
                          </div>
                          <div class="w3-half"></div>
                         </div>
		                 <div class="height-10"></div>
					     <input type="submit" class="w3-button w3-blue w3-round-small" name="register" value="Save" />
						 &nbsp;<input type="button" onclick="discharge()" class="w3-button w3-black w3-round-small" name="batal" value="Discharge" />
					    </form>
                       </div>
					   <div class="height-20"></div>';					  
      echo $design; 
	}
  }  
  
  $design='<div class="w3-container">
            <h3 style="padding:10px 0 10px 0;border-bottom:1px solid #b3b6b7;color:#b3b6b7;margin-top:0">&nbsp;&nbsp;Registered Workshop</h3>
            <div class="height-10"></div>
            <div class="w3-responsive">
             <table class="w3-table-all" style="max-height:400px">
              <tr>
			    <th>Workshop ID</th>
				<th>Location Description</th>
				<th colspan="2" style="text-align:center">Action</th>
			  </tr>';

  $query="SELECT * FROM m_Location ORDER BY LocationDesc";
  $result=mssql_query($query);
  
  if(mssql_num_rows($result) <= 0) { $design=$design.'<tr><td colspan="4" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; }
  
  while($rows=mssql_fetch_array($result)) 
  {
    $design=$design.'<tr>';
	$design=$design.' <td>'.$rows[0].'</td>';     
	$design=$design.' <td>'.$rows[1].'</td>';	
	if($_SESSION['allowDelete'] ==1) { $design=$design.' <td style="text-align:center"><a onclick=confirmDelete("'.$rows[0].'") class="w3-btn w3-red w3-round-medium" style="line-height:10px">Delete</a></td>'; }
	else { $design=$design.' <td><i class="fa fa-lock"></i></a></td>'; }
	if($_SESSION['allowUpdate'] ==1) { $design=$design." <td style='text-align:center'><a href='?show=wrk&id=$rows[0]' class='w3-btn w3-pink w3-round-medium' style='line-height:10px'>Edit</a></td>"; }
	else { $design=$design.' <td><i class="fa fa-lock"></i></a></td>'; }
    $design=$design.'</tr>'; 
  }
  
  mssql_free_result($result);

  $design=$design.'   </table>';
  $design=$design.'  </div>
                     <div class="height-10"></div>
					</div>';
  echo $design;

  
  mssql_close($dbSQL);
?>

<script type="text/javascript">
  function confirmDelete(privVariable) {
    swal({
      title: 'Are you sure?',
      text: 'You will not be able to recover selected record! ',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, Delete',
	  cancelButtonText: 'No, Cancel',
	  confirmButtonClass: 'btn btn-sucess',
	  cancelButtonClass: 'btn btn-danger',
	  buttonsStyling: true
	}).then(function () {
	  $("#result").load("doremove_workshop.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled', 'Selected record is safe :)', 'error') }
	  })	
  }  
  
  function discharge() { $url="/e-imp/master.data/?show=wrk";  location.replace($url); }     
</script>