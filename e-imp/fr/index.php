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
		<link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
		<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
		<script src="../asset/js/modernizr.custom.js"></script>
		<script src="../asset/js/jquery.min.2.1.1.js"></script> 
	
		<style>
			.display-form-shadow {-webkit-box-shadow: 0 8px 6px -6px black;-moz-box-shadow: 0 8px 6px -6px black;box-shadow: 0 8px 6px -6px black;}    
			.form-main {width:95%;height:auto;border:1px solid #d5d8dc;margin:0 auto;background-color:#f1f1f1}
			.form-header {font: 600 15px/25px Calibri, Play, Helvetica, sans-serif;color:#000;padding:0 10px;border-bottom:1px solid #d7dbdd;background:#f1f1f1;letter-spacing:.07em}	

			@media all and (max-width : 768px) { 
				.form-main {width:100%;height:auto;margin:0 auto;}
				.navbar-label {display:none;}
				.flex-container {display: flex;justify-content: center;}
			}	   
		</style>   
	</head>

	<body> 
		<?php 
			if (!isset($_SESSION["uid"])) 
			{
				$url = "/"; 
				echo "<script type='text/javascript'>location.replace('$url');</script>"; 
			} 	
			else 
			{   
				include("../asset/libs/db.php");
				include("../asset/libs/common.php");		
				include("../asset/libs/dashboard.php");
				mssql_close($dbSQL);		
		?>
				
		<div class="w3-container">
			<div class="wrapper">
				<div class="height-10"></div>
				<div class="se-pre-con" id="loader-icon" style="display:none"></div>
			
				<?php
					if(isset($_GET["r"])) 
					{
						if($_GET["r"] == "slhw")   { include("hw_summary.php"); }		  
						if($_GET["r"] == "wsurvey"){ include($_GET["r"].".php"); }		
						if($_GET["r"] == "w_app")  { include("w_app.php"); }		
						if($_GET["r"] == "w_fns")  { include("w_fns.php"); }	
		
						if($_GET["r"] == "w_cl")   { include("wait-cleaning.php"); }		
						if($_GET["r"] == "sm_eor") { include("submitted-eor.php"); }		
						if($_GET["r"] == "sm_mnr") { include("complete_cl_rp.php"); }		
					}  
				?>				
       	  
			</div>
		</div>	

		<script>
			var dropdown = document.getElementsByClassName("dropdown-btn");
			var i;

			for(i = 0; i < dropdown.length; i++) 
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
				if (document.getElementById("menuUserDiv").style.display == "none" || document.getElementById("menuUserDiv").style.display == "") 
				{
					document.getElementById("menuUserDiv").style.display = "block";	
				} 
				else 
				{
					document.getElementById("menuUserDiv").style.display = "none";	
				}		  
			}		

			function dateSeparator(varID) 
			{
				var str = document.getElementById(varID).value;
				panjang = str.length;
				if (panjang==8) 
				{
					var partYear = str.slice(0,4);
					var partMonth = str.slice(4,6); 
					var partDate = str.slice(6,8);
	  
					result = partYear.concat('-', partMonth, '-', partDate);
					document.getElementById(varID).value = result;
				} 		 
			}
  
			function isNumber(evt) 
			{
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;

				if (charCode == 46) 
				{
					return true;
				} 
				else 
				{	  
					if (charCode > 31 && (charCode < 48 || charCode > 57)) 
					{
						return false;
					}
				}      
				return true;
			}   

			jQuery(document).ready(function() 
			{
				jQuery('#loading').fadeOut(3000);
			});
		</script>  
		
		<?php
			}
		?>
     
	</body>
</html>  