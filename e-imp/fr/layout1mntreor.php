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
			<div class="height-10"></div>
			<table class="w3-table w3-bordered">
				<tr>
					<th>Index</th>
					<th>Shipping Line</th>
					<th>Hamparan</th>
					<th>Owner</th>
					<th>User 1</th>
					<th>User 2</th>
					<th>3rdParty</th>
					<td>Total Line</th>
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
							$html .= "	<td>".$rsl[$i]["shortname"]."</td>";
							$html .= "	<td>".$rsl[$i]["workshopID"]."</td>";
							$html .= "	<td>".$rsl[$i]["Owner"]."</td>";
							$html .= "	<td>".$rsl[$i]["User1"]."</td>";
							$html .= "	<td>".$rsl[$i]["User2"]."</td>";
							$html .= "	<td>".$rsl[$i]["ThirdParty"]."</td>";
							$html .= "	<td>".$rsl[$i]["RecordCount"]."</td>";
							$html .= "</tr>";
							
							echo $html;
							$i++;
						}
					}
					
					
				?>
								
			</table>
		</div>
	</body>	
</html>	