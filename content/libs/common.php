<?php
  function new_log_lhw($log_nbr, $cont_nbr, $sizeTypeHeight, $gateIn, $remark, $status) {
	$is_success=0;
	$sql="Insert Into LOG_HAMPARAN_INJECT_DETAIL(log_index, container_nbr, size_type_height, dttm_in_workshop, description, record_state) 
	      Values($log_nbr, '$cont_nbr', '$sizeTypeHeight', '$gateIn', '$remark', '$status'); ";
	$rslt=mssql_query($sql);
	
	if (!$rslt) { $is_success=0; }
	else { $is_success=1; }
	
	if ($is_success==1 && $status=="Inserted") {
	  $sql="Update LOG_HAMPARAN_INJECT_HEADER Set Accepted=Accepted +1 Where log_index= $log_nbr; ";
	  $rslt=mssql_query($sql);
	}	
	
	return $is_success;
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
  
  function getUserIP() {
    $clientip      = isset( $_SERVER['HTTP_CLIENT_IP'] )       && $_SERVER['HTTP_CLIENT_IP']       ?
                    $_SERVER['HTTP_CLIENT_IP']         : false;
    $xforwarderfor = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] ?
                     $_SERVER['HTTP_X_FORWARDED_FOR']   : false;
    $xforwarded    = isset( $_SERVER['HTTP_X_FORWARDED'] )     && $_SERVER['HTTP_X_FORWARDED']     ?
                     $_SERVER['HTTP_X_FORWARDED']       : false;
    $forwardedfor  = isset( $_SERVER['HTTP_FORWARDED_FOR'] )   && $_SERVER['HTTP_FORWARDED_FOR']   ?
                     $_SERVER['HTTP_FORWARDED_FOR']     : false;
    $forwarded     = isset( $_SERVER['HTTP_FORWARDED'] )       && $_SERVER['HTTP_FORWARDED']       ?
                     $_SERVER['HTTP_FORWARDED']         : false;
    $remoteadd     = isset( $_SERVER['REMOTE_ADDR'] )          && $_SERVER['REMOTE_ADDR']          ?
                     $_SERVER['REMOTE_ADDR']            : false;
    
    // Function to get the client ip address
    if ( $clientip          !== false ) { $ipaddress = $clientip; }
    elseif( $xforwarderfor  !== false ) { $ipaddress = $xforwarderfor; }
    elseif( $xforwarded     !== false ) { $ipaddress = $xforwarded; }
    elseif( $forwardedfor   !== false ) { $ipaddress = $forwardedfor; }
    elseif( $forwarded      !== false ) { $ipaddress = $forwarded; }
    elseif( $remoteadd      !== false ) { $ipaddress = $remoteadd; }
    else{ $ipaddress = false; # unknown  
	    }
    return $ipaddress;
  }	
		
  function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac';  }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
      $bname = 'Internet Explorer';
      $ub = "MSIE";  }
    elseif(preg_match('/Firefox/i',$u_agent)) {
      $bname = 'Mozilla Firefox';
      $ub = "Firefox"; }
    elseif(preg_match('/Chrome/i',$u_agent)) {
      $bname = 'Google Chrome';
      $ub = "Chrome"; }
    elseif(preg_match('/Safari/i',$u_agent)) {
      $bname = 'Apple Safari';
      $ub = "Safari"; }
    elseif(preg_match('/Opera/i',$u_agent)) {
      $bname = 'Opera';
      $ub = "Opera"; }
    elseif(preg_match('/Netscape/i',$u_agent)) {
      $bname = 'Netscape';
      $ub = "Netscape"; }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
      //we will have two since we are not using 'other' argument yet
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version= $matches['version'][0]; }
      else { $version= $matches['version'][1]; }
    }
    else { $version= $matches['version'][0]; }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
      'userAgent' => $u_agent,
      'name'      => $bname,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'    => $pattern
    );
  } 
   
  function validMenuAccess($tagID) {
	 if(($_SESSION["uid"]!="ROOT") && ($tagID != 'change_pswd')) {
	   include("db.php");	   
	   $userID = $_SESSION['uid'];
	   $menutag= (int)$tagID;
       $query="Select * From userMenuProfile Where userID='".$userID."' And menuTag=".$menutag;
	   $res=mssql_query($query);	 
	   $return=mssql_num_rows($res);
	   mssql_free_result($res);
	 } else { $return=1; }
	 return $return;
   }
?>