<div id="mySidenav" class="sidenav">
  <?php $design="";

        $design=$design.'<div class="height-10"></div> ';			
		$design=$design.'<a href="?p=vcust" style="border:none;text-align:left;background:#0d6a92; -moz-border-radius:none;-webkit-border-radius:none;border-radius:none;
		                  box-shadow:none"><i class="fa fa-th-large"></i>&nbsp;Update Password</a> <div class="height-5"></div>';
		
		$design=$design.'<a href="/e-imp/session/?eof=1" style="background:#e74c3c;color:#fff" >LOG OUT</a><div class="height-15"></div>';

        if($valid_11==1 || $valid_15==1 || $valid_18==1) {
		  $design=$design.'<div id="title_menu_Master" style="text-align:center;line-height:35px;">Master</div>'; 
		  
	      if($valid_11==1) { $design=$design.'<a href="/e-imp/content/emp">Manage User Profile</a><div class="height-5"></div>'; } 
		  if($valid_15==1) { 
		    $design=$design.'<a href="?p=vcust" >Customer</a><div class="height-5"></div>'; 
		    $design=$design.'<a href="?p=costCenter" >Cost Center</a><div class="height-5"></div>'; 			
		  } 
		  if($valid_18==1) { 
		    $design=$design.'<a href="?p=wrk" >Hamparan</a><div class="height-5"></div>'; 
			$design=$design.'<a href="?p=wrk" >Price List Mngnt.</a><div class="height-5"></div>'; 
		  } 
        }
		
		if($valid_18==1 || $valid_32==1) {
		  $design=$design.'<div id="title_menu_Master" style="text-align:center;line-height:35px;">DataLoad</div> '; 	
		  
		  if($valid_18==1) {$design=$design.'<a href="?p=pr_mnr" >Price List</a><div class="height-5"></div>'; }
		  if($valid_32==1) {
	  	    $design=$design.'<a href="?p=sldhW" >Shipping Line Document</a><div class="height-5"></div>';
	        $design=$design.'<a href="?p=dolhW" >Laporan Hamparan</a><div class="height-5"></div>';		
		  } 
		}
		
		if($valid_32==1) {
		  $design=$design.'<div id="title_menu_Master" style="text-align:center;line-height:35px;">&nbsp;&nbsp;Operation</div> ';	
		  if($valid_32==1) {
	  	    $design=$design.'<a href="?p=gatein" >Masuk Hamparan</a><div class="height-5"></div>';
	  	    $design=$design.'<a href="?p=mnr" >M N R</a><div class="height-5"></div>';		
	  	    $design=$design.'<a href="?p=gateout" >Keluar Hamparan</a><div class="height-5"></div>';					  
          }			  
		}

        $design=$design.'<div id="title_menu_Master" style="text-align:center;line-height:35px;">&nbsp;&nbsp;Laporan Kegiatan</div> ';			
		$design=$design.'<a href="?p=slhw" >Summary Hamparan</a><div class="height-5"></div>
                         <a href="?p=wsurvey" >Waiting Survey</a><div class="height-5"></div>
                         <a href="?p=w_fns" >Waiting CR</a><div class="height-5"></div>	  		
                         <a href="?p=sm_mnr" >Sum. CR & Cleaning</a><div class="height-5"></div>';
				
		$design=$design.'<div class="height-60"></div>';
		echo $design;
  ?>		
</div>
