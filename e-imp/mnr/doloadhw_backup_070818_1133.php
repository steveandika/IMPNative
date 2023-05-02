<?php
  session_start();
?>

<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
<div class="height-10"></div>  
<div class="w3-container"><div id="info"></div></div> 

<?php  
  $location = $_POST['location'];
  if(isset($_FILES['HWFileName']['name'])) {
    include("../asset/libs/db.php");
    include("../asset/libs/upload_reader.php"); 
	include("../asset/libs/common.php"); 
			
    $target = basename($_FILES['HWFileName']['name']);	
	
    move_uploaded_file($_FILES['HWFileName']['tmp_name'], $target);	  
	$data = new Spreadsheet_Excel_Reader($_FILES['HWFileName']['name'],false);
    $baris = $data->rowcount($sheet_index=0);
    
	$kodeBook_Before = '';
	$principleName_tmp = '';
	$cond = 'DM';
	$pending = 'Y';
	$contHeight = 'STD';
	$contType = 'GP';
	
	$err=0;
	
    for ($i=2; $i<$baris; $i++) {
      $containerNo1 = strtoupper($data->val($i, 1)); // Container Prefix
	  $containerNo1=str_replace(" ","",$containerNo1);
      $containerNo2 = $data->val($i, 2); // Container Infix
	  $containerNo2=str_replace(" ","",$containerNo2);
      $containerNo3 = $data->val($i, 3); // CD	  
	  $containerNo3=str_replace(" ","",$containerNo3);
	  
	  $contSize = $data->val($i, 4); // Size
	  
      $dateIn = $data->val($i, 5); //-- Hamparan In
	  $dateIn=str_replace(" ","",$dateIn);
      $dateport = $data->val($i, 6); //-- Port In	  
	  $dateport=str_replace(" ","",$dateport);
      $dateOut = $data->val($i, 7); //-- Hamparan Out	  
	  $dateOut=str_replace(" ","",$dateOut);
      $crdate = $data->val($i, 8); //-- C/R	  
	  $crdate=str_replace(" ","",$crdate);
	  $ccdate = $data->val($i, 9); //-- C/C	  
	  $dateIn=str_replace(" ","",$dateIn);
	  $cctype = $data->val($i, 10); //-- Jenis Cleaning	  
	  $cctype=str_replace(" ","",$cctype);
	  
	  $NoContainer = $containerNo1.$containerNo2.$containerNo3;
	  $NoContainer=str_replace(" ","",$NoContainer);
	  
	  echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$NoContainer.' .. reading on '.$i.' of '.$baris.'";</script>';			
	  $eventTime = date('h:i');
	  
	  $valid_row=1;
	  if(strlen($NoContainer)==11) {
	    $isOK=validUnitDigit($NoContainer);
        if($isOK=="OK") { $valid_row=1; }		
	  } 

	  if($valid_row==1) {  
        $contSize = str_replace(" ","",$contSize);
        $contHeight = str_replace(" ","",$contHeight);	  	  
	    
		if($contSize!="") {
  	      $do="IF NOT EXISTS(Select * From containerLog Where containerNo = '$NoContainer') BEGIN
	             Insert Into containerLog(containerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
		  	  		               Values('$NoContainer', 1, '/', 0, '$contSize', '$contType', '$contHeight', 'STL');
			   END;";
		  $rsl=mssql_query($do);		 
	    }	  
		
		if($dateIn != "") {
		  $kodeBook="";		  
		  
          $qry="SELECT COUNT(1) AS jumlahBrs FROM containerJournal 
		        WHERE NoContainer='$NoContainer' AND gateIn='$dateIn' 
		          AND tanggalSurvey IS NULL AND bookInID NOT LIKE '%BATAL' AND bookInID NOT LIKE '%*'";
          $rsl=mssql_query($qry);
		  while($row=mysql_fetch_array($rsl)) { $numrows=$row["jumlahBrs"]; }
		  mssql_free_result($rsl);
		  
		  if($numrows <= 0) {
            $kodeBook=str_replace('-', '', $dateIn); 
		    $kodeBook=$location.substr($kodeBook,0,1).substr($kodeBook,2,6);			  
  			  
			$fname=date("Y-m-d");
			$do="Declare @bookInID VarChar(30), @LastIndex_ Int; 
			     If Not Exists(Select * From logKeyField Where keyFName Like '".$kodeBook.'%'."') Begin
			       Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
			       Set @bookInID = CONCAT('".$kodeBook."','1');			            
			       
				 End Else Begin  
			           Select @LastIndex_ = lastNumber +1 From logKeyField Where keyFName Like '".$kodeBook.'%'."';
					   
                       Update logKeyField Set lastNumber  =lastNumber +1 Where keyFName Like '".$kodeBook.'%'."';                            
				       Set @bookInID = CONCAT('".$kodeBook."', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex_)))); 
			          End;	
                  
				 Insert Into tabBookingHeader(bookID, bookType, blID, principle, consignee, operatorID, SLDFileName) 
			                           Values(@bookInID, 0, @bookInID, '', '', '', '$fname'); 
										 
				 Select bookID From tabBookingHeader Where bookID=@bookInID; "; 									  
			$rsl=mssql_query($do);
			if(!$rsl) {$err++;}
			else { 
			  $col=mssql_fetch_array($rsl);
			  $kodeBook=$col["bookID"];
			  mssql_free_result($rsl);			  
			}
			  
            $do="INSERT INTO containerJournal(bookInID, NoContainer, gateIn, jamIn, Cond, isPending, Remarks, isCleaning, isRepair, workshopID,gateOut, GIPort)
			                           VALUES('$kodeBook', '$NoContainer', '$dateIn', '$eventTime', 'DM', 'Y', '', 1, 1, '$location','$dateOut','$dateport'); ";						 			
			$rsl=mssql_query($do);
			if(!$rsl) {$err++;}				
			else { 
			  if($crdate != "") {
			    $do="UPDATE containerJournal SET CRDate='$crdate' WHERE NoContainer='$NoContainer' AND gateIn='$dateIn'; ";
                $rsl=mssql_query($do);				
			  }	  
			  
			  if($cctype != "") {
			    $do="UPDATE containerJournal SET cleaningType='$cctype' WHERE NoContainer='$NoContainer' AND gateIn='$dateIn'; ";
                $rsl=mssql_query($do);					  
			  }	  
			  
			  if($ccdate != "") {
			    $do="UPDATE containerJournal SET CCleaning='$ccdate' WHERE NoContainer='$NoContainer' AND gateIn='$dateIn'; ";
                $rsl=mssql_query($do);					  
			  }	  			  
			}  
		  }	  
		}   
/* 
          $kodeBook="";		  
          $qry="SELECT bookInID FROM containerJournal WHERE NoContainer='$NoContainer' AND gateIn='$dateIn'";
          $rsl=mssql_query($qry);
          while($arr=mssql_fetch_array($rsl)) { $kodeBook=$arr["bookInID"];	}
          mssql_free_result($rsl);			
		  
		  if($crdate != "" && $kodeBook !="") {			
			$do="UPDATE RepairHeader SET FinishRepair='$crdate' WHERE BookID='$kodeBook'";
			$exec_rsl=mssql_query($do);
			
			$do="UPDATE containerJournal SET CRDate='$crdate' WHERE bookInID='$kodeBook'";
			$exec_rsl=mssql_query($do);
		  }
		  
		  if($ccdate != "" && $kodeBook !="") {
		    $do="UPDATE containerJournal SET CCleaning='$ccdate' WHERE bookInID='$kodeBook'";
            $exec_rsl=mssql_query($do);			
		  }	  
		 
          if(($ccdate != "" || $crdate != "") && $kodeBook !="") {			  
 	        if(strtotime($crdate) >= strtotime($ccdate)) { $avcond=$crdate; }
	        if(strtotime($crdate) < strtotime($ccdate)) { $avcond=$ccdate; }
	        $do="UPDATE containerJournal SET AVCond='$avcond', Cond='AV', isPending='N', gateOut='$avcond' WHERE bookInID = '$kodeBooking' AND NoContainer = '$NoContainer'; ";
            $exec_rsl=mssql_query($do);	 		
          }		
*/		  


/*  	    if($kodeBook!="") {
          $do="";
	   	  $tmp="";

  	      if($dateport !="") { $tmp="GIPort='$dateport' "; }
          if($crdate !="") {
			if($tmp=="") { $tmp="CRDate='$crdate' "; }
            else { $tmp=$tmp.", CRDate='$crdate' "; }	
          }			  
          if($ccdate != "" && $cctype!="") {
			if($tmp=="") { $tmp="CCleaning='$ccdate', cleaningType='$cctype' "; }
            else { $tmp=$tmp.", CCleaning='$ccdate', cleaningType='$cctype' "; }	
          }			  
		  if($crdate!="" && $ccdate!="") {
			if(strtotime($crdate) > strtotime($ccdate)) { $avcond=$crdate; }
			if(strtotime($crdate) < strtotime($ccdate)) { $avcond=$ccdate; }			  
			if(strtotime($crdate) == strtotime($ccdate)) { $avcond=$crdate; }			  
			  
			if($tmp=="") { $tmp="AVCond='$ccdate', Cond='AV', isPending='N' "; }
            else { $tmp=$tmp.", AVCond='$ccdate', Cond='AV', isPending='N' "; }				  
	      }	

  	      if($tmp!="") {
		    $do="UPDATE containerJournal SET ".$tmp." WHERE NoContainer='$NoContainer' AND bookInID ='$kodeBook' ";  	
			$rsl=mssql_query($do);
			if(!$rsl) {$err++;}
		  }	  
		  
          if($ccdate!="" && $cctype!="") {
            $postTgl = trim(str_ireplace("-","",$ccdate));
            $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
		
            $do="DECLARE @bookInID VARCHAR(30);
			     If Not Exists(Select * From CleaningHeader Where containerID='$keywrd' And bookID=@bookInID) Begin
     	           Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		           Set @ToDay_ = '$ccdate'; 
		   	       Set @Keywrd=CONCAT('$postTgl','%');
				
		           If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
				     Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                     Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
				     Set @NewDraft=CONCAT('$postTgl', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
			       End Else Begin 
				         Insert Into logKeyField(keyFName, lastNumber) Values('$postTgl', 1);
				         Set @NewDraft=CONCAT('$postTgl', '.1');
				       End;
			  
                   Insert Into CleaningHeader(cleaningID, containerID, nilaiDPP, bookID, invoiceNumber, estimateID) 
                                       Values(@NewDraft, '$NoContainer', 0, '$bookInID', '', '');			 
                   Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                       Values(@NewDraft, '', 0, '', '$cctype');										
	             End; ";						
			  $rsl=mssql_query($do);
			  if(!$rsl) {$err++;}			  
			}		  
		}	*/ /* end of $kodeBook not Empty */		
	  }	
	}  
    echo '<script language="javascript">document.getElementById("info").innerHTML="Done";</script>'; 		

    $do="Update containerJournal Set GIPort=Null Where GIPort='1900-01-01';
	     Update containerJournal Set CRDate=Null Where CRDate='1900-01-01'; 
		 Update containerJournal Set CCleaning=Null Where CCleaning='1900-01-01'; 
	     Update containerJournal Set gateOut=Null Where gateOut='1900-01-01'; ";
	$rslExec=mssql_query($do);
	
	$uid=$_SESSION['uid'];
	$rmrk="UPLOAD_LHW";
	$do="INSERT INTO userLogAct(userID, dateLog, DescriptionLog) VALUES('$uid', GETDATE(), '$rmsrk');";
    $rslExec=mssql_query($do); 	

    mssql_close($dbSQL);		
	unlink($target);
	
	$url = "/e-imp/mnr/?do=dolhW&page=loadhw";
	if($err>0) { 
?>
     <div class="w3-container">   
	  <div class="w3-container" style="border:0;border-left:3px;border-style:solid;border-color:#d5d8dc;margin:0 auto;">
	    Upload process was failed. Please check wether you have trouble issue in Internet Connection or there was 
		some error at your column format.
		<div class="height-10"></div>
		<a href="<?php echo $url;?>" class="w3-button w3-pink" style="text-decoration:none;outline:none">< Confirm ></a>		
		<div class="height-10"></div>
	   </div>
      </div>  
<?php 
    }
    if($err==0) { 
?>
  <div class="w3-container" >    
	  <div class="w3-container" style="border:0;border-left:3px;border-style:solid;border-color:#d5d8dc;margin:0 auto;">
	    <?php echo "Process load was finished. <br> Rejected ".$haveEvent." line(s). Accepted ".$success." line(s)."?>
		<div class="height-10"></div>
		<a href="<?php echo $url;?>" class="w3-button w3-pink" style="text-decoration:none;outline:none">< Confirm ></a>
		<div class="height-10"></div>
	  </div> 
  </div>    
<?php 
    } 	
  } 
  else {
    $url = "/e-imp/mnr/?do=dolhW&page=loadhw&error=1";
    echo "<script type='text/javascript'>location.replace('$url');</script>"; 		  
  }
?>