<div class="height-20"></div>
<div class="form-main display-form-shadow w3-round-medium">
  <div class="form-header">Hamparan Document Management</div>
  <div class="height-10"></div>
  <div class="w3-container">
   <div class="submenu-bar">
     <ul class="flex-container">
      <li class="flex-item">
	   <a href="/e-imp/mnr/?do=dolhW&page=loadhw" style="text-decoration:none" title="Upload LHW File">
		  <i class="fa fa-cloud-upload"style="font-size:18px;color:#2196F3"></i><span class="navbar-label"> Load LHW File (XLS Format)</span></a>&nbsp;&nbsp;
	  </li>
      <li class="flex-item">
	   <a href="/e-imp/mnr/page_template.php?dl=hw_template" target="_blank" style="text-decoration:none" title="Download Template">
		  <i class="fa fa-cloud-download" style="font-size:18px;color:#2196F3"></i><span class="navbar-label"> Download Template</span></a>
	  </li>
     </ul>  	
   </div>
  </div>
  <div class="height-10"></div>
</div>
<div class="height-10"></div>

 <?php 
  if(isset($_GET['page'])) { 
    echo '<div class="form-main display-form-shadow w3-round-medium">';
    include($_GET['page'].".php"); 
    echo '</div>';
  }
 ?>		


<script type="text/javascript">
  function openModul(urlvar) {
	$("#sub_content").load(urlvar);
  }
</script>