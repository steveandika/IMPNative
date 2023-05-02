<?php
  session_start();
?>

<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
<div class="height-10"></div>  
<div class="w3-container">
  <div id="info"></div>
</div> 

<?php  
  $location = $_POST["location"];
  
  if(isset($_FILES['HWFileName']['name'])) {
    include("../asset/libs/db.php");
    include("../asset/libs/upload_reader.php"); 
	include("../asset/libs/common.php"); 
			
    $target = basename($_FILES["HWFileName"]["name"]);	
	
    move_uploaded_file($_FILES["HWFileName"]["tmp_name"], $target);	  
	$data = new Spreadsheet_Excel_Reader($_FILES["HWFileName"]["name"],false);
    $baris = $data->rowcount($sheet_index=0);
    
	$kodeBook_Before ="";
	$principleName_tmp ="";
	$contHeight ="STD";
	$contType ="GP";
	
	$err=0;
	$success=0;
	$rejected="";
	$reject_count=0;
	
    for ($i=2; $i<=$baris; $i++) {
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
      //$dateOut = $data->val($i, 7); //-- Hamparan Out	  
	  //$dateOut=str_replace(" ","",$dateOut);
      $crdate = $data->val($i, 8); //-- C/R	  
	  $crdate=str_replace(" ","",$crdate);
	  $ccdate = $data->val($i, 9); //-- C/C	  
	  $dateIn=str_replace(" ","",$dateIn);
	  $cctype = $data->val($i, 10); //-- Jenis Cleaning	  
	  $cctype=str_replace(" ","",$cctype);
	  
	  $NoContainer = $containerNo1.$containerNo2.$containerNo3; 
	  
	  echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$NoContainer.' .. reading on '.$i.' of '.$baris.'";</script>';			
	  $eventTime = date('h:i');
	  
	  $valid_row=1;
	  if(strlen($NoContainer)==11) {
	    $isOK=validUnitDigit($NoContainer);
        if($isOK=="OK") { $valid_row=1; }		
	  } 
	  if($valid_row==1) {  
        $contSize = str_replace(" ","",$contSize);
	    
		if($contSize!="") {
  	      $do="If Not Exists(Select containerNo From containerLog Where containerNo = '$NoContainer') Begin
	             Insert Into containerLog(containerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
		  	  		               Values('$NoContainer', 1, '/', 0, '$contSize', '$contType', '$contHeight', 'STL');
			   End;";
		  $rsl=mssql_query($do);		 
	    }	  
		
		if($dateIn != "") {
		  if ($dateport =="") { $dateport=$dateIn; }
		  
		  $kodeBook="";		  
		  
          $qry="Select COUNT(1) AS jumlahBrs From containerJournal Where NoContainer='$NoContainer' And gateOut IS NULL And bookInID NOT LIKE '%BATAL' And bookInID NOT LIKE '%*'";
          $rsl=mssql_query($qry);
		  while($row=mssql_fetch_array($rsl)) { $numrows=$row["jumlahBrs"]; }
		  mssql_free_result($rsl);
		  
		  if($numrows <= 0) {
            $kodeBook=str_replace("-", "", $dateIn); 
		    $kodeBook=$location.substr($kodeBook,0,1).substr($kodeBook,2,6);			  

			$do="Declare @bookInID VarChar(30), @LastIndex_ Int; 
			     If Not Exists(Select keyFName From logKeyField Where keyFName ='".$kodeBook."') Begin
			       Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
			       Set @bookInID = CONCAT('".$kodeBook."','1');			            
			       
				 End Else Begin  
			           Select @LastIndex_ = lastNumber +1 From logKeyField Where keyFName ='".$kodeBook."';
					   
                       Update logKeyField Set lastNumber=lastNumber +1 Where keyFName ='".$kodeBook."';                            
				       Set @bookInID = CONCAT('".$kodeBook."', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex_)))); 
			          End;	
                  
				 Insert Into tabBookingHeader(bookID, bookType, blID, principle, consignee, operatorID, SLDFileName) 
			                           Values(@bookInID, 0, @bookInID, '', '', '', '$target'); 
										 
				 Select bookID From tabBookingHeader Where bookID=@bookInID; "; 									  
			$rsl=mssql_query($do);
		
			if(!$rsl) {$err++;}
			else { 
			  $col=mssql_fetch_array($rsl);
			  $kodeBook=$col["bookID"];
			  mssql_free_result($rsl);			  
			}
			  
            $do="INSERT INTO containerJournal(bookInID, NoContainer, gateIn, jamIn, Cond, isPending, Remarks, isCleaning, isRepair, workshopID, GIPort)
			                           VALUES('$kodeBook', '$NoContainer', '$dateIn', '$eventTime', 'AV', 'N', '', 0, 0, '$location','$dateport'); ";						 			
			$rsl=mssql_query($do);
			
			if(!$rsl) { $err++; }				
			else { 
			  $success++;
			  
			  $sql="";
			  if($crdate != "") { $sql="UPDATE containerJournal SET CRDate='$crdate', isRepair=1 WHERE bookInID='$kodeBook'; "; }
			  if($cctype != "") { $sql=$sql."UPDATE containerJournal SET cleaningType='$cctype' WHERE bookInID='$kodeBook'; "; }
			  if($ccdate != "") { $sql=$sql."UPDATE containerJournal SET CCleaning='$ccdate', isCleaning=1 WHERE bookInID='$kodeBook'; "; } 
              $rsl=mssql_query($sql);				
			}  
		  }	  
		  else {
			$rejected=$rejected."Container No: ".$NoContainer.", GateIn: ".$dateIn."<br>";
			$reject_count++;
		  }	  
		}   
	  }
      else {
		$rejected=$rejected."Container No: ".$NoContainer.", GateIn: ".$dateIn." (invalid unit number)"."<br>";
		$reject_count++;		  
	  }	  
	}  
    echo '<script language="javascript">document.getElementById("info").innerHTML="Done";</script>'; 		

    $do="Update containerJournal Set GIPort=Null Where GIPort='1900-01-01';
	     Update containerJournal Set CRDate=Null Where CRDate='1900-01-01'; 
		 Update containerJournal Set CCleaning=Null Where CCleaning='1900-01-01'; 
	     Update containerJournal Set gateOut=Null Where gateOut='1900-01-01'; ";
	$rslExec=mssql_query($do);
	
	if ($_SESSION["uid"] !="ROOT") {
	  $uid=$_SESSION["uid"];	
	  $remark="Upload Hamparan Workshop";
	
	  $upload_dttm=date("Y-m-d h:i:s");
	  $do="INSERT INTO userLogAct(userID, dateLog, DescriptionLog) VALUES('$uid', '$upload_dttm', '$remark');";
      $rslExec=mssql_query($do); 	
	}  

    mssql_close($dbSQL);		
	//unlink($target);
	
	$url = "/e-imp/mnr/?do=dolhW&page=loadhw";	
?>
    <div class="w3-container">   
	  <div class="w3-container" style="border:0;border-left:3px;border-style:solid;border-color:#d5d8dc;margin:0 auto;">
	    <?php if($err>0) {   
	            echo "Upload process was failed. Please check wether you have trouble issue in Internet Connection or there was 
		               some error at your column format.";
		      }		   
			  else {
				echo "Process load was finished. <br> Rejected ".$reject_count." line(s). Accepted ".$success." line(s)." ;
                echo "Rejected Line: <br>".$rejected."<br>";				
			  }	  
		?>	  
	   <div class="height-10"></div>
	   <strong><a href="<?php echo $url;?>" style="text-decoration:none;outline:none;cursor:pointer">< Confirm ></a></strong>		
	   <div class="height-10"></div>
	 </div>
    </div>  
<?php 
  } 
  else {
    $url = "/e-imp/mnr/?do=dolhW&page=loadhw&error=1";
    echo "<script type='text/javascript'>location.replace('$url');</script>"; 		  
  }
?>