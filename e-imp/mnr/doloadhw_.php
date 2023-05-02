<?php
  session_start();
?>

  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />  
  <div class="height-10"></div>  
  <div class="w3-container">
   <div id="info" style="font-family:Roboto,sans-serif;font-size:13px"></div>
  </div> 

<?php  
  $location = $_POST['location'];
  if(isset($_FILES['HWFileName']['name'])) {
    include("../asset/libs/db.php");
    include("../asset/libs/upload_reader.php"); 
			
    $target = basename($_FILES['HWFileName']['name']);	
	
    move_uploaded_file($_FILES['HWFileName']['tmp_name'], $target);	  
	$data = new Spreadsheet_Excel_Reader($_FILES['HWFileName']['name'],false);
    // menghitung jumlah baris file xls
    $baris = $data->rowcount($sheet_index=0);
    // nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
    // import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
    
	$kodeBook_Before = '';
	$principleName_tmp = '';
	$cond = 'DM';
	$pending = 'Y';
	$contHeight = 'STD';
	$contType = 'GP';
	
	$err=0;
	
    for ($i=2; $i<=$baris; $i++) {
      $containerNo1 = strtoupper($data->val($i, 1)); // CONTAINER PREFIX
	  $containerNo1=str_replace(' ','',$containerNo1);
      $containerNo2 = $data->val($i, 2); // CONTAINER INFIX
	  $containerNo2=str_replace(' ','',$containerNo2);
      $containerNo3 = $data->val($i, 3); // CD	  
	  $containerNo3=str_replace(' ','',$containerNo3);
	  $contSize = $data->val($i, 4); // Size
//      $principleName = strtoupper($data->val($i, 5)); // PRINCIPLE 	
      $dateIn = $data->val($i, 5); //-- HAMPARAN IN DATE
	  $dateIn=str_replace(' ','',$dateIn);
      $GIport = $data->val($i, 6); //-- PORT IN DATE	  
      $cond = $data->val($i, 7); //-- EVENT IN DATE	  
      $dateOut = $data->val($i, 8); //-- HAMPARAN IN DATE	  
	  $NoContainer = $containerNo1.$containerNo2.$containerNo3;
	  $NoContainer=str_replace(' ','',$NoContainer);
	  
	  echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$NoContainer.' .. reading on '.$i.' of '.$baris.'";</script>';			
	  $eventTime = date('h:i');
	  
	  if($dateIn != '' && $containerNo1 != '') {
 	    if((trim($contSize) != '') && (trim($contHeight) != '')) {
	      $contSize = str_replace(' ','',$contSize);
	      $contHeight = str_replace(' ','',$contHeight);	  	  
/*
		  $ISOCode = $contSize.$contHeight;
		  $deskripsi = 'UPLOAD FROM STREAM';
		
	      $query = "If Not Exists(Select * From m_ISOCode Where Size='$contSize' And Tipe='$contHeight') Begin 
                      Declare @NewIndex Int, @LastIndex Int; 		          
				      Select @LastIndex = MAX(IDISO) From m_ISOCODE;
				      Set @NewIndex = @LastIndex +1;				  
				      Insert Into m_ISOCODE(IDISO, ISOCode, DescriptionISO, Size, Tipe) 
				      Values(@NewIndex, '$ISOCode', '$deskripsi', '$contSize', '$contHeight');		        
				    End; ";
	      $result = mssql_query($query);
*/		  
	    }
/*	  
	    $kodeCustomer = '';
	    if(trim($principleName) != '') {
		  $keywrd = '%'.str_replace(' ','',strtoupper($principleName)).'%';
		  $query = "Select custRegID From m_Customer Where Replace(completeName, ' ', '') Like '$keywrd' And asMLO=1; ";
		  $result = mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $kodeCustomer = $arr['custRegID'];
		    mssql_free_result($result);
		  }	
        }	
		
		$kodeConsignee = '';
	    if(trim($consignee) != '') {
		  $keywrd = '%'.str_replace(' ','',strtoupper($consignee)).'%';
		  $query = "Select custRegID From m_Customer Where Replace(completeName, ' ', '') Like '$keywrd' And (asImp=1 OR asExp=1); ";
		  $result = mssql_query($query);
		  if(mssql_num_rows($result) > 0) {
		    $arr = mssql_fetch_array($result);
		    $kodeConsignee = $arr['custRegID'];
		    mssql_free_result($result);
		  }	
        }	
*/
        $kodeBook=str_replace('-', '', $dateIn);
		$kodeBook=$location.substr($kodeBook,0,1).substr($kodeBook,2,6);

		$do=''; 
		$query = "Select NoContainer From containerJournal Where gateIn is Null And NoContainer = '$NoContainer' ";
		$result = mssql_query($query);
		if(mssql_num_rows($result) > 0) {
		  mssql_free_result($result);
		  $do = "Declare @bookInID VarChar(30);
				 Select @bookInID = bookInID From containerJournal Where NoContainer = '$NoContainer' And gateIn Is Null;				 
				 
				 Update containerJournal Set gateIn = '$dateIn', jamIn = '$eventTime'
				  Where NoContainer = '$NoContainer' And bookInID=@bookInID; ";
		}
		else {
		  $query = "Select NoContainer From containerJournal Where gateIn Is Not Null And (gateOut Is Null Or Format(gateOut,'yyyy-MM-dd') != '1900-01-01') 
		                                                       And NoContainer = '$NoContainer' ";
		  $result = mssql_query($query);
		  if(mssql_num_rows($result) <= 0) {
			mssql_free_result($result);  
			$cond=str_replace(' ','',$cond);
			
			if($cond=='AV' || $cond=='') {$ispending='N';}
			else {$ispending='Y';}
			
			$do = "If Not Exists(Select * From containerLog Where ContainerNo = '$NoContainer') Begin
	                 Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
					           Values('$NoContainer', 1, '/', 0, '$contSize', '$contType', '$contHeight', 'STL');
	               End;  
				   
				   Declare @bookInID VarChar(30), @LastIndex Int; 
			       If Not Exists(Select * From logKeyField Where keyFName Like '".$kodeBook.'%'."') Begin
			         Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
				     Set @bookInID = CONCAT('".$kodeBook."','1');			            
			       
				   End Else Begin  
			             Select @LastIndex = lastNumber +1 From logKeyField Where keyFName Like '".$kodeBook.'%'."';
                         Update logKeyField Set lastNumber  =lastNumber +1 Where keyFName Like '".$kodeBook.'%'."';                            
					     Set @bookInID = CONCAT('".$kodeBook."', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex)))); 
			          End;	
                  
				  Insert Into tabBookingHeader(bookID, bookType, blID, principle, consignee, operatorID, SLDFileName) 
			                            Values(@bookInID, 0, @bookInID, '', '', '', '$target'); 					
				  
			      Insert Into containerJournal(bookInID, NoContainer, gateIn, jamIn, Cond, isPending, Remarks, isCleaning, isRepair, workshopID,gateOut, GIPort)
			                            Values(@bookInID, '$NoContainer', '$dateIn', '$eventTime', '$cond', '$ispending', '', 0, 0, '$location','$dateOut','$GIPort'); ";						 
			
		  } 
		}
        if($do != '') {
		  $result = mssql_query($do); 		            
 		  if(!$result) {$err++;}
		}
        else {$err++;}		
      } 
	}
    echo '<script language="javascript">document.getElementById("info").innerHTML="Done";</script>'; 		

    $do="Update containerJournal Set GIPort=Null Where Format(GIPort,'yyyy-MM-dd')='1900-01-01';
	     Update containerJournal Set gateOut=Null Where Format(gateOut,'yyyy-MM-dd')='1900-01-01'; ";
	$rslExec=mssql_query($do);

    mssql_close($dbSQL);		
	unlink($target);
	if($err ==0) {$url = "/e-imp/mnr/?do=dolhW&page=loadhw&success=1";}
	else  {$url = "/e-imp/mnr/?do=dolhW&page=loadhw&reject=".$err;}
    echo "<script type='text/javascript'>location.replace('$url');</script>"; 	
  }  
  else {
    $url = "/e-imp/mnr/?do=dolhW&page=loadhw&error=1";
    echo "<script type='text/javascript'>location.replace('$url');</script>"; 		  
  }
?>