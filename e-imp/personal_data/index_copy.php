<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  
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
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
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
	  include("../asset/libs/responsive-menu.php");
	</script>	
    <header>   
	  
	  <div class="w3-row-padding">	   
	    <div class="w3-quarter"> <!-- Categories -->
		  <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Categories</h2>
		  <ul class="accordion">		  
		    <script language="php">
			  if(validMenuAccess("11")==1) { 
			</script>
		        <li onclick="myFunction('emp')"><a href="#"><i class="fa fa-star-half-o"></i> &nbsp;Employee</a>
                  <div id="emp" class="w3-hide">
                    <ul class="sub-menu">				
				      <li><a href="?show=ltem"><i class="fa fa-angle-double-right"></i> Registered Employee</a></li>					
				      <li><a href="?show=frgem"><i class="fa fa-angle-double-right"></i> Employee Form</a></li>
			        </ul>
                  </div>		
			    </li>
			    <li onclick="myFunction('func')"><a href="#"><i class="fa fa-cog"></i> &nbsp;Function</a>
                  <div id="func" class="w3-hide">
                    <ul class="sub-menu">				
				      <li><a href="?show=fcdpt"><i class="fa fa-angle-double-right"></i> Registered Function</a></li>					
				      <li><a href="?show=fcadd"><i class="fa fa-angle-double-right"></i> New Function</a></li>
			        </ul>
                  </div>		
				</li>
		    <script language="php">
			  }
			  if(validMenuAccess("12")==1) { 			  
			</script>  
		 	    <li onclick="myFunction('users')"><a href="#"><i class="fa fa-user"></i> &nbsp;User</a>
                  <div id="users" class="w3-hide">
                    <ul class="sub-menu">				
				      <li><a href="?show=ru"><i class="fa fa-angle-double-right"></i> Registered Users</a></li>					
				      <li><a href="?show=cu_a"><i class="fa fa-angle-double-right"></i> Create User and Setup Access</a></li>
			        </ul>
                  </div>				
				</li>
		    <script language="php">
			  }
			  if(validMenuAccess("14")==1) { 
       		</script>	
			    <li><a href="?show=gr_rp"><i class="fa fa-object-group"></i> Group Repair</a></li>
		    <script language="php">
			  }
            </script>			
		  </ul>
		</div>
		<div class="w3-threequarter"> <!-- Page Content -->
		  <div id="result">
            <script language="php">
	          if(isset($_GET["show"])) {
	            if($_GET["show"] == "ltem") { include("emp_list.php"); }		
			    if($_GET["show"] == "frgem") { include("emp_reg.php"); }		
	            if($_GET["show"] == "fcdpt") { include("function.php"); }	
                if($_GET["show"] == "fcadd") { include("manage-function.php"); }	       
                if($_GET["show"] == "ru") { include("users_list.php"); }	
				if($_GET["show"] == "cu_a") { include("user_reg.php"); }	
				if($_GET["show"] == "gr_rp") { include("reg_group.php"); }
 		      }		  
			</script>  
		  </div>
		</div>
		
	  </div> <!-- End Of Row Padding-->

    </header>	  
  </div><!-- container -->  
</body>
</html>  

<script language="php">
  }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="../asset/js/sweetalert2.min.js"></script>   
<script src="../asset/js/classie.js"></script>
<script src="../asset/js/gnmenu.js"></script> 
<script>
  new gnMenu( document.getElementById('gn-menu') );

  function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
  }
</script>

<script type="text/javascript">
  function openPage(urlvar) { $("#result").load(urlvar); }
  function loadurl(urlvar) { location.replace(urlvar); }
</script>
