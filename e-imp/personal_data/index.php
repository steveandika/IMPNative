<script language="php">
  session_start();  
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <meta name="author" content="Edmund" />
  <title>IMP | Integrated Container System</title> 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
  <script src="../asset/js/modernizr.custom.js"></script>  
  <!-- <script src="../asset/js/sweetalert2.min.js"></script> -->
  <script src="../asset/js/jquery.min.2.1.1.js"></script> 
  <style>    
    #style-4::-webkit-scrollbar {width:4px;background-color: #F5F5F5;}
	#style-4::-webkit-scrollbar-track {border-radius: 10px;background: rgba(0,0,0,0.1);border: 1px solid #ccc;}
    #style-4::-webkit-scrollbar-thumb {border-radius: 10px;background: linear-gradient(left, #fff, #e4e4e4);border: 1px solid #aaa;}	
	#style-4::-webkit-scrollbar-thumb:hover {background: #fff;}	
	#style-4::-webkit-scrollbar-thumb:active {background: linear-gradient(left, #22ADD4, #1E98BA);}	
  </style>
  
</head>

<body> 
<script language="php">
  if (!isset($_SESSION["uid"])) {
    $URL="/"; 
	echo "<script type='text/javascript'>location.replace('$URL');</script>"; } 	
  else { 
    include("../asset/libs/db.php");
	include("../asset/libs/common.php");	
    include("../asset/libs/dashboard.php");
	mssql_close($dbSQL);
</script>
  <div class="wrapper">

	  <div class="w3-row-padding">	   
	    <div class="w3-quarter"> <!-- Categories -->
		  <h2>Categories</h2>
		  <ul class="accordion">		  
		    <script language="php">
			  if(validMenuAccess("11")==1) { 
			</script>
		        <li onclick="myFunction('emp')"><a href="#">Employee</a>
                  <div id="emp" class="w3-hide">  
                    <ul class="sub-menu">				
				      <li><a href="?show=ltem"><em>01</em>List</a></li>					
				      <li><a href="?show=frgem"><em>02</em>New Employee</a></li>
			        </ul>
                  </div>	
			    </li>
			    <li onclick="myFunction('func')"><a href="#">Function</a>
                  <div id="func" class="w3-hide">
                    <ul class="sub-menu">				
				      <li><a href="?show=fcdpt"><em>01</em>List</a></li>					
				      <li><a href="?show=fcadd"><em>02</em>New Function</a></li>
			        </ul>
                  </div>		
				</li>
		    <script language="php">
			  }
			  if(validMenuAccess("12")==1) { 			  
			</script>  
		 	    <li onclick="myFunction('users')"><a href="#">User</a>
                  <div id="users" class="w3-hide">
                    <ul class="sub-menu">				
				      <li><a href="?show=ru"><em>01</em>List</a></li>					
				      <li><a href="?show=cu_a"><em>02</em>Registration</a></li>
			        </ul>
                  </div>				
				</li>
		    <script language="php">
			  }
            </script>			
		  </ul>
		</div>
		<div class="w3-threequarter"> <!-- Page Content -->
		  <div id="result">
            <script language="php">
	          if(isset($_GET["show"])) {			
                if(validMenuAccess("11")==1) {  			  
	              if($_GET["show"] == "ltem") { include("emp_list.php"); }		
 			      if($_GET["show"] == "frgem") { include("emp_reg.php"); }		
	              if($_GET["show"] == "fcdpt") { include("function.php"); }	
                  if($_GET["show"] == "fcadd") { include("manage-function.php"); }	       
				}
				if(validMenuAccess("12")==1) { 	
                  if($_GET["show"] == "ru")   { include("users_list.php"); }	
				  if($_GET["show"] == "cu_a") { include("user_reg.php"); }	
				}
/*                if(validMenuAccess("14")==1) { 					
				  if($_GET["show"] == "gr_rp") { include("reg_group.php"); }
				}  */
 		      }		  
			</script>  
		  </div>
		</div>
		
	  </div> <!-- End Of Row Padding-->
  
  </div><!-- wrapper -->  
  
<script>
  window.addEventListener("resize", function(){
	var w = window.innerWidth;
    if (w <= 800) {
      document.getElementById("mySidenav").style.width = "0";		  
	} else {
        document.getElementById("mySidenav").style.width = "200px";
		document.getElementById("mySidenav").style.backgroundColor = "#fbfcfc";
      }		
  });

  function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show w3-animate-opacity";
    } else { 
        x.className = x.className.replace(" w3-show w3-animate-opacity", "");
    }
  }

  function dateSeparator(varID) {
    var str = document.getElementById(varID).value;
	panjang = str.length;
	if (panjang==8) {
      var partYear = str.slice(0,4);
	  var partMonth = str.slice(4,6); 
	  var partDate = str.slice(6,8);
	  
	  result = partYear.concat('-', partMonth, '-', partDate);
	  document.getElementById(varID).value = result;
	} 		 
  }  
</script>

<script type="text/javascript">
  function openPage(urlvar) { $("#result").load(urlvar); }
  function loadurl(urlvar) { location.replace(urlvar); }
</script>
  
</body>
</html>  

<script language="php">
  }
</script> 