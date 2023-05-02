<script language="php">
  session_start();
  include("../asset/libs/db.php");	

  echo '<div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Registered Function</h2>';
  
  if(isset($_GET['deskripsi'])) {
    $keywrd=LTRIM($_GET['deskripsi']);
	
	$query="Select * From m_EmployeeFunction Where Description='$keywrd'";
	$result=mssql_query($query);
	if(mssql_num_rows($result) >=1) {
	  $row=mssql_fetch_array($result);
	  $deskripsi=$row[0]; }
    mssql_free_result($result); }
	    
  $design=' ';  
  $design=$design.' <div class="w3-responsive w3-animate-zoom" style="max-height:480px;overflow-y:scroll" id="style-4">';
  $design=$design.' <table class="w3-table-all"><thead>                    
                     <tr>
					  <th>Index</th>
                      <th>Function Name</th>
                      <th colspan="2" style="text-align:center">Action</th>
                      <th>Used</th>
                     </tr></thead><tbody>';
  
  $query="Select * From m_EmployeeFunction Order By Description";
  $result=mssql_query($query);
  $rowIndex=0;
  while($rows=mssql_fetch_array($result)) {
	$totalused=0;
	$subqry="Select Count(currentFunction) AS totalUsed From m_EmployeeFunction a
	         Inner Join m_Employee b On b.currentFunction=a.FunctionID 
			 Where a.FunctionID=".$rows[1]."
			 Group By a.FunctionID ";
	$subres=mssql_query($subqry);
    if(mssql_num_rows($subres) > 0) {
	  $subrow=mssql_fetch_array($subres);	
	  $totalused=$subrow['totalUsed'];
	}
	mssql_free_result($subres);
    		  
	$rowIndex++;
    $design=$design.'<tr>
	                   <td>'.$rowIndex.'.</td>';
    $design=$design.' <td data-label="Function">'.$rows[0].'</td>';					   
    if($_SESSION['allowDelete'] ==1) { $design=$design.' <td style="text-align:center"><a onclick=confirmDelete("'.$rows[1].'") class="w3-btn w3-red w3-round-medium" 
	                                                         style="line-height:10px">Delete</a></td>'; }
	else { $design=$design.' <td style="text-align:center"><i class="fa fa-lock"></i></td>'; }
    if($_SESSION['allowUpdate'] ==1) { $design=$design.' <td style="text-align:center"><a onclick=opendetail("'.$rows[1].'") class="w3-btn w3-blue w3-round-medium" 
	                                                          style="line-height:10px">Edit</a></td>'; }
	else { $design=$design.' <td style="text-align:center"><i class="fa fa-lock"></i></td>'; }
															 
    $design=$design.' <td><a onclick=linkedrec("'.$rows[1].'") class="w3-btn w3-amber w3-round-medium" style="line-height:10px">'.$totalused.'</a></td>';
	
    $design=$design.'</tr>';	
  }
  $design=$design.' </tbody></table></div>';
  $design=$design.'</div>';
  echo $design;

</script>

<script type="text/javascript">
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
	  $("#result").load("doremove-function.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {swal('Cancelled', 'Selected record is safe :)', 'error' ) }
	  })	
  }  
  
  function opendetail(param) { $("#result").load("manage-function.php?id="+param); }
  function domanage(urlVariable) { $("#result").load(urlVariable); }
  function linkedrec(param) { $("#result").load("emp_list.php?id="+param); }
</script>