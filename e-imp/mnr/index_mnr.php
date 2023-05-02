<div class="submenu-bar">  
   <div class="flex-container">
      <div class="flex-item">
        <a onclick=openmnr() style="text-decoration:none;cursor:pointer">    
		  <i class="fa fa-search" style="font-size:18px;color:#2196F3"></i>&nbsp;&nbsp;Search By Container Number</a>
	  </div>
<!--      <div class="flex-item">
	   <a onclick=openmnr_hmp() style="text-decoration:none;cursor:pointer" class="w3-text-blue" title="MNR-Hamparan Inquiry">
		  <i class="fa fa-search" style="font-size:18px;color:#2196F3"></i>&nbsp;&nbsp;Hamparan/Workshop</a>
	  </div>	  -->
      <div class="flex-item">
	   <a onclick=uploading() style="text-decoration:none;cursor:pointer" title="Image Management">
		  <i class="fa fa-file-image-o" style="font-size:18px;color:#2196F3"></i>&nbsp;&nbsp;Image</a>
	  </div>
<!--	  
      <div class="flex-item">
       <a href="/e-imp/mnr/manage-cr" style="text-decoration:none;cursor:pointer" target="_blank">
	      <i class="fa fa-calendar-o" style="font-size:18px;color:#2196F3"></i>&nbsp;&nbsp;C/R, C/C, Approval</a>	  
	  </div>
-->	  
     </div>  	  
</div>
<div class="height-10"></div>

<div id="mnr_content"></div> 
<div class="height-10"></div>
<div id="mnr_form"></div> 
<div class="height-10"></div>

<script type="text/javascript">
  function openmnr() { 
    $('#loader-icon').show();
    $("#mnr_form").hide(); 
    $("#mnr_content").load("mngunt.php"); 
	$('#loader-icon').hide();
  }
  function uploading() { 
    $('#loader-icon').show();
    $("#mnr_form").hide();
    $("#mnr_content").load("manage-photo.php"); 
	$('#loader-icon').hide();
  }  
  function openmnr_hmp() {
    $('#loader-icon').show();	  
    $("#mnr_form").hide();  
    $("#mnr_content").load("inquiry-wrks.php"); 
	$('#loader-icon').hide();
  }  
  
</script>