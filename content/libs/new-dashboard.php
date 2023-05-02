<div id="main-menu" class="common-window" style="position: fixed;width: 195px;top: 70px; left:20px">
  <?php $design="";
  
        if($valid_11==1 || $valid_12==1 || $valid_15==1 || $valid_16==1|| $valid_18==1) {
		  $design=$design.'<div style="line-height:35px;font-weight: 500;color: #99a3a4">Master</div>'; 
		  
	      if($valid_11==1) { 
		    $design=$design.'<a href="/e-imp/content/emp" >Manage User Profile</a><div class="height-5"></div>'; 
		  }	
	      if($valid_12==1) { 		  
			$design=$design.'<a href="/e-imp/content/users" >User Management</a><div class="height-5"></div>'; 
		  } 
		  if($valid_15==1) { 
		    $design=$design.'<a href="?p=vcust" >Customer</a><div class="height-5"></div>'; 
		  }	
		  if($valid_16==1) { 		  
		    $design=$design.'<a href="?p=costCenter" >Cost Center</a><div class="height-5"></div>'; 			
		  } 
		  if($valid_18==1) { 
		    $design=$design.'<a href="?p=wrk" >Hamparan</a><div class="height-5"></div>'; 
			$design=$design.'<a href="?p=wrk" >Price List Mngnt.</a><div class="height-5"></div>'; 
		  } 
		  
		  $design=$design.'<div class="height-10"></div>';
        }
		
		if($valid_32==1) {
		  $design=$design.'<div style="line-height:35px;font-weight: 500;color: #99a3a4">Operation</div> ';	
		  if($valid_32==1) {
	  	    $design=$design.'<a href="?p=gatein" >Masuk Hamparan</a><div class="height-5"></div>';
	  	    $design=$design.'<a href="?p=mnr">M N R</a><div class="height-5"></div>';		
	  	    $design=$design.'<a href="?p=gateout" >Keluar Hamparan</a><div class="height-5"></div>';					  
          }	
		  
		  $design=$design.'<div class="height-10"></div>';
		}

        $design=$design.'<div  style="line-height:35px;font-weight: 500;color: #99a3a4">Reports</div> ';			
		$design=$design.'<a href="?dir='.base64_encode("rpt_waiting_survey").'" >Waiting Survey</a><div class="height-5"></div>
                         <a href="?dir='.base64_encode("rpt_waiting_cr").'" >Waiting CR</a><div class="height-5"></div>	  		
                         <a href="?dir='.base64_encode("rpt_filter_cr").'" >Sum. CR & Cleaning</a><div class="height-5"></div>';

		echo $design;
  ?>
</div>