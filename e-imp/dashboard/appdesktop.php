<?php
  session_start();
  $defHTML = $_SESSION['defurl'];
?>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>IMP | Integrated Container System</title>
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" /> 
  <script src="../asset/js/jquery.min.2.1.1.js"></script>  
</head>

<body>
	<?php
		if(!isset($_SESSION["uid"])) 
		{
			$url="../"; 
			echo "<script type='text/javascript'>location.replace('$url');</script>"; 
		} 
		else 
		{ 
			include_once ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 	
			openDB();
			include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");

		}	
	?>
  
	<div class="wrapper">
		<div style="padding-top:20px">
	
			<div class="w3-row-padding">
	    
				<div class="w3-third">
					<div class="bluebox">&nbsp;
					</div>
				</div>  
				<div class="w3-third"></div>
				<div class="w3-third">
					<div class="bluebox">
						<?php
							$html = '';
							$html .= '<div class="bluebox-link">';			   
							$html .= ' <a href="'.$defHTML.'/e-imp/dashboard/FinTool2_13.zip" style="color:#EAF937;text-decoration:none!important">';
							$html .= ' Finance Tool ver 2.13</a></div>';
							$html .= '<div class="bluebox-desc">Updated: May 26, 2023 16:10</div>';
			   
							echo $html;
						?> 			 
					</div>		
				</div>
		
			</div>
			<div class="height-10"></div>

    </div>
  </div> 
  
<script>
	var dropdown = document.getElementsByClassName("dropdown-btn");
	var i;

	for (i = 0; i < dropdown.length; i++) {
		dropdown[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var dropdownContent = this.nextElementSibling;
			if (dropdownContent.style.display === "block") {
				dropdownContent.style.display = "none";
			} 
			else {
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
</script>
  
</body>
</html>