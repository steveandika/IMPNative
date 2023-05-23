<!DOCTYPE html>
<html>
	<head>  
		<meta charset="utf-8"> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
		<title>IMP | Integrated Container System</title>
		<link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />    
		<link rel="stylesheet" type="text/css" href="../asset/css/master.css"  /> 
	</head>
	<body>
		<?php
			include($_SERVER["DOCUMENT_ROOT"]."/asset/libs/new_db.php");
	
			if((isset($_GET["h"])) && (isset($_GET["doc"])) && (isset($_GET["shpl"]))) 
			{
				$obj = new DatabaseClass ();
		
				$op = $_GET["doc"];
				$wh = $_GET["h"];
				$liner = $_GET["shpl"];
				if($op == "EoRIConS")
				{
					$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSGrouping Where shortName = '$liner' And workshopID = '$wh'");					
				}
		
				if($op == "EoRPDF")
				{
					$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSPDFGrouping Where shortName = '$liner' And workshopID = '$wh'");				
				}
				
				include("layout1mntreor.php");			
			}
		?>
	</body>
</html>	