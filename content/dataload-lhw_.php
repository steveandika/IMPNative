<div id="dataload-sld" style="position: absolute;left: 10px;width: 575px;">
  <div style="width: 100%;background: #3498db;padding:5px 5px;color: #fff;text-align: center">	  
    <strong>Upload Form</strong>
  </div>
  <div style="width: 100%;padding:20px 10px;border:2px solid #3498db">
   <form method="post" onSubmit="return validateForm()" action="dataload" enctype="multipart/form-data">
     <label><strong>Perhatian:</strong></label>
	 <label>Kolom yang akan dibaca ole sistem: (kolom 1-3) No Container, (kolom 4) Container Size, (kolom 4) Date In, (kolom 5) Date Port In, (kolom 10) Cleaning Type</label>	 
	 <div class="height-10"></div>
     <label><strong>Document :</strong></label>
     <input type="file" required name="docName" class="w3-input w3-border"  />
     <div class="height-10"></div>

     <label><strong>Hamparan/Workshop :</strong></label>
     <select name="workshopName" class="w3-select w3-border">		   
	 
<?php 
      $query = "Select * From m_Location Order By locationDesc ";
      $result = mssql_query($query);
      while($arr = mssql_fetch_array($result)) { 
	    if ($workshop==$arr[0]) { echo '<option selected value="'.$arr[0].'">'.$arr[1].'</option>'; }
		else { echo '<option value="'.$arr[0].'">'.$arr[1].'</option>'; }
	  }
      mssql_free_result($result); 
?>

     </select>
     <div class="height-20"></div>
     <button type="submit" class="button-blue">Start Upload</button>
   </form> 
   
<script type="text/javascript">
  // validasi form (hanya file .xls yang diijinkan)
  function validateForm() {
    function hasExtension(inputID, exts) {
      var fileName = document.getElementById(inputID).value;
      return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
    }
       
    if(!hasExtension('fileUser', ['.xls'])) {
      alert("Only Microsoft Excel 97-2003 (XLS) files are permitted.");
      return false;
    }
  }
</script>  

  </div>  
  <div class="height-10"></div>

<?php
 	if ($doc_Name!="") { 
	  $sql="Select xls_file_name From LOG_HAMPARAN_INJECT_HEADER Where xls_file_name='$doc_Name'; ";
	  $rsl=mssql_query($sql);
	  if (mssql_num_rows($rsl) > 0) {
	    echo '<script languange="text/javascript">
		       alert("File already exist in log system");
		      </script>';
	  }
      else { include("dataload-lhw-process.php"); }
	}
?>  
</div>