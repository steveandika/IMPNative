<script language="php">
  session_start();
  $defHTML = $_SESSION['defurl'];
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>IMP | Integrated Container System</title>
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />    
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />  
  <script src="asset/js/modernizr.custom.js"></script> 	
  <script src="asset/js/jquery.min.2.1.1.js"></script>    

</head>

<body style="overflow-y:auto!important">aaa
  <script language="php">
    if (!isset($_SESSION["uid"])) {
      $url="../"; 
 	  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
    } 
	else { 
      include_once ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 	
	  openDB();
	  include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php")
	  
/*      include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard_beta.php");*/
		
	  echo "<div style='margin-left:0;width:100%;'>"; 		
	  
      if (isset($_GET["src"])) {
	    $sourcepage = base64_decode($_GET["src"]);  				
		include($sourcepage);
	  }
	  
	  echo "</div>";
    }	
  </script>

  <div class="webfooter">
    I-ConS © <?php echo date("Y")." | ";
	               $ip = $_SERVER['REMOTE_ADDR']; 
                   $dataArray = json_decode(file_get_contents("http://ipinfo.io/"));
                   /*var_dump($dataArray);*/
				   echo "<strong>".$dataArray->region."</strong> | ".$dataArray->hostname;?>
  </div>  
</body>
</html>