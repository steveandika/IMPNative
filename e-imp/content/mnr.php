<?php 
  session_start(); 
  
  $valid_ses =1;
  $loginName ="ADMINISTRATOR";
  
  if (!isset($_SESSION["uid"])) {
  /*	  
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  */	
  } 
  
  if($valid_ses ==1) {
?>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>I-ConS | Maint. & Repair</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>  
  <script src="../asset/js/jquery.min.2.1.1.js"></script>    
  <style>
    body {transition: background-color .5s;}
  </style>
</head>

<body>
  <?php include("../asset/libs/fixed-header_nomenu.php"); ?>
  
  <div id="fixed-menu" style="border-bottom:
</body>
</html>

<?php
  }
?>