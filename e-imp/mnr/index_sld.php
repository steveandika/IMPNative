<div class="height-20"></div>
<div class="form-main display-form-shadow w3-round-medium">
  <div class="w3-container">
    <label style="font: 600 18px/35px Rajdhani, Helvetica, sans-serif;">Shipping Line Document Management</label>
  </div>
  <div class="w3-container">
     <ul class="flex-container">
      <li class="flex-item">
        <a href="/e-imp/mnr/?do=sldhW&page=loadsld" class="w3-button w3-light-grey w3-round-small main-button_light-blue" title="Upload New SLD">
		   <i class="fa fa-upload"style="font-size:18px;color:#2196F3"></i><br>
		   <span class="navbar-label"> Upload File (format in Excel 97-2003)</span></a>	     
	  </li>
      <li class="flex-item">	  
        <a href="/e-imp/mnr/?do=sldhW&page=sld_log" class="w3-button w3-light-grey w3-round-small main-button_light-blue" title="View uploaded data">
		  <i class="fa fa-desktop" style="font-size:18px;color:#2196F3"></i><br><span class="navbar-label"> SLD Log</span></a>
	  </li>
	  <li class="flex-item">
        <a href="/e-imp/mnr/page_template.php?dl=template" target="_blank" class="w3-button w3-light-grey w3-round-small main-button_light-blue" title="Download Template">
		  <i class="fa fa-download" style="font-size:18px;color:#2196F3"></i><br>
		  <span class="navbar-label"> Download Template</span></a>	    
	  <li>
     </ul>
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

<!--
<div class="w3-container">
 <div style="border-top:5px solid #a1cb2f; background:#fff;-moz-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);-webkit-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);
               box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16)" class="w3-animate-zoom">

   <h3 style="padding:0 0 10px 0;border-bottom:1px solid #b3b6b7">&nbsp;&nbsp;Shipping Line Document</h3>
   
   <div class="w3-row-padding">
     <div class="w3-half">
       <div style="padding:0 0 20px 0;border:0">
         <div class="w3-row-padding" style="font-size:13px">
	       <div class="w3-half">
	         <a href="/e-imp/mnr/?do=sldhW&page=loadsld" style="text-decoration:none" class="w3-text-blue">
			           Load SLD File (format in Excel 97-2003)&nbsp;<i class="fa fa-upload"></i></a>	     
	       </div>
	       <div class="w3-half"></div>
	     </div>
	     <div class="height-10"></div>
	     <div class="w3-row-padding" style="font-size:13px">
	       <div class="w3-half">
	         <a href="/e-imp/mnr/?do=sldhW&page=sld_log" style="text-decoration:none" class="w3-text-blue">SLD Log Table</a>
	       </div>
	       <div class="w3-half"></div>
	     </div>
	     <div class="height-10"></div>
	     <div class="w3-row-padding" style="font-size:13px">
	       <div class="w3-half">
	         <a href="/e-imp/mnr/page_template.php?dl=template" target="_blank" style="text-decoration:none" class="w3-text-blue">
				       Get SLD Template&nbsp;<i class="fa fa-download"></i></a>	    
	       </div>
	       <div class="w3-half"></div>	   
	     </div>
       </div>  	 
	 </div>

	 <div class="w3-half">
<script language="php">
  if(isset($_GET['page'])) { include($_GET['page'].".php"); }
</script> 	 
	 </div>
   </div>
  
 </div> 
  <div class="height-20"></div>
</div>
-->
