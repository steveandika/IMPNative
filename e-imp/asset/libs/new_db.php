<?php  
	$server='192.168.1.5, 1435'; 
  
	$dbSQL = new mssql($server, 'sa', 'Pswd1209078');
	if($dbSQL->connect_error) 
	{ 
		die('Connection failed: '.$dbSQL->connect_error); 
	} 
	else 
	{ 
      echo "connected"; 
    }
?>