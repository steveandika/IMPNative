<script language="php">
  session_start();
  include("../asset/libs/db.php");

  $keywrd = '';  
  if(isset($_GET['id'])) { $keywrd = strtoupper(trim($_GET['id'])); }
   
  echo '<div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Registered Employee</h2>';
  
  $design='';
  $design=$design.'<form id="cari" method="get" action="/e-imp/personal_data/?">';
  $design=$design.'<input type="hidden" name="show" value="ltem">';
  $design=$design.'<input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" value='.$keywrd.' >';
  $design=$design.'</form>';
  
  $design=$design.'<div class="clear height-10"></div>';	
  $design=$design.'<div class="w3-responsive w3-animate-zoom" style="max-height:460px;overflow-y:scroll" id="style-4">
                    <table class="w3-table-all">
                      <thead><tr>
					   <th>Index</th> 
                       <th>Employee Name</th>
                       <th>Function</th>
                       <th colspan="2" style="text-align:center">Action</th>
                      </tr></thead><tbody>';
  
  if($keywrd != '') { 
    $query="Select a.empRegID, a.completeName, b.Description, FORMAT(a.DTMIn,'yyyy-MM-dd') As dateIn, c.locationDesc 
	        From m_Employee a 
	        Left Join m_EmployeeFunction b On b.functionID=a.currentFunction 
			Left Join m_Location c On c.locationID=a.LocationID
	        Where empRegID='$keywrd' or completeName Like '".'%'.$keywrd.'%'."' 
	        Or b.Description Like '".'%'.$keywrd.'%'."' Order By c.locationDesc, b.Description,a.completeName"; }  		
  else { 
   $query="Select a.empRegID, a.completeName, b.Description, FORMAT(a.DTMIn,'yyyy-MM-dd') As dateIn, c.locationDesc  
           From m_Employee a
           Left Join m_EmployeeFunction b On b.functionID=a.currentFunction  
		   Left Join m_Location c On c.locationID=a.LocationID
		   Order By c.locationDesc, b.Description, completeName"; }	
  $result = mssql_query($query);	
  $totalRow=mssql_num_rows($result);  
  if(mssql_num_rows($result) <= 0) { $design=$design.'<tr><td colspan="5" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>';  }	  
  
/*  
  $Per_Page=20;
  $page=$_GET["Page"];
  if(!isset($_GET["Page"])) { $page=1; }
		
  $prev_page= $page-1;
  $next_page= $page+1;
  $page_start=($Per_Page*$page)-$Per_Page;
  if($totalRow<=$Per_Page) { $Num_Pages=1; }
  else { $Num_Pages=(int)($totalRow/$Per_Page) +1; }
  $Page_End=$Per_Page*$page;
  if($Page_End > $totalRow) { $Page_End=$totalRow; }  
*/
  
  $loc='*';
  $RowIndex=0;
  for($i=$page_start; $i<$totalRow; $i++) {
  //while($row=mssql_fetch_array($result)) {	
    if(mssql_result($result, $i, 'locationDesc') !=$loc) { 
	  $loc=mssql_result($result, $i, 'locationDesc');
	  $design=$design.'<tr><td colspan="5" style="letter-spacing:1px;text-align:left" class="w3-deep-orange">LOCATION: '.$loc.'</td></tr>';
      $RowIndex=0;	  
	}
	
/*    
	if($loc != $row["locationDesc"]) {
	  $loc=$row["locationDesc"];
	  $design=$design.'<tr><td colspan="5" style="letter-spacing:1px;text-align:left" class="w3-deep-orange">LOCATION: '.$loc.'</td></tr>';
	  $RowIndex=0;
	}
*/	
	$RowIndex++;
	$design=$design.'<tr>
	                   <td>'.$RowIndex.'.</td>
					   <td>'.mssql_result($result, $i, 'completeName').'</td>
	                   <td>'.mssql_result($result, $i, 'Description').'</td>';
	if($_SESSION['allowDelete'] == 1 && $_SESSION['uid'] != $row[0]) {	
	  $design=$design.' <td style="text-align:center"><a onclick=confirmDelete("'.mssql_result($result, $i, 'empRegID').'") class="w3-btn w3-red w3-round-medium" style="line-height:10px">Delete</a></td>'; }
	else { echo '<td style="text-align:center"><i class="fa fa-lock"></i></td>'; }	
	if($_SESSION['allowUpdate'] == 1) {	$design=$design.' <td style="text-align:center"><a onclick=opendetail("'.mssql_result($result, $i, 'empRegID').'") class="w3-btn w3-blue w3-round-medium" style="line-height:10px">Edit</a></td>'; }
    else { echo '<td style="text-align:center"><i class="fa fa-lock"></i></td>'; }													  
	$design=$design.' </tr>';
  }	

/*	
	$design=$design.'<tr>
	                   <td>'.$RowIndex.'.</td>';
	if($_SESSION['allowDelete'] == 1 && $_SESSION['uid'] != $row[0]) {	
	  $design=$design.' <td><a onclick=confirmDelete("'.$row[0].'") style="cursor: pointer;"><i class="fa fa-trash"></i></a></td>'; }
	else { echo '<td><i class="fa fa-lock"></i></td>'; }	
	if($_SESSION['allowUpdate'] == 1) {	$design=$design.' <td><a onclick=opendetail("'.$row[0].'") style="cursor: pointer;"><i class="fa fa-folder-open-o"></i></a></td>'; }
    else { echo '<td><i class="fa fa-lock"></i></td>'; }													  
	$design=$design.' <td>'.$row[1].'</td>
	                  <td>'.$row[2].'</td>
					 </tr>';}	*/
	    
  mssql_free_result($result);
  $design=$design.' </tbody></table>';
  $design=$design.'</div>';
  
  echo $design;	  

/*
  echo '<div class="clear height-20"></div>';

  echo '<div class="w3-left">
	      <div class="w3-bar-item w3-border w3-round w3-light-grey" style="padding:5px">
			<a href="#" class="w3-bar-item w3-button" style="text-decoration:none;">Total Page: '.$Num_Pages.' &nbsp';
					
  if($Num_Pages<=$page) {
	$prev=$page-1;
	echo "<a href='$_SERVER[SCRIPT_NAME]?show=ltem&id=$keywrd&Page=$prev' style='text-decoration:none' class='w3-bar-item w3-button w3-hover-blue'>&laquo;</a>";
  }

  for($i=1; $i<=$Num_Pages; $i++) {  
	if($i == $page) { 
	  echo "<a href='#' class='w3-bar-item w3-button'><b>".$i."</b></a>&nbsp;"; 
	}
	else { 
	  echo "<a class='w3-bar-item w3-button w3-hover-blue' style='text-decoration:none' 
		                   href='/e-imp/personal_data/?show=ltem&id=$keywrd&Page=$i'>$i</a>&nbsp;"; 
	}  
  }
  
  if($Num_Pages>$page) {
    $next=$page +1;
	echo "<a href='/e-imp/personal_data/?show=ltem&id=$keywrd&Page=$next' style='text-decoration:none' class='w3-bar-item w3-button w3-hover-blue'>&raquo;</a>";
  }
		
  echo '</div><br><br>';
		
	  /*if($page >= $Num_Pages) {
		echo $Num_Pages.' '.$page;  
	    $design="<div class='w3-left'>
	              <div class='w3-bar-item w3-border w3-round w3-light-grey' style='padding:5px'>
				    <a href='#' class='w3-bar-item w3-button' style='text-decoration:none'>Total Page: ".$Num_Pages." &nbsp
					<a href='$_SERVER[SCRIPT_NAME]?show=ltem&id=$keywrd&Page=$prev_page' class='w3-bar-item w3-button'>&laquo;</a>";
	    echo $design;
		for($i=1; $i<$page; $i++) {  
		  if($i == $page) { $design="<a href='#' class='w3-bar-item w3-button'><b>".$i."</b></a>&nbsp;"; }
		  else {
		  $design="<a class='w3-bar-item w3-button w3-hover-blue' style='text-decoration:none' 
		            href='$_SERVER[SCRIPT_NAME]?show=ltem&id=$keywrd&Page=$i'>$i</a>&nbsp;"; 
		  echo $design; }
		
		//$design="<a href='#' class='w3-bar-item w3-button'><b>".$page."</b></a>&nbsp;";
		echo $design;
		
		/*for($i=$page+1; $i<=$Num_Pages; $i++) { 
		  $design="<a class='w3-bar-item w3-button w3-hover-blue' style='text-decoration:none' 
		            href='$_SERVER[SCRIPT_NAME]?show=ltem&id=$keywrd&Page=$i'>$i</a>&nbsp;"; 
		  echo $design; }
	
  	    $design="<a href='$_SERVER[SCRIPT_NAME]?show=ltem&id=$keywrd&Page=$next_page' class='w3-bar-item w3-button w3-hover-blue'>&raquo;</a></div>
			     </div>";
	    echo $design; */

		
      mssql_close($dbSQL);  
  
  //echo '</div>';  
  echo '<div class="height-10"></div>';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>   
<script type="text/javascript">
/*  $(document).ready(function(){
    $("#cari").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get('emp_list.php', formValues, function(data){ $("#result").html(data); });
    });
  });	*/

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
	  $("#result").load("emp_remove.php?id="+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled','Selected record is safe :)','error' )}
	  })	
  }  
  
  function opendetail(param) { $("#result").load("emp_reg.php?id="+param); }
  function domanage(urlVariable) { $("#result").load(urlVariable); }  
</script>