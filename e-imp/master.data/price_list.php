<div class="height-20"></div>
<div class="display-form-shadow form-main w3-round-medium">
  <div class="form-header">Upload New Price List</div>
  <div class="height-10"></div>
  <form id="fRegTeam" method="post" onSubmit="return validateForm()" action="upload-pricelist-file" enctype="multipart/form-data"> 
	<div class="w3-row-padding">
	  <div class="w3-third w3-text-grey" style="text-align:right;padding:4px 4px">Price List Name</div>
	  <div class="w3-third"><input class="w3-input w3-border" type="text" maxlength="10" style="text-transform:uppercase;" required name="namaFile" /></div>
	  <div class="w3-third"></div>
    </div> 				
	<div class="height-5"></div>
			 
	<div class="w3-row-padding">			 
	 <div class="w3-third w3-text-grey" style="text-align:right;padding:4px 4px">Currency</div>
	 <div class="w3-third">
	  <select class="w3-select w3-border" name="curr">
	    <option value="IDR">IDR&nbsp;</option>
		<option value="USD">USD&nbsp;</option>
	  </select>
	 </div> 
	 <div class="w3-third"></div>
    </div> 
	<div class="height-5"></div>
			 
	<div class="w3-row-padding">			 
	  <div class="w3-third w3-text-grey" style="text-align:right;padding:4px 4px">File to Import</div>
	  <div class="w3-third"><input class="w3-input w3-border" type="file" required name="fileUser"></div>
	  <div class="w3-third"></div>
    </div> 
	<div class="height-20"></div>   

    <div class="w3-row-padding">             
	  <div class="w3-half"><button type="submit" class="button-blue" name="register">Start Upload</button></div>
	  <div class="w3-half"></div>
    </div> 
  </form>  
  <div class="height-10"></div>
</div>  


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