<?php
    $msg = "";
	
	if(isset($_POST['estimateNo']))
	{	
		$estimateNo = $_POST['estimateNo'];
		
		include($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");
		
		$msg = openDB();
		if($msg != "connected")
		{
			$msg = "";
		}
		else 
		{
			$msg = "";
			$qry = "select repairID from CleaningDetail a inner join CleaningHeader b on b.cleaningID = a.cleaningID
					where estimateID = '$estimateNo'";
			$stmt =  mssql_query($qry);
			if(mssql_num_rows($stmt) > 0) 
			{
				$row = mssql_fetch_array($stmt);
				$msg = $row[0];
			}		
			mssql_free_result($stmt);
		}
	}
	
	echo $msg;
?>