<?php
  session_start();  

  include ($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");

  $auth = validateLogin();
	
  if ($auth == "denied") { 
    $url = "auth_failed";	
	echo "<script type='text/javascript'>location.replace('$url');</script>";
  } 
  else {
    $url = "index";  	 
    echo "<script type='text/javascript'>location.replace('$url');</script>";				
  }  
?>