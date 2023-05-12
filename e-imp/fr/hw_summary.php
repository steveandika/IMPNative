    <form class="rpt border-radius-3" id="summaryHamparan" method="post"> 
		<h1>Daily Summary Hamparan</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>

		<div id="inside-wrapper">
			<label style="weight:500">Hamparan In Date</label>
			<input class="w3-input w3-border border-radius-3" type="date" name="HampInDTTM" required />
			<div class="height-10"></div>   
			<label  style="weight:500">Hamparan Name</label>
			<select class="w3-input w3-border border-radius-3" name="HampName" required />

				<?php
					$cmd = "Select * From m_Location with (NOLOCK) Order By locationDesc";
					$result = mssql_query($cmd);
					while($arr = mssql_fetch_array($result)) 
					{ 
						echo '<option value="'.$arr["locationID"].'">'.$arr["locationDesc"].'</option>'; 
					}	
					mssql_free_result($result);
				?>
		  
			</select> 	
		</div>
		<div class="height-30" style="border-bottom: 1px solid #7a7a52"></div>
		<div class="height-20"></div>
		<input type="submit" class="w3-btn w3-blue" name="register" value="Start Gather Data" />						
        
    </form>
	  
	<div class="height-20"></div>
    <div id="loader-icon" class="border-radius-3" style="display:none;">..Gathering information, please wait</div>
    <div id="summaryView"></div>	  
	 		
<script type="text/javascript">
    $(document).ready(function(){  
        $("#summaryHamparan").submit(function(event){
          event.preventDefault();
	      $("#summaryView").hide(); 		
	      $("#loader-icon").show();
          var formValues = $(this).serialize();
          $.post("fr/hw_summaryView.php", formValues, function(data){ 
	        $("#loader-icon").hide();
	        $("#summaryView").html(data);
            $("#summaryView").show(); 		 		
	      });
        });
    }); 
</script> 