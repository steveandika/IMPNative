<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");
    $stmt = openDB();
	
	if($stmt === 'connected'){
		echo $stmt; 
		
		$NoCont = $_POST['NoCont'];
		$contSize = $_POST['ContSize'];
		$contType = $_POST['ContType'];
		$contHeight = $_POST['ContHeight'];
		$gateIn = $_POST['gatein'];
		$hamparanID = $_POST['hamparan'];
		$userID = $_POST['userID'];	
  
		if($contSize != "") {
			$qry = "EXEC C_CreateNewEvent 
					@ContNumber='$NoCont',
					@ContSize='$contSize',
					@ContType='$contType',
					@ContHeight='$contHeight',
					@ContConstr='STL',
					@ContMnfr='NA',
					@ContGW=0,
					@ContVent=1,
					@DateIn='$gateIn',
					@CleaningActivity=NULL,
					@HamparanID='$hamparanID',
					@UserID='$userID';";
			$stmt = mssql_query($qry);	
			echo $stmt;	
		}
    }	 
	else { echo $stmt; }
?>