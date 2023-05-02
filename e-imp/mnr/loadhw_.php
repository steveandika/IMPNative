   <div class="w3-container">
    <label style="font: 600 18px/35px Rajdhani, Helvetica, sans-serif;">Upload L H W</label>
   </div>
   <div class="height-20"></div>
   <div style="padding:0px 10px 15px 15px;border:0"> 
     <label class="w3-text-grey" style="font-size:13px">Catatan:<br>
	  &nbsp;&nbsp;1.&nbsp;&nbsp;Format file pastikan dalam XLS 97-2003<br>
      &nbsp;&nbsp;2.&nbsp;&nbsp;Pastikan format kolom sesuai dengan standard</label>
   </div>
   
   <div class="w3-container">
     <form method="post" onSubmit="return validateForm()" action="doloadhw" enctype="multipart/form-data">		 
  	   <div class="w3-row-padding">
	     <div class="w3-half">  		     
		   <label>XLS File</label>
		   <input class="style-input" type="file" required name="HWFileName" />
		 </div>		   
		 <div class="w3-half"></div>
	   </div>
	   <div class="height-5"></div>		    
	   <div class="w3-row-padding">
	     <div class="w3-half">
		   <label>Hamparan</label>   
		   <select name="location" class="style-select style-border">		   
           <?php
             $query = "Select * From m_Location Order By locationDesc ";
             $result = mssql_query($query);
             while($arr = mssql_fetch_array($result)) { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
             mssql_free_result($result);
           ?>
           </select>
		 </div>
		 <div class="w3-half"></div>
	   </div>
	   <div class="height-10"></div>
	   <button type="submit" class="w3-button w3-blue w3-round-small">Start Upload</button>	   
	 </form>  
	 <div class="height-20"></div>
   </div> 
	
<script language="php">  
    if(isset($_GET['success'])) { echo '<script>swal("Semua data berhasil disimpan ke dalam Journal Container");</script>';}	     
    if(isset($_GET['error'])) { echo '<script>swal("Maaf, telah terjadi kegagalan saat proses penyimpanam data. Periksa kembali File XLS Anda.");</script>'; }	
    if(isset($_GET['reject'])) { echo '<script>swal("Terdapat beberapa Nomor Container yang tidak/gagal tersimpan ke dalam Journal Container");</script>';}	     	
</script>

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