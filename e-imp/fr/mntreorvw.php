<?php
	include("../asset/libs/new_db.php");
	
	echo $_POST["HampName"];
	if(isset($_POST["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_POST["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_rows("shortName,workshopID" ,'' ,"VIEW_MonitoringWaitingInv GROUP BY shortName,workshopID");
			include("layout1mntreor.php");
		}
	}
?>