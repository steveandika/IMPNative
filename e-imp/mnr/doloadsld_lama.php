<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />

<div class="height-10"></div>
<div class="wrapper">  
  <div id="info"></div>
</div>
		  
<?php
  include("../asset/libs/db.php");
  include("../asset/libs/upload_reader.php");
  
  $loc = $_POST['location'];
		  
  if(isset($_FILES['SLDFileName']['name'])) {
    $target = basename($_FILES['SLDFileName']['name']);	
	
    move_uploaded_file($_FILES['SLDFileName']['tmp_name'], $target);	  
	$data = new Spreadsheet_Excel_Reader($_FILES['SLDFileName']['name'],false);   
    $baris = $data->rowcount($sheet_index=0);
	$namaFeeder="";
	$vesselName="";
	$voyageNumber="";
	$dateETA="";
	$kodeBook_Before="";
	$principleName_tmp="";
	$haveEvent=0;
	
	$logDate=date('Y-m-d');	
	$query="If Not Exists(Select * From logSLD Where SLDFileName='$target') Begin 
              Insert Into logSLD(SLDFileName, logDate) Values('$target', '$logDate'); 
			End Else Begin
			      Update logSLD Set logDate='$logDate' Where SLDFileName='$target';
			    End";
    $result=mssql_query($query);	
	
	$queryall = '';	
	$kodeBook=str_ireplace("-", "", date('Y-m-d'));
	$kodeBook="SLD".substr($kodeBook,0,1).substr($kodeBook,2,6).str_ireplace(":", "", date("h:i"));
	$success = 0;
	
    for ($i=3; $i<=$baris; $i++) {
      //  menghitung jumlah real data. Karena kita mulai pada baris ke-2, maka jumlah baris yang sebenarnya adalah 
      //  jumlah baris data dikurangi 1. Demikian juga untuk awal dari pengulangan yaitu i juga dikurangi 1
      /*$barisreal = $baris-1;*/
      $k = $i-1;

      echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$k.' data successfully inserted .";</script>';			
    
      $contType = $data->val($i, 2); //GP/RF
      $contHeight = $data->val($i, 3); //HEIGHT 
      $contSize = $data->val($i, 4); //SIZE
      $containerNo = $data->val($i, 5); //CONTAINERNO	
      $remark = $data->val($i, 6); //REMARK
      $principleName = $data->val($i, 7); //PRINCIPLE	
      $namaFeeder_tmp = $data->val($i, 8); //FEEDER
      $vesselName_tmp = $data->val($i, 9); //VESSEL	
      $voyageNumber_tmp = $data->val($i, 10); //VOYAGE
      $dateETA_tmp = $data->val($i, 11);  //ETA	
      $Cond = $data->val($i, 12);  //DM/AV
	  
	  $containerNo = str_replace(' ','',$containerNo);
	  echo $containerNo.'<br>';
	  
	  if(strlen($containerNo) == 11)  {	  
	    if($Cond == '' || $Cond == 'AV') {$isPending = 'N';}
	    else {$isPending = 'Y';}
	  	  
	    if($namaFeeder == '') {$namaFeeder = strtoupper(rtrim($namaFeeder_tmp));}
	    else if(trim($namaFeeder_tmp) != '' && rtrim($namaFeeder_tmp) != $namaFeeder) {$namaFeeder = strtoupper(rtrim($namaFeeder_tmp));} 
	    
		if($vesselName == '') {$vesselName = strtoupper(rtrim($vesselName_tmp));}	    
		else if(trim($vesselName_tmp) != '' && rtrim($vesselName_tmp) != $vesselName) {$vesselName = strtoupper(rtrim($vesselName_tmp));} 
		
	    if($voyageNumber == '') {$voyageNumber = strtoupper(rtrim($voyageNumber_tmp));}
	    else if(trim($voyageNumber_tmp) != '' && rtrim($voyageNumber_tmp) != $voyageNumber) {$voyageNumber = strtoupper(rtrim($voyageNumber_tmp));} 	  
		
	    if(trim($dateETA_tmp) != '') {
	      if($dateETA == '') {$dateETA = trim($dateETA_tmp);}
	      else if(trim($dateETA_tmp) != '' && trim($dateETA_tmp) != $dateETA) {$dateETA = trim($dateETA_tmp);} 
	    }
	  
	    $sizeCode=0;
	    if((trim($contSize) != '') && (trim($contHeight) != '')) {
	      $contSize = trim($contSize);
	      $contHeight = trim($contHeight);	  	  
		  $ISOCode=$contSize.$contHeight;
		  $deskripsi='UPLOAD FROM STREAM';
		
	      $query="If Not Exists(Select * From m_ISOCode Where Size='$contSize' And Tipe='$contHeight') Begin 
                   Declare @NewIndex Int, @LastIndex Int; 
		          
			 	   Select @LastIndex=MAX(IDISO) From m_ISOCODE;
				   Set @NewIndex = @LastIndex +1;
				  
				   Insert Into m_ISOCODE(IDISO, ISOCode, DescriptionISO, Size, Tipe) 
				   Values(@NewIndex, '$ISOCode', '$deskripsi', '$contSize', '$contHeight');		        
				  End; ";
	      $result=mssql_query($query);
	 
          $query="Select * From m_ISOCode Where Size='$contSize' And Tipe='$contHeight'";
	      $result=mssql_query($query);
	      if(mssql_num_rows($result) > 0) {
	        $arr=mssql_fetch_array($result);
		    $sizeCode=$arr[0]; 
		    mssql_free_result($result); 
		  }		
	    }
	  
	    $kodeCustomer='';
	    if(trim($principleName) != '') {
		  $keywrd='%'.str_replace(' ','',strtoupper($principleName)).'%';
		  $query="Select custRegID From m_Customer Where Replace(completeName, ' ', '') Like '$keywrd'; ";
		  $result=mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $kodeCustomer = $arr['custRegID'];
		    mssql_free_result($result);
		  }	
        }		  
	  
	    $feeder='';
	    if(trim($namaFeeder) != '') {}
		  $keywrd='%'.str_replace(' ','',strtoupper($namaFeeder)).'%';
		  $query="Select custRegID From m_Customer Where Replace(completeName, ' ', '') Like '$keywrd'; ";
		  $result=mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $feeder = $arr['custRegID'];
		    mssql_free_result($result);
		  }
		  else {
	        $query="Declare @KeyField VarChar(4); 
	                If Not Exists(Select * From logKeyField Where keyFName Like 'CSTM') Begin 
	                  Set @KeyField='C001';
	                  Insert Into logKeyField Values('CSTM', 1); 
	                End Else Begin
	                      Declare @LastKey Int, @StrLastKey VarChar(3);
	                      Select @LastKey=lastNumber +1 From logKeyField Where KeyFName Like 'CSTM'; 
	                      Set @StrLastKey = LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey))); 
	                      if LEN(@StrLastKey) = 1 Set @KeyField = CONCAT('C00', @StrLastKey); 
	                      if LEN(@StrLastKey) = 2 Set @KeyField = CONCAT('C0', @StrLastKey); 
	                      if LEN(@StrLastKey) = 3 Set @KeyField = CONCAT('C', @StrLastKey); 
	                      Update logKeyField Set lastNumber=lastNumber +1 Where KeyFName Like 'CSTM'; 
	                    End;
	              
				    Insert Into m_Customer(custRegID, completeName, isPPNInclude, asExp, asImp, asLogParty, asMLO, asFeed, asSupp, asOther) 
				    Values(@KeyField, '".strtoupper($namaFeeder)."', 1, 0, 0, 0, 0, 1, 0, 0); ";
	  	    $result = mssql_query($query); 
            mssql_free_result($result);		  
		 
		    $query="Select custRegID From m_Customer Where Replace(completeName, ' ', '') Like '$keywrd'";
		    $result=mssql_query($query);
		    if(mssql_num_rows($result) > 0) {
		      $arr = mssql_fetch_array($result);
		      $feeder = $arr['custRegID'];
			  mssql_free_result($result);
		    }
		  }	
        }		  
