	<form class="rpt border-radius-3" method="post" action="fr/summeor" target="wDetail"> 
		<h1>Daily Summary Repair</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	 
		
	    
		<div id="inside-wrapper">
			<label style="weight:500">Liners*</label>
		    <select class="w3-input w3-border border-radius-3" name="mlo" required />
				<?php
					$cmd = "Select custRegID, completeName, shortName 
							From m_Customer with (NOLOCK) Where asMLO=1 Order By shortName;";
					$result = mssql_query($cmd);
					while($arr = mssql_fetch_array($result)) 
					{
						echo '<option value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>';
					}
					mssql_free_result($result);			
				?>
		    </select>
			<div class="height-10"></div> 
			
			<label style="weight:500">Hamparan In (Range Date)*</label>
		    <input class="w3-input w3-border border-radius-3"  type="date" name="activityDTTM1" required />
			<div class="height-5"></div>
		    <input class="w3-input w3-border border-radius-3"  type="date" name="activityDTTM2" required />
			<div class="height-10"></div>

			<label style="weight:500">Activity Type*</label>
	        <select class="w3-input w3-border border-radius-3" name="activityType" Required />
				<option value=1>REPAIR&nbsp;</option>
				<option value=2>CLEANING&nbsp;</option>
            </select>		  
		    <div class="height-10"></div>

			<label style="weight:500">Party</label>
	        <select class="w3-input w3-border border-radius-3" name="billingParty">
				<option value="">&nbsp;</option>
				<option value="O">O</option>
				<option value="U1">U1</option>
				<option value="U2">U2</option>
				<option value="T">T</option>
            </select>		  
		    <div class="height-10"></div>
		
			<label style="weight:500">Hamparan Name*</label>
	        <select class="w3-input w3-border border-radius-3" name="hamparanName" Required />
				<?php
					$cmd = "Select * From m_Location with (NOLOCK) Order By locationDesc ";
					$result = mssql_query($cmd);
					while($arr = mssql_fetch_array($result))
					{ 
						echo '<option value="'.$arr[0].'">'.$arr[1].'</option>';
					}
					mssql_free_result($result);
				?>
            </select>		  
		    <div class="height-10"></div>	

			<label style="weight:500">Currency*</label>
	        <select class="w3-input w3-border border-radius-3" name="currency" Required />
				<option value="IDR">IDR</option>
				<option value="USD">USD</option>
            </select>		  
		    <div class="height-3"></div>
			<div class="height-10"></div>		
		
	    </div>	
		<div class="height-30" style="border-bottom: 1px solid #7a7a52"></div>
		<div class="height-20"></div>
		<input type="submit" class="w3-btn w3-blue" name="register" value="Start Gather Data" />
		
	  </form>
    </div>
	<div class="height-20"></div>