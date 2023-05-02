  <div class="w3-container">
    <label style="font: 600 18px/35px Rajdhani, Helvetica, sans-serif;">Upload S L D</label>
  </div>
  <div class="height-20"></div>
  <div class="w3-container">   
   <div style="padding:3px 3px;border:1px solid #f1f1f1;width:296px">
     <label class="w3-text-grey" >Catatan:</label>
   </div>  
   <div style="padding:3px 3px;border:1px solid #f1f1f1;border-top:0;width:296px">
    <label>
	  1.&nbsp;&nbsp;Format file pastikan dalam XLS 97-2003<br>
      2.&nbsp;&nbsp;Pastikan format kolom sesuai dengan standard
	</label>
   </div>
   <div class="height-20"></div>
   
   <form method="post" action="doloadsld" enctype="multipart/form-data" submit="return validateForm();">
    <div class="w3-row-padding">
	 <div class="w3-quarter">SLD File</div>
	 <div class="w3-quarter"><input type="file" required name="SLDFileName" /></div>
	 <div class="w3-twoquarter"></div>
	</div>
    <div class="height-5"></div>	

    <div class="w3-row-padding">
	 <div class="w3-quarter">(Optional) Hamparan Location</div>
     <div class="w3-quarter">
	   <select name="location" class="style-select">		   
	     <option value="">&nbsp;</option>
         <?php
           include("../asset/libs/db.php");		 
		   
           $query = "Select * From m_Location Order By locationDesc ";
           $result = mssql_query($query);
           while($arr = mssql_fetch_array($result)) { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
           mssql_free_result($result);
         ?>

       </select>
	 </div>
	 <div class="w3-twoquarter"></div>
	</div>
	
	<div class="height-10"></div>
	<div class="w3-row-padding">
	 <div class="w3-third"><button type="submit" class="w3-button w3-blue w3-round-small">Start Upload</button></div>
	 <div class="w3-twothird"></div>
	</div>	 
   </form>	 
   <div class="height-10"></div>	   
  </div>   
 
<?php
  if(isset($_GET['success'])) {
   if($_GET['success'] > 0) {echo '<script>swal("Done","Please check and recheck related uploaded record.");</script>';}	   
   else {echo '<script>swal("Failed","File has failed to upload.");</script>';}	   
  } 
  if(isset($_GET['haveE'])) {
   if($_GET['haveE'] > 0) {$haveEvent = $_GET['haveE']; echo '<script>swal('.$haveEvent.'" unit(s) have event in already");</script>';}	   
  } 
?>

<script type="text/javascript">
  // validasi form (hanya file .xls yang diijinkan)
  function validateForm() {
    function hasExtension(inputID, exts) {
      var fileName = document.getElementById(inputID).value;
      return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
    }
       
    if(!hasExtension('fileUser', ['.xls'])) {
	  swal("Error","Only Microsoft Excel 97-2003 (XLS) files are permitted.","error");  
      //alert("Only Microsoft Excel 97-2003 (XLS) files are permitted.");
      return false;
    }
  }
</script>  