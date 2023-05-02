<?php
  session_start();   
 ?>
 
<!DOCTYPE html>
<html >
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <meta name="author" content="Edmund" />
  <title>I-ConS | Cost Center</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  
  <script src="../asset/js/modernizr.custom.js"></script>  
  <script src="../asset/js/sweetalert2.min.js"></script>
  <script src="../asset/js/jquery.min.2.1.1.js"></script> 

  <style>
    .navbar-label {outline:none;color:#2196F3;font-weight:400;font-size:12px;}
	.display-form-shadow {-webkit-box-shadow: 0 8px 6px -6px black;-moz-box-shadow: 0 8px 6px -6px black;box-shadow: 0 8px 6px -6px black;}    
	.form-main {width:850px;height:auto;border:1px solid #d5d8dc;margin:0 auto;background:#fdfefe}
	.form-header {font: 400 18px/35px Rajdhani, Helvetica, sans-serif;color:#fff;padding:0 10px;border-bottom:1px solid #d7dbdd;background:#2a32ec;letter-spacing:.07em}	
	.main-button_light-blue {border:1px solid #2196F3;color:#2196F3!important;font-weight:500}
    .flex-container {padding-left:20px;margin:0;display: -webkit-box;display: -moz-box;display: -ms-flexbox;display: -webkit-flex;display: flex;
                   -webkit-flex-flow: row wrap;justify-content: flex-end;}
    .flex-item {width: auto;padding:3px 3px}	

    #style-4::-webkit-scrollbar {width:10px;background-color: #F5F5F5;}
	#style-4::-webkit-scrollbar-track {border-radius: 10px;background: rgba(0,0,0,0.1);border: 1px solid #ccc;}
    #style-4::-webkit-scrollbar-thumb {border-radius: 10px;background: linear-gradient(left, #fff, #e4e4e4);border: 1px solid #aaa;}	
	#style-4::-webkit-scrollbar-thumb:hover {background: #fff;}	
	#style-4::-webkit-scrollbar-thumb:active {background: linear-gradient(left, #22ADD4, #1E98BA);}	
	
	.style-input{padding:3px;display:block;border:none;border-bottom:1px solid #d0d3d4;width:100%}
	.style-select{width:100%;border:1px solid #d0d3d4!important;height:30px;}
	.style-border{border:1px solid #d0d3d4!important}
	.style-input:focus{border-bottom:1px solid #d0d3d4;}
	.search-textbox{padding:3px;height:29px;border:1px solid #d0d3d4;width:200px}	
	.container-search{float:right}
	.topnav {overflow:hidden;background-color:#e9e9e9;height:40px;padding:5px}	

    @media all and (max-width : 768px) { 
	  html,body{font-size:11px!important}
	  .form-main {width:99%;height:auto}
	  .navbar-label {display:none}
	  .form-main-inside {width:98%}	  
	  .search-textbox{width:150px}	
    }			
  </style>  
</head>

<body style="background:#ecf0f1">   

<?php 
  if (!isset($_SESSION["uid"])) {
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  } 	
  else {   
	include("../asset/libs/db.php");
    include("../asset/libs/dashboard.php");
	mssql_close($dbSQL);
	
	echo '<div class="wrapper">
	       <div class="height-10"></div>';
    
    echo ' <div id="progress">';	
	include('costcenter-list.php');
	include('form.php');
	echo ' </div>';
	
	echo ' <div class="height-5"></div>
	      </div>';
		  
	if(isset($_GET['show']) && isset($_GET['act'])) {
	  if($_GET['act'] == 'adding' || $_GET['act'] == 'edit') {
	    echo "<script>document.getElementById('ccFormBlock').style.display='block';</script>"; 	  
      }		  
	}
  }	
?>
  <script>
     window.addEventListener("resize", function(){
	  var w = window.innerWidth;
      if (w <= 800) {
        document.getElementById("mySidenav").style.width = "0";		  
	  } else {
        document.getElementById("mySidenav").style.width = "200px";
		document.getElementById("mySidenav").style.backgroundColor = "#fbfcfc";
      }		
     });
  </script>   
  

</body>
</html>  