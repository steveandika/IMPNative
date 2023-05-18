<?php
	session_start();
?>

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
		<div class="w3-container w3-responsive">
			<div class="height-10"></div>
			<div class="w3-container w3-light-grey">
				<div class="height-10"></div>
				<h6 class='w3-text-blue-grey' style='font-weight:600;'>MONITORING EoR (PDF) - Belum Ditagihkan (Detail)</h6>
				<div class="height-10"></div>
				<table class="w3-table w3-bordered">
					<tr>
						<th>Index</th>
						<th>Shipping Line</th>			
						<th>Container ID</th>
						<th>Gate In</th>
						<th>EoR ID</th>					
						<th style="text-align:right;">Before Tax</th>
					</tr>
					
					<?php
						if(!isset($_SESSION["uid"])) 
						{
							$url="../"; 
							echo "<script type='text/javascript'>location.replace('$url');</script>"; 
						} 
						else 
						{
							$liner = $_GET["sh"];
							$document = $_GET["d"];	
							
							include("../asset/libs/new_db.php");
							$obj = new DatabaseClass ();
							$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringWaitingInv WHERE shortname='$liner' and DocNumbr='$document'");
			
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
								$html .= "	<td style='text-align:right;'>".number_format($rsl[$i]["nilaiDPP"], 2, ",",".")."</td>";

								$html .= "</tr>";
					
								echo $html;
								$index++;								
							}
						}
					?>
					
				</table>
			</div>
		</div>
	</body>
</html>	