<?php
	session_start();   
	
	$oldval = "";
	$newval = "";
	$uid= "";
	$url = "";
	
	if((isset($_POST['passwd_lama'])) && (isset($_POST['passwd_new']))) 
	{
		$oldval = $_POST['passwd_lama'];
		$newval = $_POST['passwd_new'];
		$uid = $_SESSION['uid'];
	
		$query = "Select * From userProfile Where userID='$uid' And accessKey='$oldval'";

		$result=mssql_query($query);	
		if(mssql_num_rows($result) >= 1) 
		{
			mssql_free_result($result);
			$query = "Update userProfile Set accessKey='$newval', 
						lastUpdateAccessKey=CONVERT(VARCHAR(10), GETDATE(), 126) 
						Where userID='$uid' And accessKey='$oldval';
					  Insert Into userLogAct(userID, dateLog, DescriptionLog) 
						Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),'Password Replaced'); ";	
			$result = mssql_query($query);
			
			$url = "confirmed";				
		}
		else {
			mssql_free_result($result);
			$url = "failed" ;
		}  
    }	
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

	<body> 
		<?php 
			if (!isset($_SESSION["uid"])) 
			{
				$url = "/"; 
				echo "<script type='text/javascript'>location.replace('$url');</script>"; 
			} 	
			else 
			{   
				include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 
				openDB();
				include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");
		?>
		
		<div class="w3-container">
			<div class="wrapper">

		<?php
				if($newval == "") 
				{
					include ("request_ch_pswd.php");
				}
				else 
				{
					if($url == "confirmed")
					{
						include ("confirmed.php");
					}
					else 
					{
						include ("failed.php");
					}
				}
		?>
			
			</div>
		</div>

		<script>
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
		
		<?php
			}
		?>
  
	</body>
</html>  