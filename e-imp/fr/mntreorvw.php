<?php
	include("../asset/libs/new_db.php");
	
	if(isset($_POST["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_POST["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_rows(implode(",",array("shortName","workshopID")) ,'' ,"VIEW_MonitoringWaitingInv GROUP BY ".implode(",", array("shortName", "workshopID")));
	echo "<pre>";  
	print_r($rsl);  
	echo "</pre>";  
		}
	}
?>