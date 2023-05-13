<!DOCTYPE html>
<html>
	<head>  
		<meta charset="utf-8"> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
		<title>IMP | Integrated Container System</title>
		<link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />    
		<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
	</head>
	<body>		
		<div class="w3-container">
			<div class="w3-responsive">
				<div class="height-10"></div>
				
				<?php
					$html = "";
					
					if($op == "EoRIConS")
	                {
						$html .= "<h6 class='w3-text-blue-grey'>Monitoring EoR (IConS) - Belum Ditagihkan </h6>";
					}
					
					if($op == "EoRPDF")
					{
						$html .= "<h6 class='w3-text-blue-grey'>Monitoring EoR (PDF) - Belum Ditagihkan </h6>";
					}
					
					echo $html;					
				?>
								
				<div class="height-10"></div>
				<table class="w3-table w3-bordered">
					<tr>
						<th>Index</th>
						<th>Shipping Line</th>
						<th>Hamparan</th>
						<?php 
							$html = "";
							
							if($op == "EoRIConS")
							{
								$html .= "<th style='text-align:right;'>Owner</th>";
								$html .= "<th style='text-align:right;'>User 1</th>";
								$html .= "<th style='text-align:right;'>User 2</th>";
								$html .= "<th style='text-align:right;'>3rdParty</th>";
							}
							
							if($op == "EoRPDF")
							{
								$html .= "<th style='text-align:right;'>Total Before Tax</th>";								
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
		</div>
	</body>	
</html>	