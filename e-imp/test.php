<?php
  include ($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");

  //$auth = validateLogin();
  $auth = openDB();
  echo $auth; 
?>