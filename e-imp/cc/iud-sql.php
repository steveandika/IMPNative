<?php
  session_start();
  
  echo '<div id="info"></div>';
  echo '<script language="javascript">document.getElementById("info").innerHTML="Storing give value into database.. Please wait.";</script>';	  
  include("../asset/libs/db.php");  
  
  $ccCode='';
  $ccName='';
  $ccDesc='';
  
  $raiseErr=1;
  
  if(isset($_GET['kodeCostcenter']) && isset($_GET['namaCostcenter']) && isset($_GET['desc'])) {
    $ccCode=strtoupper($_GET['kodeCostcenter']);
    $ccName=strtoupper($_GET['namaCostcenter']);
	$ccDesc=strtoupper($_GET['desc']);
	
	$raiseErr=0;
  }	  
  
  if(isset($_GET['id']) && $_GET['id'] != '') 
  {
    if($raiseErr==0) 
    {	  
      $keywrd=$_GET['id'];
 	
      $sql="Update m_CostCenter Set ccCode='$ccCode', ccDescription='$ccDesc', ccName='$ccName' 
 	        Where ccIndex=$keywrd;
		  
            Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                        Values('".$_SESSION['uid']."', CONVERT(VARCHAR(15), GETDATE(), 126),CONCAT('Update Master Cost Center, Index: ','$keywrd')); ";
	  $rsl_exec=mssql_query($sql);
	  if($rsl_exec) {$url = "?show=list&update=1";}  
	  else {$url = "?show=list&update=0";}	 
    } 
    else {$url = "?show=list&update=0";}	
  } 
  else 
  {	  
	if($raiseErr==0) 		
	{	
      $sql="Select * From m_CostCenter Where ccCode='$ccCode' Or ccName='$ccName; ";
	  $rsl=mssql_query($sql);
	  $found=mssql_num_rows($rsl);
	  mssql_free_result($rsl);
	  
	  if($found <= 0)
	  {	  
	    $sql="Declare @LastIndex INT, @NewIndex INT;
		
	  	      Select @LastIndex=ISNULL(MAX(ccIndex),0) From m_CostCenter; 
		      Set @NewIndex = @LastIndex +1;
              
              Insert Into m_CostCenter(ccIndex, ccCode, ccDescription, ccName) Values(@NewIndex, '$ccCode', '$ccDesc', '$ccName');			  
              Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('".$_SESSION['uid']."', CONVERT(VARCHAR(15), GETDATE(), 126),
			                                                                 CONCAT('New Record Master Cost Center, Index: ','$keywrd')); ";
        $rsl_exec=mssql_query($sql);
        if($rsl_exec) {$url = "?show=list&append=1";} 
	    else {$url = "?show=list&append=0";}			
	  
	    if(strtoupper($_GET['whatToDo'])=="SAVE and ADD NEW") {$url = $url.'&act=adding';}		  
      } 
	  else {$url = "?show=list&append=0";} 		  
	}  
    else {$url = "?show=list&append=0";}  	
  }
  
  echo '<script language="javascript">document.getElementById("info").innerHTML="";</script>';
  mssql_close($dbSQL);
  
  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
?>