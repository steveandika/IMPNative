<?php
  include ($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");

  $auth = validateLogin();
	
  if ($auth === "denied") { $url = "Authentication was failed";	} 
  else { $url = "Authentication granted"; }  
  
  echo $url;
?>