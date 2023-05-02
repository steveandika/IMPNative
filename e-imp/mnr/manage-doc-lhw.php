<div class="height-10"></div>
<div class="form-main w3-round-large" style="border-width:2px!important">
 <div class="flex-container">
  <div class="flex-item"><a href="/e-imp/mnr/?do=dolhW&page=loadhw" class="w3-btn w3-blue">Load LHW File (XLS Format)</a></div>
  <div class="flex-item"><a href="/e-imp/mnr/page_template.php?dl=hw_template" target="_blank" class="w3-btn w3-light-green">Download Template</a></div>
 </div>
</div>
<div class="height-5"></div>

<?php 
  if(isset($_GET['page'])) { 
    echo '<div class="form-main">';
    include($_GET['page'].".php"); 
    echo '</div>';
  }
?>		
