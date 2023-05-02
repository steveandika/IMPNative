<div id="notification" style="position: fixed;right: 5%; width:350px" >
  <strong><i class="fa fa-file-excel-o w3-text-green"></i></strong>&nbsp;&nbsp;
	      <a href="template.php?dl=<?php echo base64_encode('tmpappcrcc'); ?>" target="_blank">Download Template</a>
  <div class="height-20"></div>		
	
  <div style="padding:20px 10px;border: 1px solid #7fb3d5;background: #d6eaf8" class="boxshadow">
    <label>Make sure all date field; using proper format (yyyy-mm-dd, example: 2019-02-01)</label>
  </div>
</div>

<div id="dataload-app" style="position: absolute;left: 15%;width: 575px;">
  <div style="width: 100%;">
    <form method="post" onSubmit="return validateForm()" action="dataload-app" enctype="multipart/form-data">	  
	  <div class="height-5"></div>
   
      <label><strong>Document :</strong></label>
      <input type="file" required name="docName" class="w3-input w3-border"  />
      <div class="height-20"></div>
      <button type="submit" class="button-blue">Start Upload</button>
    </form> 
  </div>  
  <div class="height-10"></div>

<?php
 	if ($doc_Name!="") { 
	  $sql="Select xls_file_name From LOG_HAMPARAN_INJECT_HEADER Where xls_file_name='$doc_Name'; ";
	  $rsl=mssql_query($sql);
	  if (mssql_num_rows($rsl) > 0) {
	    echo '<script languange="text/javascript">
		       alert("File already exist in log system. No log need to view.");
		      </script>';
	  }
      else { include("dataload-app-process.php"); }
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
          alert("Only Microsoft Excel 97-2003 (XLS) files are permitted.");
          return false;
        }
      }
    </script>  
</div>