<?php
	function unregister_GLOBALS()
	{
		if (!ini_get('register_globals')) {
			return;
		}

		// Might want to change this perhaps to a nicer error
		if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
			die('GLOBALS overwrite attempt detected');
		}

		// Variables that shouldn't be unset
		$noUnset = array('GLOBALS',  '_GET',
						 '_POST',    '_COOKIE',
						 '_REQUEST', '_SERVER',
						 '_ENV',     '_FILES', '_SESSION');

		$input = array_merge($_GET,    $_POST,
							 $_COOKIE, $_SERVER,
							 $_ENV,    $_FILES,
							 isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
		
		foreach ($input as $k => $v) {
			if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
				unset($GLOBALS[$k]);
			}
		}
	}

  function openDB() {
    $server='192.168.1.5, 1435'; 
    $dbSQL=mssql_connect($server, 'sa', 'Pswd120907');
	$mssg="";
  
    if (!$dbSQL) { $mssg="Something when wrong while connection to main database"; } 
    else { 
	  mssql_select_db("CSSCY");
	  $mssg="connected"; 
	}
	
	return $mssg;
  }
  
  function validateLogin() {
    $username="";
    $passwd="";
	$validLogin=0;
	$status="";

    if(isset($_POST["username"]) && isset($_POST["password"])) {
      $username=$_POST["username"];
	  $passwd=$_POST["password"];
	  $status="accept";
		 
      $specialChar=array("'","/","=","#","*","%","-","AND","OR"); 
      if (in_array($username, $specialChar)) { $status="denied"; }
	  if ($status=="accept") { 
	    if (in_array($passwd, $specialChar)) { $status="denied"; }
	  }
 
      if ($status=="accept") {   
	     $_SESSION['defurl'] = '//icons.pt-imp.com';
		 
	    if ($username=="root" && $passwd="sps") {
  		  $timeout=60*60*12;
		  $_SESSION["uid"]=$username;		  
		  $_SESSION["fullName"]="System Administrator";
		  $_SESSION["allowInsert"]=1;
		  $_SESSION["allowUpdate"]=1;
		  $_SESSION["allowDelete"]=1;		   	 
		  $_SESSION["time_out"]=$timeout;	  			
		}
        else if ($username=="IKPP" && $passwd="Sm@7219!") {	  		  
          setcookie("cuserid", $username, time() + (86400 * 30),"/");
		  setcookie("cusername", "IKPP Admin", time() + (86400 * 30),"/");	
		  setcookie("isExternal", "y", time() + (86400 * 30),"/");	
		  $_COOKIE["cuserid"]=$username;
		  $_COOKIE["cusername"]="IKPP Admin";
		  $_COOKIE["isExternal"]="y";
		}
        else {			
          openDB();		
          $query="Select a.userid, a.alInsert, a.alDelete, a.alEdit, b.completeName 
		          from userProfile a Inner Join m_Employee b On b.empRegID=a.userID 
	              where a.userID='$username' And accessKey='$passwd' And isActive=1";
          $result=mssql_query($query);
          if(mssql_num_rows($result) > 0) {
		    $row=mssql_fetch_array($result);	
		     
			$timeout=60*60*12;
		    $_SESSION["uid"]=strtoupper($username);
		    $_SESSION["passwd"]=$passwd;
		    $_SESSION["fullName"]=$row["completeName"];
		    $_SESSION["allowInsert"]=$row["alInsert"];
		    $_SESSION["allowUpdate"]=$row["alEdit"];
		    $_SESSION["allowDelete"]=$row["alDelete"];		   	 
		    $_SESSION["time_out"]=$timeout;	  
		  }
		  else { $status="denied"; }
		}
	  }		 
	}
	
	return $status;
  }
 
	  
	function removeCookie() {
		session_destroy();
		
		/*setcookie("cuserid", "", time()-3600, "/");
		setcookie("cusername", "", time()-3600, "/");		
		setcookie("isExternal", "", time()-3600, "/");	
		unset($_COOKIE["cuserid"]);
		unset($_COOKIE["cusername"]);
		unset($_COOKIE["isExternal"]);	  	*/
	  }
	  
	  function redirectToIndex() {
		$url="//icons.pt-imp.com/log-out"; 
		echo "<script type='text/javascript'>location.replace('$url');</script>";   
	  }	  
	  
	  function headerReport($reportTitle, $whName, $periode1, $periode2) {
		$dsgn="<label style='font: 15px;font-weight:500;text-decoration:underline'>Repair ToolBox - Report</label>
			   <div class='height-10'>&nbsp;</div>
			   <h6><strong>".$reportTitle."</strong></h6>
			   <label><strong>Report Period</strong> ".$periode1." until ".$periode2."</label><br>";
		if (isset($whName)) {
		  $dsgn=$dsgn."<label><strong>Workshop</strong> ".$whName."</label><br>";	
		}
		
		return $dsgn; 
	}	
	  
	function validUnitDigit($paramNoUnit) {
		$constPrefix = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$constSuffix = array(10,12,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,31,32,34,35,36,37,38);
		$constMulti = array(1,2,4,8,16,32,64,128,256,512);
		$str = '';
		$calc = 0;	
		for($leap=0; $leap<=3; $leap++) {
		  $doBreak = 0;  
		  $tale = 0;
		  while($doBreak != 1) {
			if($constPrefix[$tale] == substr($paramNoUnit, $leap,1)) {		  
			  $doBreak = 1;
			  $calc =$calc+($constMulti[$leap]* $constSuffix[$tale]);
			}
			else {
			  $tale++;
			}
		  }
		}
		
		for($leap=4; $leap<=9; $leap++) {
		  $digitNoUnit = intval(substr($paramNoUnit, $leap,1));
		  $calc = $calc +($digitNoUnit * $constMulti[$leap]);
		}		
		
		$resultDiv = (Floor($calc/11)) *11;
		$lastDigit = strval(($calc - $resultDiv) %10);
		$digitNoUnit = substr($paramNoUnit, 10,1);
		if(Trim($lastDigit) != $digitNoUnit ) {
		  return 'Not standard Container digit was found. Should be '.substr($paramNoUnit, 0,10).'<strong> '.$lastDigit.'</strong>';
		} else { 
		  return 'OK'; 
		}
	}
	  
	function haveCustomerName($customerID) {
		$namaCustomer = '';
		$srch="Select CASE WHEN shortName Is NULL THEN completeName ELSE shortName End As custName 
			   From m_Customer Where custRegID='$customerID'";
		$rslt=mssql_query($srch);
		if(mssql_num_rows($rslt) > 0 ) { 
		  $arrFtch=mssql_fetch_array($rslt);
		  $namaCustomer=$arrFtch[0];
		}
		mssql_free_result($rslt);	
		return $namaCustomer;
	}
	  
			   
	function validMenuAccess($tagID) {
		 $jmlBrs=1;
		 
		 if((strtoupper($_SESSION["uid"])!="ROOT") && ($tagID != 'change_pswd')) {
		   $userID = $_SESSION['uid'];
		   $menutag= (int)$tagID;

		   $query="Select count(1) JumlahBaris from userMenuProfile where userID='".$userID."' And menuTag=".$menutag;
		   $result=mssql_query($query);	
		   if (mssql_num_rows($result) > 0) {
			 $arrftch=mssql_fetch_array($result);
			 $jmlBrs=$arrftch["JumlahBaris"];		 
		   }
		   mssql_free_result($result);
		 }  
		 return $jmlBrs;
	}

	function newvalidMenuAccess($roleName,$tagID) {
		$jmlBrs=1;

		$sql = "Select ".$roleName." from usersIMPRole where userID = '$tagID' and isEnable = 1 and ".$roleName."=1";
		$result = mssql_query($sql);
		
		$row = mssql_num_rows($result);
		if ($row > 0){
		  $col = mssql_fetch_array($result);
		  $jmlBrs = $col[0];
		  
		  mssql_free_result($result);
		}
		else { 
		  $jmlBrs = $row; 
		  mssql_free_result($result);
		}
			
		return $jmlBrs;
	} 

	function showMessage($msgIs, $type) {
		$html  = '<div id="msgw" class="frame">';
		 
		if ($type == 'error'){
		  $html .= ' <div class="padding-top-5 padding-bottom-5 w3-text-red" style="border-bottom:1px solid #ddd;font-size:15px;">';
		} 
		else {
		  $html .= ' <div class="padding-top-5 padding-bottom-5" style="border-bottom:1px solid #ddd;background-color:#2471a3!important;font-size:15px;color:#fff">';
		}

		$html .= '   &nbsp&nbsp;Notification';	 
		$html .= ' </div>';	   
			 
		$html .= ' <div class="w3-container">';          
		$html .= '   <div class="padding-top-5 padding-bottom-10">'.$msgIs.'</div>';	  
		$html .= ' </div>';
		$html .= '</div>';	
		 
		echo $html;
	}	   
	  
	function floatMessage($msgIs) {	
		include ("floatmsg_confirmonly.php");

		echo "<script>document.getElementById('floatMsg').style.display = 'block';</script>";
	}	     
?>