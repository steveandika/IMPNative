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

<body style="overflow-y:auto!important">
  <script language="php">
    if (!isset($_SESSION["uid"])) {
      $url="../"; 
 	  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
    } 
	else { 
      include_once ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 	
	  openDB();
	  include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");
	  
/*      include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard_beta.php");*/
		
	  echo "<div style='margin-left:0;width:100%;'>"; 		
	  
      if (isset($_GET["src"])) {
	    $sourcepage = base64_decode($_GET["src"]);  				
		include($sourcepage);
	  }
	  
	  echo "</div>";
    }	
  </script>
  
  <div class="wrapper">
    <div style="padding-top:20px">
	
		<div class="w3-row-padding">
	    
			<div class="w3-third">
				<div class="bluebox">
					<?php
						$html = '';
						$html .= '<div class="bluebox-link">';
						$html .= ' <a href="'.$defHTML.'/dashboard/EDI.7z" style="color:#EAF937;text-decoration:none!important">';
						$html .= ' Electronic Data Interchange (EDI)</a></div>';
						$html .= '<div class="bluebox-desc">Updated: Feb 01, 2021 07:23</div>';
			   
						echo $html;
					?> 			 
				</div>
		</div>  
	    <div class="w3-third"></div>
	    <div class="w3-third">
          <div class="bluebox">
			<?php
			   $html = '';
		       $html .= '<div class="bluebox-link">';			   
		       $html .= ' <a href="'.$defHTML.'/e-imp/dashboard/FinTool2_4.zip" style="color:#EAF937;text-decoration:none!important">';
			   $html .= ' Finance Tool</a></div>';
			   $html .= '<div class="bluebox-desc">Updated: Jan 10, 2022 07:05</div>';
			   
			   echo $html;
			?> 			 
		  </div>		
		</div>
		
		</div>
	  
		<div class="w3-row-padding">
		</div>
    </div>
  </div> 
  
</body>
</html>