	<?php		
		$obj = new DatabaseClass ();
		$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringWaitingInv");
	?>
	
	<h6 class='w3-text-blue-grey' style='font-weight:600;'>DOKUMEN PENAGIHAN Menunggu Invoice ID</h6>
	<div class="height-10"></div>
	<table class="w3-table w3-bordered">
		<tr>
			<th>Index</th>
			<th></th>
			<th>Document Number</th>
			<th>Submit Date</th>
			<th>Shipping Line</th>
			<th>Party</th>
			<th>Activity Type</th>
			<th style="text-align:right;">Total Document of EoR</th>
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
				$html .= "	<td>".$rsl[$i]["Party"]."</td>";
				$html .= "	<td>".$rsl[$i]["activityType"]."</td>";
				$html .= "	<td style='text-align:right;'>".$rsl[$i]["jumlahEoR"]."</td>";
				$html .= "</tr>";
					
				echo $html;
				$index++;
			}				
		?>
								
	</table>
	<div class="height-10"></div>