<?php
	include("../asset/libs/new_db.php");
	
	if(isset($_POST["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_POST["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSGrouping");
			include("layout1mntreor.php");
		}
		
		if($op == "EoRPDF")
		{
			$rsl = $obj -> get_listMntrEoRFin("VIEW_MonitoringEstimateICONSPDFGrouping");
			include("layout1mntreor.php");			
		}
	}
?>