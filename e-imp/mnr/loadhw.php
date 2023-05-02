<div class="form-header">&nbsp;&nbsp;Upload LHW Document</div>
<div class="height-10"></div>
<div style="padding:0px 10px 15px 15px;border:0"> 
  <label class="w3-text-grey" style="font-size:13px">Notes:<br>
	&nbsp;&nbsp;1.&nbsp;&nbsp;Make sure your document in XLS 97-2003 format file<br>
    &nbsp;&nbsp;2.&nbsp;&nbsp;Make sure you use given standard format
  </label>
</div>
   
<form method="post" onSubmit="return validateForm()" action="doloadhw" enctype="multipart/form-data">		 
 <div class="flex-container">
   <div class="flex-item" style="width:140px">Attach Document</div>
   <div class="flex-item" style="width:250px"><input type="file" required name="HWFileName" /></div>   
 </div>
 <div class="height-5"></div>
 <div class="flex-container">
   <div class="flex-item" style="width:140px">Hamparan Name</div>
   <div class="flex-item" style="width:250px">
     <select name="location" class="style-select">		   
       <?php $query = "Select * From m_Location Order By locationDesc ";
             $result = mssql_query($query);
             while($arr = mssql_fetch_array($result)) { echo '<option value="'.$arr[0].'">'.$arr[1].'</option>'; }
             mssql_free_result($result); 
	   ?>
     </select>

   </div>   
 </div>
 <div class="height-10"></div>
 <div class="flex-container">	 
   <div class="flex-item"><button type="submit" style="padding:2px 6px;outline:none">Start Upload</button></div>
 </div>
<form> 
<div class="height-5"></div>
	 
<?php
  $design="";
  if(isset($_GET['success'])) { 
    $design=$design."<div class='hardnotif'> 	  
	                  <div class='height-10'></div>
	   		          <div class='w3-container'>Semua data berhasil disimpan ke dalam Journal Container</div>	 
					  <div class='height-10'></div>
	                 </div>"; 
  }	     
						  
  if(isset($_GET['error'])) { 
    $design=$design."<div class='hardnotif' style='width:600px;margin:0 auto'> 	  
	                  <div class='height-10'></div>
	 			      <div class='w3-container'>Maaf, telah terjadi kegagalan saat proses penyimpanam data. Periksa kembali File XLS Anda.</div>	 
					  <div class='height-10'></div>
	                 </div>"; 
  }	     
  
  if(isset($_GET['reject']))  { 
    $design=$design."<div class='hardnotif' style='width:600px;margin:0 auto'> 	  
	                  <div class='height-10'></div>
	  		          <div class='w3-container'>Terdapat beberapa Nomor Container yang tidak/gagal tersimpan ke dalam Journal Container</div>	 
					  <div class='height-10'></div>
	                 </div>"; 
  }	     
  $design=$design.'<div class="height-5"></div>';
  echo $design;
  
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