<script language="php">
  session_start();  
  
  if (!isset($_SESSION["uid"])) {
    $URL="/"; 
	echo "<script type='text/javascript'>location.replace('$URL');</script>"; } 
	
  else { 
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/normalize.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/googlenexus.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/component.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/w3.css" />
  <link rel="stylesheet" type="text/css" href="../asset/css/common.css" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/sweetalert2.min.css" />
  
  <script src="../asset/js/modernizr.custom.js"></script>    
</head>

<body> 
  <div class="container">
    <script language="php">
	  include("../asset/libs/db.php");
	  include("../asset/libs/responsive-menu.php");	  
	</script>
    <header>
	  <div class="tab">
	    <script language="php">
		  echo '<button type="button" onclick=openPage("/e-imp/master.data/") class="w3-blue"><i class="fa fa-bars"></i> &nbsp;Master Data</button>'; 		 	 
		  echo '<button type="button" disabled><i class="fa fa-lock"></i> &nbsp;Port</button>'; 
		</script>
	  </div>
	  
      <div id="content">
	    <script language="php">include("content.php");</script>
	  </div>
		
	</header>
  </div><!-- container -->   
</body>
</html>  

<script language="php">
  mssql_close($dbSQL); }
</script>
  
<script src="../asset/js/classie.js"></script>
<script src="../asset/js/gnmenu.js"></script>
 
<script>
  new gnMenu( document.getElementById('gn-menu') );
  function openPage(urlvar) { location.replace(urlvar); }	
</script>