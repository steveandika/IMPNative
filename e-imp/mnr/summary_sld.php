<script language="php">
  session_start();
  
  if(isset($_GET['key'])) {
    include("../asset/libs/db.php");
	  
    $keywrd = $_GET['key'];
	
    //get summary detail - 1. Unsetup In Event Unit
    $query = "Select IsNull(d.completeName, '') As completeName, c.Size, c.Type,
              Count(c.Size) As numSize, Count(c.Type) As numType, a.bookID
              From tabBookingHeader a
              Inner Join containerJournal b On b.bookInID=a.bookID
              Inner Join containerLog c On c.ContainerNo = b.NoContainer
              Left Join m_Customer d On d.custRegID = a.principle
              Where b.gateIn Is Null And a.SLDFileName='$keywrd' 
              Group By d.completeName, c.Size, c.Type, a.bookID; ";
    $result = mssql_query($query);	
	
	echo '<h2 style="border-bottom:1px solid #ccc;">Summary - '.$keywrd.'</h2>';
	echo '<table class="w3-table w3-border w3-bordered">
	          <thead>
			    <tr>
				  <th colspan="7">WORKSHOP WAITING EVENT IN</th>
	            <tr style="background:#3498db;color:#000">
				  <th>Principle</th>
				  <th>Size</th>
				  <th>Type</th>
				  <th>&nbsp;</th>
				  <th>&nbsp;</th>
     		    </tr></thead>
			  <tbody>'; 	 

	if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="5" style="color:red;font-weight:600">0 RECORD HAS FOUND</td></tr>';
	} 			  
	while($arr = mssql_fetch_array($result)) {
	  $var = 'file_n='.$keywrd.'&size='.$arr["Size"].'&type='.$arr["Type"].'&book='.$arr["bookID"];
	  
	  echo '<tr>
	          <td>'.$arr["completeName"].'</td>
			  <td>'.$arr["Size"].' ('.$arr["numSize"].')</td>
			  <td>'.$arr["Type"].' ('.$arr["numType"].')</td>';
			  
      if($_SESSION['allowDelete'] == 1) { 
	    echo '<td><a onclick=confirmDelete("'.$var.'") class="w3-btn w3-red" 
	          style="padding:4px 8px;border-radius:4px">Delete</a></td>'; 
	  }			  
      echo '  <td><a href="browse_uploadedsld.php?'.$var.'" target="_blank" class="w3-btn w3-yellow" 
	          style="padding:4px 8px;border-radius:4px">Browse</a></td>';
	  echo '</tr>';
	}
	echo '    </tbody></table><br>';
	mssql_free_result($result);
	
    //get summary detail - 1. Setup already In Event Unit	
    $query = "Select IsNull(d.completeName, '') As completeName, c.Size, c.Type,
              Count(c.Size) As numSize, Count(c.Type) As numType
              From tabBookingHeader a
              Inner Join containerJournal b On b.bookInID=a.bookID
              Inner Join containerLog c On c.ContainerNo = b.NoContainer
              Left Join m_Customer d On d.custRegID = a.principle
              Where b.gateIn Is Not Null And a.SLDFileName='$keywrd' 
              Group By d.completeName, c.Size, c.Type; ";
    $result = mssql_query($query);	

	echo '<table class="w3-table w3-border w3-bordered">
	          <thead>
			    <tr>
				  <th colspan="4">HAVE EVENT</th>
	            <tr class="w3-light-green" style="color:#000">
				  <th>Principle</th>
				  <th>Size</th>
				  <th>Type</th>
				  <th>&nbsp;</th>
     		    </tr></thead>
			  <tbody>'; 	 
	
	if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="4" style="color:red;font-weight:600">0 RECORD HAS FOUND</td></tr>';
	} 
	while($arr = mssql_fetch_array($result)) {
	  echo '<tr>
	          <td>'.$arr["completeName"].'</td>
			  <td>'.$arr["Size"].' ('.$arr["numSize"].')</td>
			  <td>'.$arr["Type"].' ('.$arr["numType"].')</td>';
      echo '  <td><a href="browse_uploadedsld.php?'.$var.'" target="_blank" class="w3-btn w3-yellow" 
	          style="padding:4px 8px;border-radius:4px">Browse</a></td>
			</tr>';
	}
	echo '    </tbody></table><br>';
	mssql_free_result($result);
	
	
    mssql_close($dbSQL);
  }
</script>

<script src="../asset/js/sweetalert2.min.js"></script>   
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
	  $("#summary_sld").load("doremove_sld.php?"+privVariable);
	}, function(dismiss) {
		if (dismiss == 'cancel') { swal('Cancelled', 'Selected record is safe :)', 'error' )}
	  })	
  }  

  function viewdetail(urlVariable) {     
    var w=window.open("browse_uploadedsld.php?"+urlVariable); 
 	$(w.document.body).html(response);}  
</script>