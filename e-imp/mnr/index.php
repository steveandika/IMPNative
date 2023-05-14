<script language="php">
  session_start();  
</script>

<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
	<meta charset="utf-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
	<title>IMP | Integrated Container System</title>
    <meta name="keywords" content="Container, Logistic, Container Repair, Indo Makmur">
    <meta name="description" content="">
    <meta name="author" content="">  
	<link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
	<link rel="stylesheet" type="text/css" href="../asset/css/master.css" /> 
	<script src="../asset/js/jquery.min.2.1.1.js"></script>  
  
	<style>
		.display-form-shadow {-webkit-box-shadow: 0 8px 6px -6px black;-moz-box-shadow: 0 8px 6px -6px black;box-shadow: 0 8px 6px -6px black;}    
		.form-main-prnw {width:95%;height:auto;border:1px solid #d5d8dc;margin:0 auto;background:#fdfefe}
		.form-main {width:98%;height:auto;border:1px solid #d5d8dc;margin:0 auto;background-color:#f1f1f1}
		.form-header {font: 400 15px/25px Rajdhani, Helvetica, sans-serif;color:#fff;padding:0 10px;border-bottom:1px solid #d7dbdd;background:#616161}		
		.hdr25-light-grey {font: 600 15px/25px Rajdhani, Helvetica, sans-serif;color:#000;padding:0 10px;border-bottom:1px solid #d7dbdd;background:#f1f1f1;letter-spacing:.07em}	
		.hdr25-dark-grey {font: 600 15px/25px Rajdhani, Helvetica, sans-serif;color:#fff;padding:0 10px;border-bottom:1px solid #d7dbdd;background:#616161;letter-spacing:.07em}	   

   
		.navbar-label {outline:none;color:#2196F3;font-weight:400;font-size:12px;}
		.main-button_light-blue {padding:2px 15px;outline:none;border:1px solid #2196F3;color:#2196F3!important;font-weight:500}
  
		.dropdown {overflow:hidden;border-bottom:1px solid #e9e9e9;height:40px;z-index:9999}   
		.dropdown ul {padding:0;}	
		.dropdown ul li {display:block;line-height:20px;float:left;overflow:hidden;}   
		.dropdown ul li a {text-decoration:none;cursor:pointer;padding-right:10px;color:#2196F3;outline:none;font-weight:500} 
		.dropdown ul li a:hover{text-decoration:underline} 

		.hardnotif {height:auto;border:1px solid #FF0000;margin:0 auto;background:#FFC0CB}	 
	</style>       
</head>

<body> 
	<?php
		if (!isset($_SESSION["uid"])) 
		{
			$url = "/"; 
			echo "<script type='text/javascript'>location.replace('$url');</script>"; 
		} 	
		else {   
			include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 
			openDB();
			include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");
	?>
  
			<div class="wrapper">				
				<div id="result">
	
					<?php
						if (isset($_POST["do"]) && $_POST["do"]=="cruddate") {include("manage-cr.php");}
						if(isset($_GET['do'])) 
						{
							if($_GET['do']=="hw_registry") {include("gatein_header.php");}
							if($_GET["do"]=="domnr") {include("mngunt.php");}
							if($_GET["do"]=="sitesvy") {include("manage-photo.php");}
							if($_GET["do"]=="cruddate" ) {include("manage-cr.php");}
				
							if(isset($_GET['do']) && $_GET['do']=="trash") 
							{
								$query="Declare @BookID VarChar(30);
										Select @BookID=BookID From RepairHeader Where estimateID='".$_GET['id']."';
										
										Update RepairHeader Set BookID=CONCAT(@BookID,'*'), statusEstimate='CANCEL' Where estimateID='".$_GET['id']."'; 
	        
										Insert Into userLogAct(userID, dateLog, DescriptionLog) 
										Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Canceling Submitted Estimate ','".$_GET["id"][$i]."')); ";	
								$result=mssql_query($query);
							
								echo '<div class="addon-form">
										<div class="w3-container"><div class="w3-panel w3-green">
											<h3>Success </h3><p>Estimate Number '.$_GET["id"].' has been cancelled as per request. And Action has been logged</p>
										</div></div>
										<div class="height-5"></div>			
									<	a href="?p=approval" style="cursor:pointer" class="w3-btn w3-border w3-light-grey w3-text-blue">Need Approval List</a></div>'; 
							}		 
						}
					?>
  
				</div>	  
			</div>	
  
	<?php
		}
	?>

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
		if (document.getElementById("sideBar").style.left == "0px") {
			document.getElementById("sideBar").style.left = "-250px";	
			//document.getElementById("sideBar").style.width = "250px";	
		} 
		else {
			document.getElementById("sideBar").style.left = "0px";	
			//document.getElementById("sideBar").style.width = "100%";
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

	function addRow_mine(tableID) 
	{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount < 100)
		{							// limit the user from creating fields more than your limits
			var row = table.insertRow(rowCount);
			var colCount = table.rows[0].cells.length;
			for(var i=0; i<colCount; i++) 
			{
				var newcell = row.insertCell(i);
				var insrow = table.rows[rowCount-1];		  
				newcell.innerHTML = insrow.cells[i].innerHTML;
			}
		}
		else {
			alert("Maximum record per EOR is 100.");			   
		}
	}

	function deleteRow_mine(tableID) 
	{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;

		//hitung jumlah baris yang ditandai untuk hapus	
		for(var i=1; i<rowCount; i++) 
		{
			var flag = 0;
			var row = table.rows[i];
			var chkbox = row.cells[0]; 	  
			if(chkbox != null) { flag++; }	 
		}	

		//proses hapus	
		var terhapus = 0;
		var baris = 1;
		var stop = 0;
  
		while(terhapus < flag) 
		{	
			rowCount = table.rows.length;
			for(var j=1; j<rowCount; j++) 
			{  
				row = table.rows[j];
				alert(row.cells[0]);
				chkbox = row.cells[0];
				if(chkbox != null) 
				{		  
					table.deleteRow(j);
					j = rowCount +1;
					terhapus++;
				}   
			}
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
		else {	  
			if (charCode > 31 && (charCode < 48 || charCode > 57)) 
			{
				return false;
			}
		}	  
    
		return true;
	}      
</script>
  
</body>
</html>  