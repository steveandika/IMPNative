<?php
  session_start();  
?>

<!DOCTYPE html>
<html style="overflow-y:auto!important">
	<head>  
		<meta charset="utf-8"> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
		<meta name="keywords" content="Container, Logistic, Container Repair, Indo Makmur">
		<meta name="description" content="">
		<meta name="author" content="">  
		<title>IMP | Integrated Container System</title> 
		<link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
		<link rel="stylesheet" type="text/css" href="asset/css/master.css" />
		<script src="asset/js/modernizr.custom.js"></script>  
		<script src="asset/js/jquery.min.2.1.1.js"></script>    
		<script>
			$(window).load(function() 
			{
	 // Animate loader off screen
				$(".se-pre-con").fadeOut("slow");;
			});
		</script>  
	</head>

	<body>
		<div class="w3-container">
			<div class="wrapper">
				<div class="height-20"></div>
				<div class="w3-center"><strong>Wait a moment..</strong></div>  
  
			<?php 
				if (!isset($_SESSION["uid"])) 
				{
					$url = "/"; 
					echo "<script type='text/javascript'>location.replace('$url');</script>"; 
				} 
				else 
				{ 
					if (isset($_GET["src"])) 
					{
						$tempGetString = $_GET["src"];
          
						$isLoadHamparan = "loadHamparan";
						$isLoadDateApp = "loadDateApp";
						$sourcePage = "";
						$formTitle = "";
		
						if (str_rot13($isLoadHamparan)==$tempGetString) { $sourcePage = "dataload-lhw.php"; }			  
						if (str_rot13($isLoadDateApp)==$tempGetString) { $sourcePage = "dataload-app.php"; }			  
					}	
   
					echo "<script type='text/javascript'>location.replace('$sourcePage');</script>";   
				}
			?>
			
			</div>
		</div>	
	
	</body>
</html> 