<script language="php">
  session_start();  
  include("../asset/libs/db.php");
	    
  $keywrd = '';
  if(isset($_GET['id'])) { $keywrd = strtoupper(trim($_GET['id'])); }
  
  
  $design='<div class="height-10"></div>
            <div class="w3-row-padding">
			  <div class="w3-half">
			    
				<div class="w3-row-padding">
				  <div class="w3-third">
				    <a href="?show=vcust&new=1" class="w3-btn w3-blue"><i class="fa fa-file"></i>&nbsp;New Customer</a>
				  </div>
				  <form id="cari" method="get"> 
				  <div class="w3-third">
  				    <input type="text" name="id" placeholder="Search.." class="w3-input w3-border" style="text-transform:uppercase;" value='.$keywrd.' >            
				  </div>
				  <div class="w3-third">
				    <button type="submit" class="w3-button w3-grey" ><i class="fa fa-search"></i></button> 
				  </div>
                  </form> 				  
				</div>
				
			  </div>
			  <div class="w3-half"></div>
			</div>';

  $design=$design.' <div class="height-10"></div>';
  $design=$design.' <div class="w3-responsive">';
  $design=$design.'  <table class="w3-table w3-bordered">';
  $design=$design.'   <thead><tr>';
  $design=$design.'    <th>Customer Name</th>';
  $design=$design.'    <th>Short Name</th>';
  $design=$design.'    <th>Exportir</th>';
  $design=$design.'    <th>Importir</th>';
  $design=$design.'    <th>Log. Party</th>';
  $design=$design.'    <th>M L O</th>';
  $design=$design.'    <th>Feeder</th>';
  $design=$design.'    <th>Supplier</th>';
  $design=$design.'    <th>Others</th>';
  $design=$design.'    <th colspan="2" style="text-align:center">Action</th>'; 

  $design=$design.'  </tr></thead><tbody>';
  
  if($keywrd != '%') { 
    $query="Select * From m_Customer Where custRegID='$keywrd' or completeName Like '".'%'.$keywrd.'%'."' Order By completeName"; 
  }  	
  else { $query="Select * From m_Customer Order By completeName"; }	
  $result = mssql_query($query);	   
  while($row=mssql_fetch_array($result)) {	
	$design=$design.'<tr>';
	$design=$design.' <td style="border-right:1px solid #ddd">'.$row[1].'</td>'; 
	$design=$design.' <td style="border-right:1px solid #ddd">'.$row["shortName"].'</td>'; 
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';
	if($row[10] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';
	if($row[11] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';
	if($row[12] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';		
	if($row[13] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';		
	if($row[14] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';		
	if($row[15] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';
	$design=$design.' <td style="text-align:center;border-right:1px solid #ddd">';		
	if($row[16] == 1) { $design=$design.'<i class="fa fa-check"></i>'; }
	else { $design=$design.'&nbsp;'; }
	$design=$design.' </td>';	
	if($_SESSION['allowDelete'] == 1) {	$design=$design.' <td style="text-align:center"><a onclick=confirmDelete("'.$row[0].'") class="w3-btn w3-red w3-round-medium" style="line-height:10px">Delete</a></td>'; }
	else { $design=$design.' <td style="text-align:center;border-right:1px solid #ddd"><i class="fa fa-trash"></i></td>'; }
	if($_SESSION['allowUpdate'] == 1) {	$design=$design.' <td style="text-align:center"><a href="?show=vcust&new=1&id='.$row[0].'" class="w3-btn w3-pink w3-round-medium" style="line-height:10px">Edit</a></td>'; }
	else { $design=$design.' <td style="text-align:center;border-right:1px solid #ddd"><i class="fa fa-folder-open-o"></i></td>'; }	
	$design=$design.'</tr>';	}	
	    
  mssql_free_result($result);
  $design=$design.' </tbody></table>';
  $design=$design.'</div><div class="height-10"></div>';
  
  echo $design;	  
  
  mssql_close($dbSQL);
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#cari").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("customer.php", formValues, function(data){ $("#result").html(data);  });
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
	  $("#result").load("doremove_cust.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') { swal('Cancelled', 'Selected record is safe :)', 'error' )}
	  })	
  }  
  
  function opendetail(urlVariable) { $().load("manage_cust.php?show=vcust&new=1&id="+urlVariable); }
</script>