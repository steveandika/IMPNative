	<div class="height-10"></div>
	<form class="rpt border-radius-3" id="summaryHamparan" method="post"> 
		<h1>Monitoring Estimate of Repair Billing</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>

		<div id="inside-wrapper">
			<label style="font-weight:500">Estimate Date</label>
			<input type="date" class="w3-border border-radius-3" name="estimate1" required />
			<div class="height-10"></div>   
			<label style="font-weight:500">until</label>
			<input type="date" class="w3-border border-radius-3" name="estimate2" required />
			<div class="height-10"></div>
			<label  style="font-weight:500">Monitoring Type</label>
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