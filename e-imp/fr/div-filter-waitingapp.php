	 <form class="rpt border-radius-3" method="post" action="fr/waitingAppList" target="wDetail"> 
		<h1>Daily Waiting Approval</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	 
	 
		<div id="inside-wrapper">
			<label style="weight:500">Hamparan Name/Location</label>
			<select class="w3-input w3-border border-radius-3" name="hamparanName" required />

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