<?php
  session_start();
  include("../asset/libs/common.php"); 
  openDB();
  
  if (isset($_POST['noCnt'])) {
	$NoContainer = strtoupper($_POST['noCnt']);

    $query = "Select * From containerJournal with (NOLOCK)
	          Where NoContainer = '$NoContainer' And gateIn Is Not Null And gateOut Is Null ANd bookInID Not Like '%BATAL'; ";
    //echo $query;
	$result = mssql_query($query);
	$numRecord = mssql_num_rows($result);
    mssql_free_result($result);
	$valid=0;    
	if ($numRecord <= 0) {
      $sizeCont = $_POST['contSize'];
	  $typeCont = $_POST['contType'];
	  $heightCont = $_POST['contHeight'];
	  $MnfrYear = $_POST['mnfr'];
	  $Construction = $_POST['constr'];
	  $Vent = $_POST['vent'];
	  $kodeBooking = $_POST['kodeBooking'];
	  $location = $_POST['location'];  
	  $eventDate = $_POST['eventDate'];	  
	  $vessel = $_POST['vesselName'];
	  $voyage = $_POST['voyageNo'];
	  $mlo = $_POST['mlo'];
	  $user = $_POST['consignee'];
	    	
	  $cond = "DM";
  	  $pending = "Y";
	  $iscleaning = 0;
	  $isrepair = 0;
	  
	  if($kodeBooking == '') {	  
        $dateIn_converted = str_replace("-", "", $eventDate); 
		$kodeBook = substr($dateIn_converted,0,1).substr($dateIn_converted,2,6);
        //$kodeBook = $location.substr($kodeBook,0,1).substr($kodeBook,2,6);			  
	    
		$remarks="HAMPARAN IN > ".$NoContainer;
	    $query = "Declare @KodeBookIn VarChar(30), @strLastIndex VarChar(20);
		          Declare @LastIndex Int;
				  
	              If not exists (Select keyFName from logKeyField where keyFName = '".$kodeBook."') 
	              Begin
	                Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."', 1);
	                Set @KodeBookIn = Concat('".$kodeBook."', '00001');
	              End else 
	                  Begin
		                Update logKeyField Set lastNumber = lastNumber +1 where keyFName = '".$kodeBook."';   
		                Set @LastIndex = (Select lastNumber from logKeyField where keyFName = '".$kodeBook."'); 
		                Set @strLastIndex = RTRIM(LTRIM(CONVERT(VARCHAR(20),@LastIndex)));
		  
		                If Len(@strLastIndex)=1 Set @strLastIndex=CONCAT('0000', @strLastIndex)
		                Else If Len(@strLastIndex)=2 Set @strLastIndex=CONCAT('000', @strLastIndex)
		                Else If Len(@strLastIndex)=3 Set @strLastIndex=CONCAT('00', @strLastIndex)
		                Else If Len(@strLastIndex)=4 Set @strLastIndex=CONCAT('0', @strLastIndex);

		               Set @KodeBookIn = Concat('".$kodeBook."', @strLastIndex); 
		          End;
				  
				  If Not Exists(Select ContainerNo From containerLog with (NOLOCK) Where ContainerNo = '$NoContainer') Begin
 		            Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
					Values('$NoContainer', ".$Vent.", '$MnfrYear', 0, '$sizeCont', '$typeCont', '$heightCont', '$Construction');
			      End; 
			  
                 Insert Into tabBookingHeader(bookID, bookType, blID, principle, vessel, consignee, operatorID, voyageID, SLDFileName) 
       	                               Values(@KodeBookIn, 0, @KodeBookIn, '$mlo', '$vessel', '$user', '', '$voyage', ''); 					  
				 Insert Into containerJournal(bookInID, NoContainer, gateIn, jamIn, Cond, isPending, Remarks, isCleaning, isRepair, workshopID)
				                       Values(@KodeBookIn, '$NoContainer', '$eventDate', '$eventTime', '$cond', '$pending', '', 1, 1, '$location'); ";					  
        $result = mssql_query($query);
		
		if($result) { $valid=1; }
	  }
      else {
		$query = "Update containerJournal Set gateIn = '$eventDate', Cond = '$cond', isPending = '$pending', 
				                              isCleaning = 1, isRepair = 1, workshopID = '$location' 
				  Where NoContainer = '$NoContainer' And bookInID='$kodeBooking' And gateIn Is Null;
				  
				  Update tabBookingHeader Set principle='$mlo', vessel='$vessel',consignee='$user' Where bookID='$kodeBooking'; ";
        $result = mssql_query($query); 
		
        if($result) { $valid=1; }		
      }		  
	  
	  //echo $query;
    }
  }
  $url = '/e-imp/mnr/?do=hw_registry&valid='.$valid;
    
  echo "<script type='text/javascript'>location.replace('$url');</script>";     
?>