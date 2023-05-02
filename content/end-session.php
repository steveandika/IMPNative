<?php
  session_start();
  include("asset/libs/db.php");
  
  $userid=$_SESSION["uid"];
  session_destroy();
  
  $sql="Update userProfile Set isLogin=0 Where userID='$userid'; ";
  $rsl=mssql_query($sql);
  
  $sql="Delete From userLogAct Where DescriptionLog Like '%LOGIN%' And userID='$userid'; ";
  $rsl=mssql_query($sql);
    
  $url = "//icons.pt-imp.com/log-out";
  echo "<script type='text/javascript'>location.replace('$url');</script>";
?>