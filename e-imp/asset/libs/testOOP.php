<?php
	include("new_db.php");
	$obj = new DatabaseClass ();
	
	$rsl = get_rows("count(1) JumlahBaris" ,"userID='root'"  ,"userMenuProfile");
	echo "<pre>";  
	print_r($rsl);  
	echo "</pre>";  	
?>