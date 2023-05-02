<div id="notification" style="position: fixed;right: 5%; width:350px" class="boxshadow">
  <strong><i class="fa fa-file-excel-o w3-text-green"></i></strong>&nbsp;&nbsp;
	      <a href="template.php?dl=<?php echo base64_encode('tmplhw'); ?>" target="_blank">Download Template</a>
  <div class="height-20"></div>	
  
  <div style="padding:20px 10px;border: 1px solid #f7dc6f;background: #f9e79f">
    <label><strong>Please note, these folowing columns below that will read by I-Cons :</strong></label>
	<ul style="list-style: none">
	  <li>1. Container Number</li>
	  <li>2. Container Size</li>
	  <li>3. Hamp. In (= Date In)</li>
	  <li>4. Port In</li>
	  <li>5. Cleaning Type</li>
	</ul>  
  </div>
</div>

<div id="dataload-lhw" style="position: absolute;left: 15%;width: 575px;">
  <div style="width: 100%;">
    <form method="post" onSubmit="return validateForm()" action="dataload-lhw" enctype="multipart/form-data">	  

	  <div class="height-10"></div>
      <div class="w3-row-padding">
	    <div class="w3-third">
          <label>Document :</label>
          <input type="file" required name="docName" class="w3-input w3-border"  />
		</div>
        <div class="w3-twothird"></div>
      </div>		
      <div class="height-10"></div>

      <div class="w3-row-padding">
	    <div class="w3-third">
          <label>Date In Workshop :</label>
          <input type="date" required name="dateIn" class="w3-input w3-border"  />
		</div>
        <div class="w3-twothird"></div>
      </div>				  
      <div class="height-10"></div>

      <div class="w3-row-padding">
	    <div class="w3-third">
          <label>Workshop :</label>
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
		</div>
        <div class="w3-twothird"></div>
      </div>				  		 
      <div class="height-20"></div>
	  
	  <div class="w3-row-padding">
	    <div class="w3-third">
          <button type="submit" class="w3-button w3-blue-grey">Start Upload</button>
		</div>
        <div class="w3-twothird"></div>
      </div>				  		  
    </form> 
  </div>  
  <div class="height-10"></div>

<?php
 	if ($doc_Name != "") { 
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