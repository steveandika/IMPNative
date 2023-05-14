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
		<script src="asset/js/jquery.min.2.1.1.js"></script>    
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
  		  		
			}	
		?>
		
		<div class="wrapper">
		  <span><b>HITS: </b></span>
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
					//document.getElementById("sideBar").style.width = "250px";	
				} 
				else 
				{
					document.getElementById("sideBar").style.left = "0px";	
					//document.getElementById("sideBar").style.width = "100%";
				}		  
			}

			function dropDownLib() 
			{
				var x = document.getElementById("sideBar");

				if (x.className.indexOf("w3-show") == -1) 
				{
					x.className += " w3-show";
				} 
				else 
				{ 
					x.className = x.className.replace(" w3-show", "");
				}
			}
		</script>
 
	</body>
</html>