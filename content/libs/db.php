<?php
  $failed_reach_db=0;
  $server = 'DESKTOP-JF0QIB0\SQLIMP,1345';  
  $dbSQL = mssql_connect('192.168.1.5,1435', 'sa', 'Pswd120907');
  if (!$dbSQL) { $failed_reach_db=1; } 
  else { mssql_select_db("CSSCY"); }
?>