/*	  	  
	    if($principleName_tmp != $principleName) 
		{
          $principleName_tmp = $principleName;		
		
	      if(trim($dateETA) != '') {$kodeBook=str_ireplace("-", "", $dateETA);}
	      else {$kodeBook=str_ireplace("-", "", date('Y-m-d'));}
	  
	      if(trim($kodeCustomer) != '') {$kodeBook="SLD".substr($kodeBook,0,1).substr($kodeBook,2,6).$kodeCustomer;}
	      else {$kodeBook="SLD".substr($kodeBook,0,1).substr($kodeBook,2,6)."C000-".trim((string) $i);}
	    }
	  
	    if($kodeBook_Before != $kodeBook) 
		{	  
	      $kodeBook_Before = $kodeBook;
	      
          $queryall=$queryall."If Not Exists(Select bookID From tabBookingHeader Where bookID='$kodeBook') Begin
		                         Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
		  		                                       Values('$kodeBook',0,'$kodeBook','$kodeCustomer','$vesselName','$dateETA','$feeder','$voyageNumber','$dateETA','$target'); 
				               End; ";
							   
	    }
*/		

	  	$eventTime = date('h:i');
        $qry = "Select * From containerJournal Where NoContainer = '$containerNo'";
        $resl = mssql_query($qry);
		$rows = mssql_num_rows($resl);
        if($rows <= 0) {
		  mssql_free_result($resl);
		  $do= "Insert Into containerJournal(bookInID, NoContainer, JamIn, Cond, TruckingIn, VehicleInNumber, isPending, Remarks, isCleaning, isRepair, workshopID, GIPort)
	                                  Values('$kodeBook', REPLACE('$containerNo',' ',''), '$eventTime', 'DM', '', '', 'Y', '$remark', 0,0, '$loc', '$dateETA'); 
				
                If Not Exists(Select ContainerNo From containerLog Where ContainerNo=REPLACE('$containerNo',' ','')) Begin
				  Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
				                    Values(REPLACE('$containerNo',' ',''),1,'',0, '$constSize', '', '$contHeight', '');
                End;
				
                If Not Exists(Select bookID From tabBookingHeader Where bookID='$kodeBook') Begin
		                         Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
		  		                                       Values('$kodeBook',0,'$kodeBook','$kodeCustomer','$vesselName','$dateETA','$feeder','$voyageNumber','$dateETA','$target'); 
				End; ";
				
          $execRes= mssql_query($do);							   
		  if($execRes) { $success++; }
        }	
      	else {
		  mssql_free_result($resl);
		  
		  $qry = "Select * From containerJournal Where NoContainer = '$containerNo' And gateOut Is Null";
		  $resl = mssql_query($qry);
		  $rows = mssql_num_rows($resl);
          if($rows <= 0) {
		    mssql_free_result($resl);
		    $do= "Insert Into containerJournal(bookInID, NoContainer, JamIn, Cond, TruckingIn, VehicleInNumber, isPending, Remarks, isCleaning, isRepair, workshopID)
			 						    Values('$kodeBook', REPLACE('$containerNo',' ',''), '$eventTime', 'DM', '', '', 'Y', '$remark', 0,0, '$loc');
									   
				  If Not Exists(Select ContainerNo From containerLog Where ContainerNo=REPLACE('$containerNo',' ','')) Begin
				    Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
					 		  Values(REPLACE('$containerNo',' ',''),1,'',0, '$constSize', '', '$contHeight', '');
				  End;

				  If Not Exists(Select bookID From tabBookingHeader Where bookID='$kodeBook') Begin
				    Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
					   				      Values('$kodeBook',0,'$kodeBook','$kodeCustomer','$vesselName','$dateETA','$feeder','$voyageNumber','$dateETA','$target'); 
				  End; "; 				   
		   $reslExec = mssql_query($do);	
		   if($reslExec) { $success++; }			  
		  }
/*		  
		  if($rows > 0) {
		    mssql_free_result($resl);
		    $do= "Select * From containerJournal Where NoContainer = '$containerNo' And gateIn Is Not Null And gateOut Is Null";
			$resl = mssql_query($qry);
			$rows = mssql_num_rows($resl);
			if($rows == 1) {
			  $cols=mssql_fetch_array($resl);
			  $Book=$cols['bookInID'];
			  
			  mssql_free_result($resl);
			  $do= "Update tabBookingHeader Set principle='$kodeCustomer', vessel='$vesselName',vesselATA='$dateETA',
							                    operatorID='$feeder', voyageID='$voyageNumber', ETA='$dateETA', SLDFileName='$target' 
					Where BookID='$Book'; ";
			  $reslExec = mssql_query($do);
			  echo $do."<br>";
			  //echo "'$containerNo' have event in already"."<br>";
			  $haveEvent++;
			}
			else if($rows <= 0) {
				   
				 } 
			
		  }
*/		  
        }		
