 	<div class="height-10"></div>	
 	<form id="inquiry_unlock_mnr" class="search border-radius-3" method="post">
		<h1>#Workshop Management > <strong>Open Lock MNR</strong></h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	 
				
		<div class="w3-row-padding">
			<div id="privateStyleLabel" class="w3-quarter">Estimate Number</div>
			<div class="w3-quarter" style="padding:0px">
				<input id="privateStyleInput" type="text" name="enbr" required style="text-transform: UpperCase; width:100%" value="<?php echo $nbr_estimate;?>" />
				<div class="height-3"></div>
			</div>  
			<div class="w3-quarter"></div>
			<div class="w3-quarter"></div>
		</div>
		  
		<div class="w3-row-padding">
			<div id="privateStyleLabel" class="w3-quarter">Request By</div>
			<div class="w3-quarter" style="padding:0px">		  
				<input id="privateStyleInput" type="text" name="user" required style="text-transform: UpperCase;width:100%" value="<?php echo $requester_name;?>" />
				<div class="height-3"></div>
			</div>  			  
			<div class="w3-quarter"></div>
			<div class="w3-quarter"></div>		  
		</div>
		  
		<div class="w3-row-padding" >
			<div id="privateStyleLabel" class="w3-quarter">Open Lock Reason</div>
			<div class="w3-quarter" style="padding:0px">		  
				<select id="privateStyleInput" name="desc" style="width:100%">
			 
				<?php
					if ($reason=="PENGHAPUSAN_DETAIL") { echo "<option selected value=PENGHAPUSAN_DETAIL>ADA DETAIL YANG HARUS DIHAPUS</option>"; }
					else { echo "<option value=PENGHAPUSAN_DETAIL>ADA DETAIL YANG HARUS DIHAPUS</option>"; } 
					if ($reason=="NAT") { echo "<option selected value=NAT>ADA DETAIL YANG HARUS DI-NAT</option>"; }
					else { echo "<option value=NAT>ADA DETAIL YANG HARUS DI-NAT</option>"; }  	   
					if ($reason=="UPDATE_APP_DATE") { echo "<option selected value=UPDATE_APP_DATE>PENGUBAHAN TANGGAL APPROVAL</option>"; }
					else { echo "<option value=UPDATE_APP_DATE>PENGUBAHAN TANGGAL APPROVAL</option>"; }  	   		   
				?>
				
				</select>
				<div class="height-3"></div>
			</div>  			  
			<div class="w3-quarter"></div>
			<div class="w3-quarter"></div>		  
		</div>
		  
		<div class="height-10"></div>
		<button type="submit" style="margin-left:0;">Apply</button>
		</div>
	</form>		
	
    <div class="height-10"></div>
    <div id="loader-icon" >..Processing request</div>
    <div id="unlockresult"></div>		
  	
    <script type="text/javascript">
      $(document).ready(function(){  
        $("#inquiry_unlock_mnr").submit(function(event){
          event.preventDefault();
	      $("#unlockresult").hide(); 		
	      $("#loader-icon").show();
          var formValues = $(this).serialize();
          $.post("newmnr/doUnlockmnr.php", formValues, function(data){ 
	        $("#loader-icon").hide();
	        $("#unlockresult").html(data);
            $("#unlockresult").show(); 		 		
	      });
        });
      }); 
    </script> 	
	


