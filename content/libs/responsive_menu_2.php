<script language="php">
  function validAccess($tagID) {
	if(($_SESSION["uid"]!='root') && ($tagID != 'change_pswd')) {   
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
</script>

<header class="header clearfix">
  <div class="w3-row-padding">
    <button type="button" id="toggleMenu" class="toggle_menu" style="color:#fff;"><i class="fa fa-align-left"></i></button>
    <h1 style="font-weight:600!important;color:#fff!important">I-ConS</h1>
</header>
 
<nav class="vertical_nav">
  <ul id="js-menu" class="menu">
    <li class="menu--item">
      <a href="?p=personal" class="menu--link">
        <i class="menu--icon fa fa-fw fa-slideshare"></i>
        <span class="menu--label">HUMAN RESOURCES</span>
      </a>
    </li>  

<!--	
    <li class="menu--item">
	
      <a href="?p=mdata" class="menu--link">
        <i class="menu--icon fa fa-fw fa-trello"></i><span class="menu--label">LIBRARY</span>
      </a>
-->
   <li class="menu--item  menu--item__has_sub_menu">
	 <label class="menu--link"><i class="menu--icon fa fa-fw fa-list-alt"></i><span class="menu--label">LIBRARY</span></label> 
     <ul class="sub_menu">	 
	  <?php
	    if(validAccess("15")==1) { echo '<li class="sub_menu--item"><a href="?p=vcust" class="sub_menu--link">Customer</a></li>'; }
/*		
        if(validAccess("16")==1) { echo '<li class="sub_menu--item"><a href="?p=rhrb" class="sub_menu--link">Ports</a></li>'; }
		if(validAccess("17")==1) { echo '<li class="sub_menu--item"><a href="?p=rv" class="sub_menu--link">Vessel</a></li>'; }
*/
		if(validAccess("18")==1) { echo '<li class="sub_menu--item"><a href="?p=wrk" class="sub_menu--link">Workshop Location</a></li>'; }
		if(validAccess("19")==1) 
		{ 
	      echo '<li class="sub_menu--item"><a href="?p=pr_mnr" class="sub_menu--link">Price List Log</a></li>'; 
		  echo '<li class="sub_menu--item"><a href="?p=cedexBrowse" class="sub_menu--link">Manage Price List</a></li>'; 
		}
	  ?>
	 </ul>
    </li> 
    
	<?php
	  if(validAccess("32")==1) { 		
	    echo '<li class="menu--item  menu--item__has_sub_menu">
                <label class="menu--link"><i class="menu--icon fa fa-fw fa-steam"></i><span class="menu--label">WORKSHOP</span></label>

                <ul class="sub_menu">
				  <li class="sub_menu--item"><a href="?p=sldhW" class="sub_menu--link">S L D</a></li>				
				  <li class="sub_menu--item"><a href="?p=dolhW" class="sub_menu--link">L H W</a></li>
				  <li class="sub_menu--item"><a href="?p=gatein" class="sub_menu--link">In Hamparan</a></li>
				  <li class="sub_menu--item"><a href="?p=domnr" class="sub_menu--link">M N R</a></li>
                  <li class="sub_menu--item"><a href="?p=gateout" class="sub_menu--link">Out Hamparan</a></li>
   	            </ul>
              </li>';
	  } 
	?>
	
    <li class="menu--item  menu--item__has_sub_menu">
      <label class="menu--link"> 
        <i class="menu--icon fa fa-fw fa-table"></i><span class="menu--label">REPORTS</span>      
	  </label>

      <ul class="sub_menu">
	    <li class="sub_menu--item"><a href="?p=slhw" class="sub_menu--link">Summary LHW</a></li>
        <li class="sub_menu--item"><a href="?p=m_in" class="sub_menu--link">In - Workshop</a></li>
        <li class="sub_menu--item"><a href="?p=m_out" class="sub_menu--link">Out - Workshop</a></li>
        <li class="sub_menu--item"><a href="?p=cy" class="sub_menu--link">Workshop Stock</a></li>
        <li class="sub_menu--item"><a href="?p=wsurvey" class="sub_menu--link">Waiting Survey</a></li>
        <li class="sub_menu--item"><a href="?p=w_eor" class="sub_menu--link">Waiting Estimate</a></li>		  
        <li class="sub_menu--item"><a href="?p=w_fns" class="sub_menu--link">Waiting CR</a></li>		  		
        <li class="sub_menu--item"><a href="?p=w_cl" class="sub_menu--link">Waiting Cleaning</a></li>		  		
        <li class="sub_menu--item"><a href="?p=sm_mnr" class="sub_menu--link">Sum. CR & Cleaning</a></li>		  		

   	  </ul>
    </li>

    <li class="menu--item">
      <a href="?p=private" class="menu--link">
        <i class="menu--icon fa fa-fw fa-exchange"></i>
          <span class="menu--label">REPLACE PASSWORD</span>
      </a>
    </li>

    <li class="menu--item">
      <a href="?eof=1" class="menu--link">
        <i class="menu--icon fa fa-fw fa-power-off"></i>
        <span class="menu--label" style="font-weight:600!important">LOG OUT</span>
      </a>
    </li>	
  
  </ul>

    <button id="collapse_menu" class="collapse_menu" style="border-bottom:1px solid #ddd">
      <i class="collapse_menu--icon fa fa-fw"></i>
    </button>  

</nav>

<script language="php">
  include("common.php");
  if(isset($_GET["p"])) {
	if($_GET["p"]=="personal") { $url = "/e-imp/personal_data/"; }
	if($_GET["p"]=="private")  { $url = "/e-imp/passwd/"; }
/*	if($_GET["p"]=="mdata")    { $url = "/e-imp/master.data/"; } */

	if($_GET["p"]=="vcust")    { $url = "/e-imp/master.data/?show=".$_GET["p"]; }
/*	
	if($_GET['p']=="rhrb")     { $url = "/e-imp/master.data/?show=".$_GET["p"]; }
	if($_GET['p']=="rv")       { $url = "/e-imp/master.data/?show=".$_GET["p"]; }
*/	
	if($_GET['p']=="wrk")      { $url = "/e-imp/master.data/?show=".$_GET["p"]; }	
	if($_GET['p']=="pr_mnr")   { $url = "/e-imp/master.data/?show=".$_GET["p"]; } 
	if($_GET['p']=="cedexBrowse")   { $url = "/e-imp/master.data/?show=".$_GET["p"]; } 	
	
	if($_GET["p"]=="sldhW")    { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="dolhW")    { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="gatein")   { $url = "/e-imp/mnr/?do=hw_registry"; }
	if($_GET['p']=="domnr")    { $url = "/e-imp/mnr/?do=".$_GET["p"]; }	
	if($_GET['p']=="gateout")  { $url = "/e-imp/mnr/?do=".$_GET["p"]; } 
	
/*	if($_GET['p']=="estimate") { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="photo")    { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="approval") { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
	if($_GET['p']=="av_reg")   { $url = "/e-imp/mnr/?do=".$_GET["p"]; }*/

    if($_GET["p"] == "slhw")   { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "m_in")   { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "m_out")  { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "cy")     { $url = "/e-imp/fr/?r=".$_GET["p"]; }	
	if($_GET["p"] == "wsurvey"){ $url = "/e-imp/fr/?r=".$_GET["p"]; }	
	if($_GET["p"] == "w_eor")  { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
/*	if($_GET["p"] == "w_app")  { $url="/e-imp/fr/?r=w_app"; }		*/
	if($_GET["p"] == "w_fns")  { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "w_cl")   { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "sm_eor") { $url = "/e-imp/fr/?r=".$_GET["p"]; }		
	if($_GET["p"] == "sm_mnr") { $url = "/e-imp/fr/?r=".$_GET["p"]; }	
		  
	echo "<script type='text/javascript'>location.replace('$url');</script>";
  }
  if((isset($_GET['eof'])) && ($_GET['eof'] == "1")) {
	$url="/e-imp/session/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 	  
</script>