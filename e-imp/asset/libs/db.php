<?php  
  $server='192.168.1.5, 1435'; 
  $dbSQL=mssql_connect($server, 'sa', 'Pswd120907');
  if (!$dbSQL) { 
    die('Something went wrong while connecting to MSSQL'); 
  } else { 
      mssql_select_db('CSSCY');  
    }
?>