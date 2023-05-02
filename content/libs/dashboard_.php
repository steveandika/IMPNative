<?php
  function validAccess($tagID) {
	if((strtoupper($_SESSION["uid"])!="ROOT") && ($tagID != 'change_pswd')) {   
	  $userID = $_SESSION['uid'];
	  $menutag= (int)$tagID;
      $query="Select * From userMenuProfile Where userID='".$userID."' And menuTag=".$menutag;
	  $res=mssql_query($query);	 
	  $return=mssql_num_rows($res);
	  mssql_free_result($res);
	} 
	else { $return=1; }
	
	return $return;
   }
?>

<header class="header">
 <div class="header-container">
   <button type="button" class="toggle_menu" style="color:#fff;font-size:20px" onclick="toggleNav()"><i class="fa fa-bars"></i></button> 
   <h1 style="font-weight:500!important;color:#fff!important">IMP Container System</h1>
 </div> 	
 <div class="header-container label-userProfile">
    <i class="fa fa-user" style="font-size:16px;color: #1c2833"></i>&nbsp;&nbsp;<?php echo $_SESSION["fullName"]?>
 </div>
</header>
 
<div id="mySidenav" class="sidenav">
  <a href="?p=personal" class="menu-link"><i class="menu-icon fa fa-fw fa-slideshare"></i><span class="menu-label">Users Management</span></a>
  <a href="?p=private" class="menu-link"><i class="menu-icon fa fa-fw fa-exchange"></i><span class="menu-label">Change Password</span></a>
  
  <div id="library_block" style="margin-left:5px;margin-right:5px;border:1px solid #ddd;border-radius:4px">
    <i class="menu-icon fa fa-fw fa-list-alt"></i><span class="menu-label" style="background-color:#f4f6f7;font-weight:500">Master</span>
	<div style="border-bottom:1px solid #ddd"></div>
	<div id="contain_library" style="margin-left:10px;margin-right:5px;">
    <?php if(validAccess("11")==1) { echo '<a href="/e-imp/personal_data/?show=ltem" class="sub-menu-link">User Profile</a>'; }
	      if(validAccess("15")==1) { echo '<a href="?p=vcust" class="sub-menu-link">Customer</a>'; }
          if(validAccess("15")==1 || validAccess("18")==1) { echo '<a href="?p=costCenter" class="sub-menu-link">Cost Center</a>'; }	   
  /*		
       if(validAccess("16")==1) { echo '<li class="sub_menu--item"><a href="?p=rhrb" class="sub_menu--link">Ports</a></li>'; }
 	   if(validAccess("17")==1) { echo '<li class="sub_menu--item"><a href="?p=rv" class="sub_menu--link">Vessel</a></li>'; }
*/
   	      if(validAccess("18")==1) { echo '<a href="?p=wrk" class="sub-menu-link">Workshop</a>'; }
	      if(validAccess("18")==1) { 
	        echo '<a href="?p=pr_mnr" class="sub-menu-link">Price List</a>'; 
	        echo '<a href="?p=cedexBrowse" class="sub-menu-link">Price List Managmnt.</a>'; 
	      }
	?>
	</div>
  </div>	
  
  <i class="menu-icon fa fa-fw fa-steam"></i><span class="menu-label">Operation</span></a>    
	<?php if(validAccess("32") == 1) { 		
	        echo '<div id="workshop_block" style="padding:5px 5px;border:1px solid #ddd;margin-left:15px;margin-right:5px;border-radius:4px">
			        <a href="?p=sldhW" class="sub-menu-link">DataLoad</a>				
			        <a href="?p=sldhW" class="sub-menu-link">S L D</a>				
			        <a href="?p=dolhW" class="sub-menu-link">L H W</a>
			        <a href="?p=gatein" class="sub-menu-link">In Hamparan</a>
			        <a href="?p=mnr" class="sub-menu-link">M N R</a>
                    <a href="?p=gateout" class="sub-menu-link">Out Hamparan</a>
				  </div>';
	      } 
	?>

  <button class="dropdown-btn" onClick="dropDownLib('finance')">   		
    <i class="menu-icon fa fa-fw fa-book"></i><span class="menu-label">Finance<i class="fa fa-caret-down"></i></span></button>
    <div id="finance" class="dropdown-container">
	
	<?php
	  if(validAccess("50") == 1) {
		//echo '<a href="?p=MNR_costCenter" class="sub-menu-link">Set CostCenter</a>';  
		echo '<a href="/e-imp/ses_fin/" onclick="return PopIt(this)" class="sub-menu-link">CostCenter & Material</a>';  
	  }
	?>
	</div>
  
  <button class="dropdown-btn" onClick="dropDownLib('rep')">   
    <i class="menu-icon fa fa-fw fa-table"></i><span class="menu-label">Reports<i class="fa fa-caret-down"></i></span></button>
    <div id="rep" class="dropdown-container">

	  <a href="?p=slhw" class="sub-menu-link">Summary Hamparan</a>
