	<div class="height-10"></div>
	<form class="rpt border-radius-3" id="summaryHamparan" method="post"> 
		<h1>Monitoring Estimate of Repair Billing</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>
		<table class="w3-table">
			<tr>
				<td><label style="font-weight:500">Estimate Date</label></td>
			</tr>
			<tr>
				<td><input type="date" class="w3-input w3-border" name="estimate1" required /></td>
			</tr>
			<tr>
				<td><label style="font-weight:500">until</label></td>
			</tr>
			<tr>
				<td><input type="date" class="w3-input w3-border" name="estimate2" required /></td>
			</tr>
			<tr>
				<td><label style="font-weight:500">Monitoring Type</label></td>
			</tr>
			<tr>
				<td>
					<select class="w3-input w3-border name="HampName" required />
						<option value="EoRIConS">Eor IConS belum ditagihkan</option>
						<option value="EoRPDF">Eor Pelayaran belum ditagihkan</option>
						<option value="EoRInv">Eor Sudah ditagihkan belum ada No. Invoice</option>
						<option value="EoRIComplete">Lengkap</option>
					</select>
				</td>
			</tr>			
		</table>

		
		<div class="height-30" style="border-bottom: 1px solid #7a7a52"></div>
		<div class="height-20"></div>
		<input type="submit" class="w3-button w3-blue" style="border-radius:5px" name="register" value="Start Gather Data" />						
        
	</form>
	  
	<div class="height-20"></div>
    <div id="loader-icon" class="border-radius-3" style="display:none;">..Gathering information, please wait</div>
    <div id="summaryView"></div>	