/*		
        $queryall=$queryall."If Not Exists(Select * From containerJournal Where NoContainer = '$containerNo' And gateIn is Not Null) Begin
	                           Insert Into containerJournal(bookInID, NoContainer, Cond, TruckingIn, VehicleInNumber, isPending, Remarks, isCleaning, isRepair, workshopID)
	                                                 Values('$kodeBook', REPLACE('$containerNo',' ',''), '$Cond', '', '', '$isPending', '$remark', 0,0, ''); 
						     
                               If Not Exists(Select bookID From tabBookingHeader Where bookID='$kodeBook') Begin
		                         Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
		  		                                       Values('$kodeBook',0,'$kodeBook','$kodeCustomer','$vesselName','$dateETA','$feeder','$voyageNumber','$dateETA','$target'); 
				               End;
							 End Else Begin
							      Update tabBookingHeader Set principle='$kodeCustomer', vessel='$vesselName',vesselATA='$dateETA',
							                                  operatorID='$feeder', voyageID='$voyageNumber', ETA='$dateETA', SLDFileName='$target'
							      Where BookID In (Select bookInID As BookID From containerJournal Where NoContainer = '$containerNo' And gateIn Is Not Null);
                                 End;							 
							 			  
	                         If Not Exists(Select containerNo From containerLog Where ContainerNo=REPLACE('$containerNo',' ','')) Begin 
			                   Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
			                   Values(REPLACE('$containerNo',' ',''), 1, '/', 0, '$contSize', '$contType', '$contHeight', 'STL'); 
			                 End; ";	  */        
	  }	
	  //$result=mssql_query($queryall); 	
	}
    echo '<script language="javascript">document.getElementById("info").innerHTML="Done";</script>'; 
  }  
  mssql_close($dbSQL);
  
  unlink($target);
  
  $url = "/e-imp/mnr/?do=sldhW&page=loadsld&success=".$success."&haveE=".$haveEvent;
  //echo "<script type='text/javascript'>location.replace('$url');</script>";   
?>