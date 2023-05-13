<?php
	include("../asset/libs/new_db.php");
	
	if(isset($_POST["HampName"])) 
	{
		$obj = new DatabaseClass ();
		
		$op = $_POST["HampName"];
		if($op == "EoRIConS")
		{
			$rsl = $obj -> get_listEoRWaitingBilled();
			include("layout1mntreor.php");
		}
	}
?>