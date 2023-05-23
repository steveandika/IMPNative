		<div class="w3-container w3-responsive">
		    <div class="height-10"></div>
			<div class="w3-container w3-light-grey w3-round-large">
				<div class="height-10"></div>
				
				<?php
					$html = "";
					
					if($op == "EoRIConS")
	                {
						$html .= "<h6 class='w3-text-blue-grey' style='font-weight:600;'>MONITORING EoR (IConS) - Belum Ditagihkan </h6>";
					}
					
					if($op == "EoRPDF")
					{
						$html .= "<h6 class='w3-text-blue-grey' style='font-weight:600;'>MONITORING EoR (PDF) - Belum Ditagihkan </h6>";
					}
					
					echo $html;					
				?>
								
				<div class="height-10"></div>
				<table class="w3-table w3-bordered">
					<tr>
						<th>Index</th>
						<th>Shipping Line</th>			
						<th>Hamparan</th>		
						<th>Container ID</th>
						<th>Gate In</th>
						<th>EoR ID</th>
						<th>Currency</th>						
						
					<?php
						$html = '';
						if($op == "EoRIConS")
						{							
							$html .= '<th style="text-align:right;">Owner</th>';
							$html .= '<th style="text-align:right;">User 1</th>';
							$html .= '<th style="text-align:right;">User 2</th>';
							$html .= '<th style="text-align:right;">3rdParty</th>';	
						}
						else 
						{
							$html .= '<th style="text-align:right;">Before Tax</th>';								
						}
						echo $html;
					?>
						
					</tr>
				
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
								$html .= "	<td>".$rsl[$i]["containerID"]."</td>";
								$html .= "	<td>".$rsl[$i]["gateIn"]."</td>";
								$html .= "	<td>".$rsl[$i]["estimateID"]."</td>";
								$html .= "	<td>".$rsl[$i]["currencyAs"]."</td>";								
								
								if($op == "EoRIConS")
								{									
									$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["Owner"], 2, ",",".")."</td>";
									$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User1"], 2, ",",".")."</td>";
									$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User2"], 2, ",",".")."</td>";
									$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["ThirdParty"], 2, ",",".")."</td>";
									$html .= "</tr>";
								}
								
								if($op == "EoRPDF")
								{
									$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["nilaiDPP"], 2, ",",".")."</td>";
								}
							
								echo $html;
								$i++;
								$index++;
							}
						}										
					?>
								
				</table>
				<div class="height-10"></div>
			</div>	
			<div class="height-10"></div>
		</div>