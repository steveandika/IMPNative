	<?php		
		$obj = new DatabaseClass ();
		$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSGrouping");
	?>
	
	<h6 class='w3-text-blue-grey' style='font-weight:600;'>MONITORING EoR (IConS) - Belum Ditagihkan </h6>
	<div class="height-10"></div>
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
        </tr>			
			
		<?php	
			$defHTML = $_SESSION['defurl'];
			$index = 1;
			for( $i = 0; $i < count($rsl); $i++ ) 
			{
				$html = "";
				$html .= "<tr>";
				$html .= "	<td>".$index."</td>";
				$html .= "	<td><a href='fr/mntreorlayout1d.php?sh=".$rsl[$i]["shortName"]."&w=".$rsl[$i]["workshopID"]."' class='w3-button w3-blue w3-round-medium' target='wdetail'>View</a></td>";
				$html .= "	<td>".$rsl[$i]["shortName"]."</td>";
				$html .= "	<td>".$rsl[$i]["workshopID"]."</td>";
				$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["Owner"], 2, ",",".")."</td>";
				$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User1"], 2, ",",".")."</td>";
				$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User2"], 2, ",",".")."</td>";
				$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["ThirdParty"], 2, ",",".")."</td>";
				$html .= "</tr>";
					
				echo $html;
				$index++;
			}
					
		?>
								
	</table>
	<div class="height-10"></div>