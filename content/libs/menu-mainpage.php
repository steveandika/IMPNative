<ul id="menu" class="w3-card-2">
 <li> <a href="#" class="drop"><i class="fa fa-th-large"></i>&nbsp;&nbsp;Dashboard</a><!-- Begin 5 columns Item -->     
   <div class="dropdown_5columns"><!-- Begin 5 columns container -->     
     
     <?php $design="";
	 
	       if($valid_11=-1 || $valid_15==1 || $valid_18==1) {
		     $design=$design.'<div class="col_5"><h1>Data Master</h1></div>';
	       
	         if($valid_11==1) { $design=$design.'<div class="col_1"><a href="/e-imp/personal_data/?show=ltem">Manage User Profile</a></div>'; }  
	         if($valid_15==1) { 
		       $design=$design.'<div class="col_1" style="border-left:1px solid #34495e"><a href="?p=vcust"> Customer</a></div>'; 
			   $design=$design.'<div class="col_1" style="border-left:1px solid #34495e;"><a href="?p=costCenter"> Cost Center</a></div>';
		     }
	         if($valid_18==1) { 
		       $design=$design.'<div class="col_1" style="border-left:1px solid #34495e;"><a href="?p=wrk"> Hamparan</a></div>'; 
			   $design=$design.'<div class="col_1" style="border-left:1px solid #34495e;"><a href="?p=wrk"> Price List Mngnt.</a></div>';
		     }		   
		   }	 
		   
		   if($valid_18==1 || $valid_32==1) {
		     $design=$design.'<div class="col_5"><h1>DataLoad</h1></div>'; 	
		  
		     if($valid_18==1) {$design=$design.'<div class="col_1"><a href="?p=pr_mnr"">Price List</a></div>'; }
		     if($valid_32==1) {
	  	       $design=$design.'<div class="col_1" style="border-left:1px solid #34495e"><a href="?p=sldhW">Shipping Line Document</a></div>';
	           $design=$design.'<div class="col_1" style="border-left:1px solid #34495e"><a href="?p=dolhW">Laporan Hamparan</a></div>';		
		     } 			  
		   }	
		   
		   if($valid_32==1) {
		     $design=$design.'<div class="col_5"><h1>Operasional</h1></div>';	
		     if($valid_32==1) {
	  	       $design=$design.'<div class="col_1"><a href="?p=gatein">Masuk Hamparan</a></div>';
	  	       $design=$design.'<div class="col_1" style="border-left:1px solid #34495e"><a href="?p=mnr">M N R</a></div>';		
	  	       $design=$design.'<div class="col_1" style="border-left:1px solid #34495e"><a href="?p=gateout">Keluar Hamparan</a></div>';					  
             }			  
		   }	

           $design=$design.'<div class="col_5"><h1>Laporan Kegiatan</h1></div>';			
		   $design=$design.'<div class="col_1"><a href="?p=slhw">Summary Hamparan</a></div>
                            <div class="col_1" style="border-left:1px solid #34495e"><a href="?p=wsurvey">Waiting Survey</a></div>
                            <div class="col_1" style="border-left:1px solid #34495e"><a href="?p=w_fns">Waiting CR</a></div>	  		
                            <div class="col_1" style="border-left:1px solid #34495e"><a href="?p=sm_mnr">Sum. CR & Cleaning</a></div>';		   
		   
           echo $design;		   
	 ?>
       
  </div><!-- End 5 columns container -->
     
 </li><!-- End 5 columns Item -->
</ul>