<!--  <a href="?p=m_in" class="sub-menu-link">In - Workshop</a>
      <a href="?p=m_out" class="sub-menu-link">Out - Workshop</a>
      <a href="?p=cy" class="sub-menu-link">Workshop Stock</a> -->
      <a href="?p=wsurvey" class="sub-menu-link">Waiting Survey</a>
<!--  <a href="?p=w_eor" class="sub-menu-link">Waiting Estimate</a> -->
      <a href="?p=w_fns" class="sub-menu-link">Waiting CR</a>	  		
<!--  <a href="?p=w_cl" class="sub-menu-link">Waiting Cleaning</a> -->
      <a href="?p=sm_mnr" class="sub-menu-link">Sum. CR & Cleaning</a>
   
    </div>

  <a href="?eof=1" class="menu-link"><i class="menu-icon fa fa-fw fa-power-off"></i><span class="menu-label">Log Out</label></a>  
</div>

<?php
  if(isset($_GET["p"])) 
  {
	if($_GET["p"]=="personal")      { $url = "/e-imp/personal_data/"; }
	if($_GET["p"]=="private")       { $url = "/e-imp/passwd/"; }

	if($_GET["p"]=="vcust")         { $url = "/e-imp/master.data/?show=".$_GET["p"]; }
	if($_GET['p']=='costCenter')    { $url = "/e-imp/cc/"; }
	if($_GET['p']=="wrk")           { $url = "/e-imp/master.data/?show=".$_GET["p"]; }	
	if($_GET['p']=="pr_mnr")        { $url = "/e-imp/master.data/?show=".$_GET["p"]; } 
	if($_GET['p']=="cedexBrowse")   { $url = "/e-imp/master.data/?show=".$_GET["p"]; } 	
	
	if($_GET["p"]=="sldhW")         { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="dolhW")         { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="gatein")        { $url = "/e-imp/mnr/?do=hw_registry"; }
	if($_GET['p']=="mnr")           { $url = "/e-imp/mnr/?do=domnr"; }	
	if($_GET['p']=="gateout")       { $url = "/e-imp/mnr/?do=".$_GET["p"]; } 
	
	if($_GET["p"]=='MNR_costCenter') {$url = "/e-imp/mnr_cc/";}
	
    if($_GET["p"] == "slhw")        { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "m_in")        { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "m_out")       { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "cy")          { $url = "/e-imp/fr/?r=".$_GET["p"]; }	
	if($_GET["p"] == "wsurvey")     { $url = "/e-imp/fr/?r=".$_GET["p"]; }	
	if($_GET["p"] == "w_eor")       { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "w_fns")       { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "w_cl")        { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "sm_eor")      { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "sm_mnr")      { $url = "/e-imp/fr/?r=".$_GET["p"]; }	
	echo $url;	  
	echo "<script type='text/javascript'>location.replace('$url');</script>";
  }
  
  if((isset($_GET['eof'])) && ($_GET['eof'] == "1")) 
  {
	$url="/e-imp/session/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  } 	  
?>

<script>
 function dropDownLib(varIDName) {
   var x = document.getElementById(varIDName);
   if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
   } else { 
        x.className = x.className.replace(" w3-show", "");
   }
 }


 
 function PopIt(linkname) {
   var w=window.open(linkname.href, linkname.target||"_blank",
                     'menubar=no,toolbar=no,location=no,directories=no,status=no,scrollbars=no,resizable=no,dependent,width=1300,height=800,left=0,top=0');
   return w?false:true;					 
 }	 
</script>
