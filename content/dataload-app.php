<?php
  session_start();  
?>

<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>IMP | Integrated Container System</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />
  <script src="asset/js/modernizr.custom.js"></script>  
  <script src="asset/js/jquery.min.2.1.1.js"></script>    
  <style>
    body {transition: background-color .5s;}
  </style>

  <script>
    $(window).load(function() {
	 // Animate loader off screen
	  $(".se-pre-con").fadeOut("slow");;
    });
  </script>  
</head>

<body>
  
<?php 
  if (!isset($_SESSION["uid"])) {
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  } 
  else { 
    include("asset/libs/db.php");
    include("asset/libs/common.php");	  
	/*include("valid-menuAccess.php");
	include("asset/libs/fixed-header.php");	*/
	
	$sourcePage = "dataload-app-cr-cc.php"; 
?>  

  <div class="wrapper" style="overflow-y:auto;-webkit-overflow-scrolling: touch;background-color:#ffff!important" >
    <div class="page-title">DataLoad - Estimate Approval, Complete Repair, Complete Cleaning Date</div>
	<div class="height-20"></div>		

<?php   		
	$doc_Name="";
	
	if (isset($_FILES["docName"])) {
	  $doc_Name=basename($_FILES["docName"]["name"]); 
//	  include($sourcePage);
//	  $sourcePage = "dataload-app-process.php"; 
	}	
	include($sourcePage);	
	mssql_close($dbSQL);
  }	
?>  

  </div>
	
</body>
</html> 