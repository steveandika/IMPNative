<?php $urldl=base64_encode('have_template');?>

<div class="submenu-bar w3-responsive">
 <ul>
  <li>
   <i class="fa fa-cloud-upload"></i>&nbsp;&nbsp;<a href="?do=upload" title="Upload Price List"><span class="navbar-label">Upload Price List</span></a>
   <i class="fa fa-cloud-download"></i>&nbsp;&nbsp;<a href="pl-template?have=<?php echo $urldl?>" title="Download Template" target="_blank"><span class="navbar-label">Download Template</span></a>
   <i class="fa fa-desktop"></i>&nbsp;&nbsp;<a href="?do=upload-log" title="Price List Log"><span class="navbar-label">Price List Log</span></a>
   <i class="fa fa-edit"></i>&nbsp;&nbsp;<a href="?do=cedex-manage" title="Manage Data Master"><span class="navbar-label">Manage Data</span></a>
  </li>
 </ul>
</div>