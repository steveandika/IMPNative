<?php
	include("../asset/libs/new_db.php");
	
	echo "Aaaa";
	if(isset($_GET["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_GET["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_rows("shortName,workshopID" ,'' ,"VIEW_MonitoringWaitingInv GROUP BY shortName,workshopID");
	echo "<pre>";  
	print_r($rsl);  
	echo "</pre>";  
		}
	}
?>