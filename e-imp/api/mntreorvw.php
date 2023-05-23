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
		<?php
			include("../asset/libs/new_db.php");
	
			if(isset($_GET["w"])) 
			{
				$obj = new DatabaseClass ();
		
				$op = $_GET["w"];
				$liner = $_GET["shpl"];
				if($op == "EoRIConS")
				{
					$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSGrouping Where shortName = '$liner'");					
				}
		
				if($op == "EoRPDF")
				{
					$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSPDFGrouping Where shortName = '$liner'");				
				}
				
				include("layout1mntreor.php");			
			}
		?>
	</body>
</html>	