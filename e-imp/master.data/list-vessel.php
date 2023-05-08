<script language="php">
  session_start();
  include ("../asset/libs/db.php");
</script>

<div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Registered Vessel</h2>
<div class="height-20"></div> 

  <form id="cari" method="get">
    <script language="php">
      $keywrd = '';
      if(isset($_GET['id'])) { $keywrd = strtoupper(trim($_GET['id'])); }
	  
/*      if($keywrd == '%') { echo '<input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" />'; }
      else { echo '<input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" value='.$keywrd.' >'; }	  */
	  echo '<input type="text" name="id" class="search searchtext" style="text-transform:uppercase;" value='.$keywrd.' >';
    </script>
  </form>
  
  <div class="height-10"></div>
  <div class="w3-responsive w3-animate-opacity">
    <table class="w3-table w3-border w3-bordered">
	  <thead><tr style="text-transform:uppercase">
	    <th>Feeder Operator Name</th>
		<th>Vessel Name</th>
		<th>Voyage</th>
		<th>P O L</th>
		<th>E T D</th>
		<th>P O D</th>
		<th>E T A</th>
	    <th></th>
		<th></th>		
	  </tr></thead>
	  <tbody>
	  
	  <script language="php">
	     $query="Select a.*, FORMAT(ETD, 'yyyy-MM-dd') As ETD, FORMAT(ETA, 'yyyy-MM-dd') As ETA, b.completeName 
		         From m_vessel a ";
		 $query=$query."Left Join m_Customer b On b.custRegID=a.operatorID ";
		 if($keywrd != '') { $query=$query."Where vesselName Like '".'%'.$keywrd.'%'."' Or 
		                                     voyage Like '".'%'.$keywrd.'%'."' Or vesselid Like  '".'%'.$keywrd.'%'."'"; } 
		 
		 $query=$query."Order By a.ETD, a.ETA, vesselName, voyage";
		 $result=mssql_query($query);
         if(mssql_num_rows($result) <= 0) {
	       $design=$design.'<tr><td colspan="9" style="text-align:left;letter-spacing: 1px;color:Red"></td></tr>'; }
         else {
           while($arr=mssql_fetch_array($result)) {
		     echo '<tr>';
			 echo '<td>'.$arr[11].'</a></td>'; 
			 echo '<td>'.$arr[1].'</td>
			       <td>'.$arr[2].'</td>
				   <td>'.$arr[4].'</td>
				   <td>'.$arr["ETD"].'</td>
				   <td>'.$arr[5].'</td>
		           <td>'.$arr["ETA"].'</td>';
			 if($_SESSION["allowDelete"]==1) { echo '<td><a onclick=confirmDelete("'.$arr[8].'") class="w3-btn w3-red" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Delete</a></td>'; }
			 else { echo '<td><i class="fa fa-locked"></i></a></td>'; } 
			 if($_SESSION["allowUpdate"]==1) { echo '<td><a onclick=opendetail("'.$arr[8].'") class="w3-btn w3-blue" 
	                                                         style="padding:4px 20px;border-radius:4px;font-weight:600">Update</a></td>'; }
			 else { echo '<td><i class="fa fa-locked"></i></a></td>'; } 
														
             echo '</tr>'; }
				   
		    mssql_close($dbSQL); }			   
	  </script>
	  
	  </tbody>
	</table>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../asset/js/sweetalert2.min.js"></script>   
<script type="text/javascript">
  $(document).ready(function(){
    $("#cari").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("list-vessel.php", formValues, function(data){ $("#result").html(data);
      });
    });	  
  });  

  function confirmDelete(param) {
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
	  $("#content").load("doremove.php?id="+param);
	}, function(dismiss) {
		if (dismiss == 'cancel') {
		  swal('Cancelled', 'Selected record is safe :)', 'error') }
	  })	
  }  
  
  function opendetail(param) { $("#result").load("reg-vessel.php?id="+param); }
</script>