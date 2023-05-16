<?php
    session_start();  
    $defHTML = $_SESSION['defurl'];
  
	$groupFin = array("AKU2182","DAP2203","HNA2171","JUN2171","JNU2183","LYA2211","RAN2182","JOK001","ROOT", "RSO2171","RYO2201","SRE2211");
	$groupRoot = array("JOK001", "ROOT");	
    $masterData = array("ROK2171", "JOK001","ROOT")
?>

<div class="header">
	<button class="logo" onclick="toggleSideNav()">&#9776;</button>
	<div class="header-right"><a class="active" href="#user">User: <b> <?php echo strtoupper($_SESSION['uid']) ?></b></a></div>
</div>  
  
<div id="sideBar" class="sidenav padding-top-10 padding-left-10 padding-right-10">
	<?php
        $html = '';

		if (in_array(strtoupper($_SESSION['uid']), $groupRoot))
		{ 
		  $html .= '<a href="?p=personal" class="menu-link"><span class="menu-label">Users Management</label></a>';
		  $html .= '<div class="height-10"></div>';
		}  

		$html .= '<a href="?p=private" class="menu-link"><span class="menu-label">Change Password</label></a>';
		$html .= '<div class="height-10"></div>';
		
		$html .= '<button class="dropdown-btn border-radius-3">Master Data<i class="fa fa-caret-down"></i></button>';
		$html .= '<div class="dropdown-container"><div class="height-5"></div>';
		
		if (in_array(strtoupper($_SESSION['uid']), $masterData)) 
		{ 
			$html .= '<a href="?p=vcust" class="sub-menu-link padding-left-15">Customer</a>';
			$html .= '<a href="?p=wrk" class="sub-menu-link padding-left-15">Workshop Location</a>'; 
			$html .= '<a href="?p=pr_mnr" class="sub-menu-link padding-left-15">Price List</a>'; 
			$html .= '<a href="?p=cedexBrowse" class="sub-menu-link padding-left-15">Manage Price List</a>'; 
		}
		$html .= '<div class="height-5"></div></div>';
		
		if(validMenuAccess("32") == 1) {
			$html .= '<button class="dropdown-btn border-radius-3">Workshop<i class="fa fa-caret-down"></i></button>';
			$html .= '<div class="dropdown-container"><div class="height-5"></div>';
			$html .= '<a href="?p=gatein" class="sub-menu-link">In Hamparan</a>';
			$html .= '<a href="'.$defHTML.'/e-imp/mnr/?do=domnr" class="sub-menu-link">M n R</a>';
			$html .= '<a href="?p=sitesvy" class="sub-menu-link">Manage Photo/Image</a>';
			$html .= '<a href="?p=cruddate" class="sub-menu-link">C/R, C/C, Estimate App.</a>';	
		 
			if (in_array(strtoupper($_SESSION['uid']), $groupRoot))
			{
				$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("newmnr/unlockmnr.php").'" class="sub-menu-link" >Open Lock MnR</a>';
			}

			$html .= '<div class="height-5"></div></div>';			
		}
		
		if (in_array(strtoupper($_SESSION['uid']), $groupFin))
		{ 
			$html .= '<div class="height-15"></div>';
			$html .= ' <a class="menu-link" href="'.$defHTML.'/e-imp/dashboard/appdesktop"><span class="menu-label">Tools</span></a>';  
			$html .= '<div class="height-5"></div>';
	    }
		
        $html .= '<button class="dropdown-btn border-radius-3">Reports<i class="fa fa-caret-down"></i></button>';
		$html .= '<div class="dropdown-container"><div class="height-5"></div>';
		$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("fr/hw_summary.php").'" class="sub-menu-link padding-left-15">Summary Hamparan</a>'; 
        $html .= '<a href="?p=wsurvey" class="sub-menu-link">Waiting Survey</a>';
		$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("fr/div-filter-waitingapp.php").'" class="sub-menu-link padding-left-15">Waiting Approval</a>'; 
        $html .= '<a href="?p=w_fns" class="sub-menu-link">Waiting C/R</a>';
		$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("fr/div-filter-sumRepair.php").'" class="sub-menu-link padding-left-15">Summary Repair</a>'; 
		/*$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("fr/mntreor.php").'" class="sub-menu-link padding-left-15">Monitoring EoR</a>'; */
		$html .= '<a href="'.$defHTML.'/e-imp/1?src='.base64_encode("fr/monitoring-eor.php").'" class="sub-menu-link padding-left-15">Monitoring EoR</a>'; 
		$html .= '<div class="height-5"></div>';
		
		$html .= '</div>';
		
		if (validMenuAccess("32") == 1 || strtoupper($_SESSION["uid"]) == "JOK001" ) 
		{
			$html .= '<button class="dropdown-btn border-radius-3">Loader Management<i class="fa fa-caret-down"></i></button>';			
			$html .= '<div class="dropdown-container"><div class="height-5"></div>';   
			$html .= '<a href="/content/dataload?src='.str_rot13('loadHamparan').'" target="_blank" class="sub-menu-link">L H W</a>';
			$html .= '<a href="/content/dataload?src='.str_rot13('loadDateApp').'" target="_blank" class="sub-menu-link">Approval, C/R, C/C Date</a>';	   
			$html .= '<div class="height-5"></div>';
			$html .= '</div>'; 
			$html .= '<div class="height-5"></div>';
		} 

		$html .= '<div class="height-15"></div>';
		$html .= '<a href="?eof=1" class="menu-link"><span class="menu-label">Log Out</label></a>';
		
		echo $html;
	?>
   
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
	
	//if($_GET["p"]=="sldhW")         { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
		if($_GET["p"]=="dolhW")         { $url = "/e-imp/mnr/?do=".$_GET["p"]; }
		if($_GET["p"]=="gatein")        { $url = "/e-imp/mnr/?do=hw_registry"; }
		if($_GET["p"]=="mnr")           { $url = "/e-imp/mnr/?do=domnr"; }		
		if($_GET["p"]=="sitesvy")       { $url = "/e-imp/mnr/?do=sitesvy"; }	
		if($_GET["p"]=="cruddate")      { $url = "/e-imp/mnr/?do=cruddate"; }	
	//if($_GET['p']=="gateout")       { $url = "/e-imp/mnr/?do=".$_GET["p"]; } 
	
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
	
		echo "<script type='text/javascript'>location.replace('$url');</script>";
	}
  
	if ((isset($_GET['eof'])) && ($_GET['eof'] == "1")) {
		removeCookie();
		redirectToIndex(); 
	} 	  
?>