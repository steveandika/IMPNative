<?php
	$app = "";

	if(isset($_POST['appname']))
	{
		include ($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");
	
		$DBstate = "";
		$DBstate = openDB();

		if($DBstate != "connected")
		{
			$app = "An error occured during creation link to main DB.";
		}
		else 
		{
			$varappname = $_POST['appname'];
			$varappver = $_POST['appver'];
			
			$qry = "select * from App_VER where appName = '$varappname';";
			$stmt = mssql_query($qry);
			if(mssql_num_rows($stmt) > 0) 
			{
				$row = mssql_fetch_array($stmt);
				
				if($row[1] == $varappver) { $app = "ok"; }	
				else { $app = "Update";	}		
			}
			else
			{
				$app = "Unknown application name was detected. Ensure the application was listed on DB.";
			}		
			
			mssql_free_result($stmt);
		}
	}	
	else 
	{
		$app = "An error occured during initiate application.";
	}	
	echo $app;
?>