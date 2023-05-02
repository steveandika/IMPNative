<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />

<div class="height-10"></div>
<div class="wrapper">  
  <div id="info"></div>
</div>
		  
<?php
  include("../asset/libs/db.php");
  include("../asset/libs/upload_reader.php");
  
  if(isset($_POST["location"])) {$loc = $_POST["location"];}
  else {$loc="SLD";}
  		  
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
	$tmp="";
	$haveEvent=0;
	
	$logDate=date('Y-m-d');	
	$query="IF NOT EXISTS(Select 1 From logSLD With (NOLOCK) Where SLDFileName='$target') BEGIN
              INSERT INTO logSLD(SLDFileName, logDate) VALUES('$target', '$logDate'); 
			END ELSE BEGIN
			      UPDATE logSLD SET logDate='$logDate' WHERE SLDFileName='$target';
			    END";
    $result=mssql_query($query);	
	
	$queryall="";	
	$kodeBook=str_replace("-", "", date('Y-m-d'));
	$kodeBook=$loc.substr($kodeBook,0,1).substr($kodeBook,2,6).str_ireplace(":", "", date("h:i"));
	$success = 0;
	
    for ($i=3; $i<=$baris; $i++) {
      $k = $i-1;

      echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$k.' data successfully inserted .";</script>';			
    
      $tmp = $data->val($i, 2); //GP/RF
	  $contType=str_replace(" ","",$tmp);
      
	  $tmp = $data->val($i, 3); //HEIGHT 
	  $contHeight=str_replace(" ","",$tmp);
      
	  $tmp = $data->val($i, 4); //SIZE
	  $contSize=str_replace(" ","",$tmp);
      
	  $tmp = $data->val($i, 5); //CONTAINERNO		  
	  $containerNo=str_replace(" ","",$tmp);
      
	  $remark = $data->val($i, 6); //REMARK
      $principleName = $data->val($i, 7); //PRINCIPLE	
      $namaFeeder_tmp = $data->val($i, 8); //FEEDER
      $vesselName_tmp = $data->val($i, 9); //VESSEL	
      $voyageNumber_tmp = $data->val($i, 10); //VOYAGE
	  
      $tmp = $data->val($i, 11);  //ETA	
	  $dateETA_tmp=str_replace(" ","",$tmp);
      
	  $tmp = $data->val($i, 12);  //DM/AV
	  $Cond=str_replace(" ","",$tmp);
	  
	  $containerNo = str_replace(' ','',$containerNo);	 
	  
	  if(strlen($containerNo) == 11) {	  
	    if($Cond== "" || $Cond == "AV") {$isPending = 'N';}
	    else {$isPending = 'Y';}
	  	  
	    if($namaFeeder == '') {$namaFeeder = strtoupper(rtrim($namaFeeder_tmp));}
	    else if(trim($namaFeeder_tmp) != '' && rtrim($namaFeeder_tmp) != $namaFeeder) {$namaFeeder = strtoupper(rtrim($namaFeeder_tmp));} 
	    if($vesselName == '') {$vesselName = strtoupper(rtrim($vesselName_tmp));}
	    else if(trim($vesselName_tmp) != '' && rtrim($vesselName_tmp) != $vesselName) {$vesselName = strtoupper(rtrim($vesselName_tmp));} 
	    if($voyageNumber == '') {$voyageNumber = strtoupper(rtrim($voyageNumber_tmp));}
	    else if(trim($voyageNumber_tmp) != '' && rtrim($voyageNumber_tmp) != $voyageNumber) {$voyageNumber = strtoupper(rtrim($voyageNumber_tmp));} 	  
		
	    if(trim($dateETA_tmp) !="") {
	      if($dateETA =="") {$dateETA = trim($dateETA_tmp);}
	      else if(trim($dateETA_tmp) != '' && trim($dateETA_tmp) != $dateETA) {$dateETA = trim($dateETA_tmp);} 
	    }
	  
	    $sizeCode=0;
	    if((trim($contSize) !="") && (trim($contHeight) !="")) {
	      $contSize = trim($contSize);
	      $contHeight = trim($contHeight);	  	  
		  $ISOCode=$contSize.$contHeight;
		  $deskripsi="UPLOAD FROM STREAM";
		
	      $query="IF NOT EXISTS(SELECT 1 FROM m_ISOCode WITH (NOLOCK) WHERE Size='$contSize' AND Tipe='$contHeight') BEGIN 
                   DECLARE @NewIndex Int, @LastIndex Int; 
		          
			 	   SELECT @LastIndex=MAX(IDISO) FROM m_ISOCODE;
				   SET @NewIndex = @LastIndex +1;
				  
				   INSERT INTO m_ISOCODE(IDISO, ISOCode, DescriptionISO, Size, Tipe) 
				                  VALUES(@NewIndex, '$ISOCode', '$deskripsi', '$contSize', '$contHeight');		        
				  End; ";
	      $result=mssql_query($query);
          	 
          $query="Select IDISO From m_ISOCode With (NOLOCK) Where Size='$contSize' And Tipe='$contHeight'";
	      $result=mssql_query($query);
	      if(mssql_num_rows($result) > 0) {
	        $arr=mssql_fetch_array($result);
		    $sizeCode=$arr["IDISO"]; 		    
		  }		
		  mssql_free_result($result); 
	    }
		
	    $kodeCustomer='';
	    if(trim($principleName) != '') {
		  $keywrd='%'.str_replace(' ','',strtoupper($principleName)).'%';
		  $query="SELECT custRegID FROM m_Customer WITH (NOLOCK) WHERE Replace(completeName, ' ', '') LIKE '$keywrd'; ";
		  $result=mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $kodeCustomer = $arr['custRegID'];		    
		  }	
		  mssql_free_result($result);
        }		  
	  
	    $feeder='';
	    if(trim($namaFeeder) != '') {
		  $keywrd='%'.str_replace(' ','',strtoupper($namaFeeder)).'%';
		  $query="Select custRegID From m_Customer WITH (NOLOCK) Where Replace(completeName, ' ', '') Like '$keywrd'; ";
		  $result=mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $feeder = $arr['custRegID'];		    
		  }
		  else {
			mssql_free_result($result);
			
	        $query="DECLARE @KeyField VarChar(4); 
	                IF NOT EXISTS(Select 1 From logKeyField WITH (NOLOCK) Where keyFName Like 'CSTM') BEGIN 
	                  Set @KeyField='C001';
	                  Insert Into logKeyField Values('CSTM', 1); 
	                END ELSE BEGIN
	                      Declare @LastKey Int, @StrLastKey VarChar(3);
						  
	                      Select @LastKey=lastNumber +1 From logKeyField WITH (NOLOCK) Where KeyFName Like 'CSTM'; 
	                      Set @StrLastKey = LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey))); 
						  
	                      if LEN(@StrLastKey) = 1 Set @KeyField = CONCAT('C00', @StrLastKey); 
	                      if LEN(@StrLastKey) = 2 Set @KeyField = CONCAT('C0', @StrLastKey); 
	                      if LEN(@StrLastKey) = 3 Set @KeyField = CONCAT('C', @StrLastKey); 
						  
	                      Update logKeyField Set lastNumber=lastNumber +1 Where KeyFName Like 'CSTM'; 
	                    END;
	              
				    INSERT INTO m_Customer(custRegID, completeName, isPPNInclude, asExp, asImp, asLogParty, asMLO, asFeed, asSupp, asOther) 
				                    VALUES(@KeyField, '".strtoupper($namaFeeder)."', 1, 0, 0, 0, 0, 1, 0, 0); ";
	  	    $result = mssql_query($query); 
		 
		    $keywrd='%'.str_replace(' ','',strtoupper($namaFeeder)).'%';
		    $query="SELECT custRegID FROM m_Customer WITH (NOLOCK) WHERE Replace(completeName, ' ', '') LIKE '$keywrd'";
		    $result=mssql_query($query);
		    if(mssql_num_rows($result) > 0) {
		      $arr = mssql_fetch_array($result);
		      $feeder = $arr['custRegID'];			  
		    }
			mssql_free_result($result);
		  }	
        }		  

	  	$eventTime = date('h:i');
        $qry = "SELECT COUNT(1) AS FoundRow FROM containerJournal WITH (NOLOCK) WHERE NoContainer = '$containerNo' AND gateIn IS NOT NULL AND gateOut IS NULL 
		        AND (bookInID NOT LIKE '%BATAL' AND bookInID NOT LIKE '%*'); ";
        $resl = mssql_query($qry);
		$arr=mssql_fetch_array($resl);
		$foundRow=$arr["FoundRow"];
		mssql_free_result($resl);
		
		
        if($foundRow <= 0)	{		  
		  $do= "INSERT INTO containerJournal(bookInID, NoContainer, JamIn, Cond, TruckingIn, VehicleInNumber, isPending, Remarks, isCleaning, isRepair, workshopID, GIPort)
	                                  VALUES('$kodeBook', REPLACE('$containerNo',' ',''), '$eventTime', 'DM', '', '', 'Y', '$remark', 0,0, '$loc', '$dateETA'); ";
		  $exec_resl=mssql_query($do);
				
		  $do= "IF NOT EXISTS(Select ContainerNo From containerLog WITH (NOLOCK) Where ContainerNo=REPLACE('$containerNo',' ','')) Begin
				  INSERT INTO containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
				                    VALUES(REPLACE('$containerNo',' ',''),1,'',0, '$constSize', '', '$contHeight', '');
                END;";
		  $exec_resl=mssql_query($do);
		  				
		  $do= "IF NOT EXISTS(Select bookID From tabBookingHeader WITH (NOLOCK) Where bookID='$kodeBook') Begin
		                         Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,operatorID,voyageID,ETA,SLDFileName)
		  		                                       Values('$kodeBook',0,'$kodeBook','$kodeCustomer','$vesselName','$dateETA','$feeder','$voyageNumber','$dateETA','$target'); 
				END; ";
		  $exec_resl=mssql_query($do);				
				
		  if($execRes) { $success++; }
		  
        } else {
            $sql="SELECT bookInID FROM containerJournal WITH (NOLOCK) WHERE NoContainer = '$containerNo' AND gateIn IS NOT NULL AND gateOut IS NULL 
		          AND (bookInID NOT LIKE '%BATAL' AND bookInID NOT LIKE '%*'); ";
            $resl=mssql_query($sql); 														  
		    while($arr_fetch=mssql_fetch_array($resl)) {
		      $kodeBook=$arr_fetch["bookInID"];
  		      $sql= "UPDATE tabBookingHeader SET principle='$kodeCustomer', vessel='$vesselName',vesselATA='$dateETA',
							                   operatorID='$feeder', voyageID='$voyageNumber', ETA='$dateETA', SLDFileName='$target' 
				  	 WHERE BookID='$kodeBook'; ";
			  $exec_resl=mssql_query($sql);
		    }
		    mssql_free_result($resl);
          }		
	  }	
	}
	
    echo '<script language="javascript">document.getElementById("info").innerHTML="";</script>'; 
  }  
  mssql_close($dbSQL);  
  unlink($target);
  
  $url = "/e-imp/mnr/?do=sldhW&page=loadsld";
  //echo "<script type='text/javascript'>location.replace('$url');</script>";   
  
  if($success==0) { ?>
  <div class="w3-container">   
	  <div class="w3-container" style="border:0;border-left:3px;border-style:solid;border-color:#d5d8dc;margin:0 auto;">
	    Upload process was failed. Please check wether you have trouble issue in Internet Connection or there was 
		some error at your column format.
		<div class="height-10"></div>
		<a href="<?php echo $url;?>" class="w3-button w3-pink" style="text-decoration:none;outline:none">Confirm</a>		
		<div class="height-10"></div>
	  </div>
  </div>  
<?php 
  }
  if($success>0) { ?>
  <div class="w3-container" >    
	  <div class="w3-container" style="border:0;border-left:3px;border-style:solid;border-color:#d5d8dc;margin:0 auto;">
	    <?php echo "Process load was finished. <br> Rejected ".$haveEvent." line(s). Accepted ".$success." line(s)."?>
		<div class="height-10"></div>
		<a href="<?php echo $url;?>" class="w3-button w3-pink" style="text-decoration:none;outline:none">Confirm</a>
		<div class="height-10"></div>
	  </div> 
  </div>    
<?php 
  } 
?>  