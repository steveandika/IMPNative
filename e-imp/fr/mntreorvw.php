<?php
	include("new_db.php");
	
	if(isset($_POST["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_POST["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_rows("shortName,workshopID" ,"userID='root'" ,"VIEW_MonitoringWaitingInv GROUP BY shortName,workshopID");
			include("layout1mntreor.php");
		}
	}
?>