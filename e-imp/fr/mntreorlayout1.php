	<?php		
		$obj = new DatabaseClass ();
		$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSGrouping");
	?>
	
	<table class="w3-table w3-bordered">
		<tr>
			<th>Index</th>
			<th></th>
			<th>Shipping Line</th>
			<th>Hamparan</th>
			<th style="text-align:right;">Owner</th>
			<th style="text-align:right;">User 1</th>
			<th style="text-align:right;">User 2</th>
			<th style="text-align:right;">3rdParty</th>
			
			<?php	
				$index = 0;
				if(!isset($_SESSION["uid"]))
				{
					$index = 1;
					for( $i = 0; $i < count($rsl); $i++ ) 
					{
						$html = "";
						$html .= "<tr>";
						$html .= "	<td>".$index."</td>";
						$html .= "	<td>".$rsl[$i]["shortName"]."</td>";
						$html .= "	<td>".$rsl[$i]["workshopID"]."</td>";
						$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["Owner"], 2, ",",".")."</td>";
						$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User1"], 2, ",",".")."</td>";
						$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User2"], 2, ",",".")."</td>";
						$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["ThirdParty"], 2, ",",".")."</td>";
						$html .= "</tr>";
					}
							
					echo $html;
					$i++;
					$index++;
				}
			?>
								
	</table>
	<div class="height-10"></div>