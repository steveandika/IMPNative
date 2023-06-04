<?php
  session_start();  
?>

<!DOCTYPE html>
<html>
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
	</head>

	<body>
  
		<?php
			include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 
	  
			if (!isset($_SESSION["uid"])) 
			{
				$url="../"; 
				echo "<script type='text/javascript'>location.replace('$url');</script>"; 
			} 
			else 
			{ 
				openDB();
				include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");
		?>
		
			<div class="w3-container">
				<div class="wrapper"></div> 				
			</div>

			<script>
				var dropdown = document.getElementsByClassName("dropdown-btn");
				var i;

				for (i = 0; i < dropdown.length; i++) 
				{
					dropdown[i].addEventListener("click", function() 
					{
						this.classList.toggle("active");
						var dropdownContent = this.nextElementSibling;
						if (dropdownContent.style.display === "block") 
						{
							dropdownContent.style.display = "none";
						} 
						else 
						{
							dropdownContent.style.display = "block";
						}
					});
				}
				
				function toggleSideNav() 
				{		
					if (document.getElementById("sideBar").style.left == "0px") 
					{
						document.getElementById("sideBar").style.left = "-250px";	
					} 
					else 
					{
						document.getElementById("sideBar").style.left = "0px";	
					}		  
				}
				
				function toggleUser() 
				{		
					if (document.getElementById("menuUserDiv").style.display == "none") 
					{
						document.getElementById("menuUserDiv").style.display = "block";	
					} 
					else 
					{
						document.getElementById("menuUserDiv").style.display = "none";	
					}		  
				}				

			</script>
		
		<?php
			}
		?>
	</body>
</html>