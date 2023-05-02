	<div class="height-10"></div>
	<form class="search border-radius-3" id="filterLokasi" method="post">
		<h1>Maintenance and Repair</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	
		<table class="w3-table">
			<tr>
				<td>
					<input type="text" name="noCnt" placeholder="Container Number" maxlength="11" required />
				</td>
			</tr>
			<tr>
				<td>
					<select name="location" >
					<option selected value="ALL">ALL LOCATION</option>
		  
					<?php
						$query = "Select * From m_Location Order By locationDesc ";
						$result = mssql_query($query);
						while($arr=mssql_fetch_array($result)) 
						{ 
							if($workshopID == $arr[0]) { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
							else { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
						}	
						mssql_free_result($result);
					?>
		
					</select>	

					<button type="submit">Search</button>
				</td>
			</tr>
		</table>
		<div class="height-20"></div>		
	</form>
	<div class="height-10"></div>

	<div id="loader-icon" style="display:none;font-weight:500">&nbsp;&nbsp;.. Pencarian data, mohon menunggu</div>  
	<div id="mnr_form" ></div>

<script type="text/javascript">
    $(document).ready(function(){  
        $("#filterLokasi").submit(function(event){
			event.preventDefault();
			$("#mnr_form").hide();
			$("#loader-icon").show();
			var formValues = $(this).serialize();
			$.post("mnguntFiltered.php", formValues, function(data){ 
				$("#loader-icon").hide();
				$("#mnr_form").html(data); 		
				$("#mnr_form").show();
			});
        });
    }); 
</script> 