	<?php		
		$obj = new DatabaseClass ();
		$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateCompleteGroup");
	?>
	
	<h6 class='w3-text-blue-grey' style='font-weight:600;'>DOKUMEN PENAGIHAN EoR Lengkap</h6>
	<div class="height-10"></div>
	<table class="w3-table w3-bordered">
		<tr>
			<th>Index</th>
			<th></th>
			<th>Document Number</th>
			<th>Submit Date</th>
			<th>Shipping Line</th>
			<th>Activity Type</th>
			<th>Party</th>
			<th>Invoice Number</th>
			<th>Invoice Date</th>
			<th>Total Attchament</th>
		</tr>
			
		<?php	
			$index = 1;
			for( $i = 0; $i < count($rsl); $i++ ) 
			{
				$html = "";
				$html .= "<tr>";
				$html .= "	<td>".$index."</td>";
				$html .= "	<td></td>";
				$html .= "	<td>".$rsl[$i]["DocNumber"]."</td>";
				$html .= "	<td>".$rsl[$i]["SubmitDate"]."</td>";
				$html .= "	<td>".$rsl[$i]["shortName"]."</td>";
				$html .= "	<td>".$rsl[$i]["activityType"]."</td>";
				$html .= "	<td>".$rsl[$i]["BillParty"]."</td>";
				$html .= "	<td>".$rsl[$i]["invoiceNumber"]."</td>";
				$html .= "	<td>".$rsl[$i]["InvoiceDate"]."</td>";
				$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["TotalRecord"], 2, ",",".")."</td>";
				$html .= "</tr>";
					
				echo $html;
				$index++;
			}
					
		?>
								
	</table>
	<div class="height-10"></div>