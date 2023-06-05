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
	</head>

	<body style="overflow-y:auto"> 
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
			
				$masterData = array("ROK2171", "JOK001","ROOT")
			?>
  
			<div class="w3-container">
				<div class="wrapper">
					<div class="se-pre-con" id="loader-icon" style="display:none"></div>
				
				<?php 
					if (in_array(strtoupper($_SESSION['uid']), $masterData)) 
					{
						if(isset($_GET['show']) && $_GET['show']=="pr_mnr") { include($_GET['show'].".php"); }
						if(isset($_GET['do'])) 
						{
							include("pr_mnr.php");	
	                    
							if($_GET['do']=="upload") {include("price_list.php");}
							if($_GET['do']=="upload-log") {include($_GET['do'].".php");}
							if($_GET['do']=="cedex-manage") {include("cedexSearch.php");}	  
							if($_GET['do']=="cedexQuery") {include($_GET['do'].".php");}  	  
						} 
					}	
				?> 
		
					<div id="result">
				
				<?php 
					if(isset($_GET["show"]) && in_array(strtoupper($_SESSION['uid']), $masterData)) 
					{
						if($_GET["show"] == "vcust") 
						{ 
							include("customer.php"); 
							include("manage_cust.php");  
						}
						if($_GET["show"] == "wrk") {include("workshop.php");}	  
						if($_GET["show"] == "cedexBrowse") {include("cedexSearch.php");}		  
					}  
				?>
				
					</div>  
		
					<div id="process">
				
				<?php 
					if(isset($_GET['job']) && in_array(strtoupper($_SESSION['uid']), $masterData)) 
					{ 
						if($_GET['job']=="cm") {include("cedexManage.php");}  	
					} 
				?>	 
				
					</div> 
				</div><!-- wrapper -->  
			</div>
  
		<?php    
				if($_GET["show"] == "vcust" && $_GET["new"] == "1") 
				{
					echo "<script>document.getElementById('id01').style.display='block';</script>"; 
				}
		?>  
 
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
			</script>  

		<?php
			}
		?>	
    
	</body>
</html>  