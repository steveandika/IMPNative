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
				<table class="w3-table w3-bordered">
					<tr>
						<th>Index</th>
						<th>Shipping Line</th>
						<th>Hamparan</th>
						<th style='text-align:right;'>Owner</th>
						<th style='text-align:right;'>User 1</th>
						<th style='text-align:right;'>User 2</th>
						<th style='text-align:right;'>3rdParty</th>
						<th style='text-align:right;'>Total Line</th>
					</tr>
				
					<?php	
						$index = 0;
						if(!isset($_SESSION["uid"]))
						{
							for( $i = 0; $i < count($rsl); $i++ ) 
							{
								$index = $i +1;
								$html = '';
								$html .= "<tr>";
								$html .= "	<td>".$index."</td>";
								$html .= "	<td>".$rsl[$i]["shortName"]."</td>";
								$html .= "	<td>".$rsl[$i]["workshopID"]."</td>";
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["Owner"], 2, ",",".")."</td>";
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User1"], 2, ",",".")."</td>";
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["User2"], 2, ",",".")."</td>";
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["ThirdParty"], 2, ",",".")."</td>";
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["RecordCount"], 2, ",",".")."</td>";
								$html .= "</tr>";
							
								echo $html;
								$i++;
							}
						}										
					?>
								
				</table>
				<div class="height-10"></div>
			</div>	
		</div>
	</body>	
</html>	