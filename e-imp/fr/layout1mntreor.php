<!DOCTYPE html>
<html>
	<head>  
		<meta charset="utf-8"> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
		<title>IMP | Integrated Container System</title>
		<link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />    
		<link rel="stylesheet" type="text/css" href="asset/css/master.css" />  
	</head>
	<body>		
		<div class="w3-container">
			<table class="w3-table w3-bordered">
				<tr>
					<th>Index</th>
					<th>Shipping Line</th>
					<th>Hamparan</th>
				</tr>
				
				<?php					
					if(!isset($_SESSION["uid"]))
					{
						for( $i = 0; $i < count( $rsl->data ); $i++ ) 
						{
							$html = '';
							$html .= "<tr>";
							$html .= "	<td>".$i++."</td>";
							$html .= "	<td>".$data[i]["shortname"]."</td>";
							$html .= "	<td>".$data[i]["workshopID"]."</td>";
							$html .= "</tr>";
							echo $html;
						}
					}
					
					
				?>
								
			</table>
		</div>
	</body>	
</html>	