<div class="se-pre-con"></div>

<?php  
  session_start();  
  
  if($doc_Name!="") {
    include("asset/libs/upload_reader.php"); 
	
	$uid=$_SESSION["uid"];
	
	$copyName="log/".$doc_Name;
	move_uploaded_file($_FILES["docName"]["tmp_name"], $copyName);	  
	$data = new Spreadsheet_Excel_Reader($copyName, false);
    $baris = $data->rowcount($sheet_index=0);
    	
	$kodeBook_Before = "";
	$principleName_tmp = "";
	$contHeight = "STD";
	$contType = "GP";
	
	$err = 0;
	$success = 0;
	$rejected = "";
	$reject_count = 0;
	
	$upload_dttm = date("Y-m-d h:i:s");

	$keyIndex = date("Y-m");	
	$keyIndex = str_replace("-", "", $keyIndex);
	$keyIndex = substr($keyIndex,0,1).substr($keyIndex,2,4);
	
	$keyFName = "LHW".$keyIndex;
	$newIndex = 0;
	
	$sql = "Select Count(1) last_Number From logKeyField Where KeyFName='".$keyFName."'";
	$rsl = mssql_query($sql);
    $row_arr = mssql_fetch_array($rsl);
	if ($row_arr["last_Number"] ==0) { $newIndex = 1; }
	mssql_free_result($rsl);
	
	if ($newIndex ==1) { $sql = "Insert Into logKeyField(keyFName, lastNumber) Values('".$keyFName."',".$newIndex."); ";	}
    else { $sql = "Update logKeyField Set lastNumber=lastNumber +1 Where keyFName='".$keyFName."'"; } 			
	$rsl = mssql_query($sql);

	$sql = "Select lastNumber From logKeyField Where KeyFName='".$keyFName."'";
	$rsl = mssql_query($sql);
    $row_arr = mssql_fetch_array($rsl);
	$newIndex = $row_arr["lastNumber"]; 
	mssql_free_result($rsl);
	
	$keyIndex = $keyIndex.(string)$newIndex;
	$int_keyIndex = (int)$keyIndex;		
		
	$sql = "Insert Into LOG_HAMPARAN_INJECT_HEADER(xls_file_name, dttm_upload, lines_number, accepted, log_index, workshop_name, userProfile)
	        Values('$doc_Name', '$upload_dttm', $baris -1, 0, $int_keyIndex, '$workshop' ,'$uid'); ";
	$rsl = mssql_query($sql);
	
    for ($i=2; $i<=$baris; $i++) {
	  $container = "";
	  $contSize = "";
	  $sizeTypeHeight = "";
	 // $dateIn = "";
	  $dateport = "";
	  $cctype = "";
	  
      $container_prefix = strtoupper($data->val($i, 1)); 
	  $container_prefix = str_replace(" ", "", $container_prefix);
      $container_nbr = $data->val($i, 2); 
	  $container_nbr = str_replace(" ", "", $container_nbr);
      $container_suffix = $data->val($i, 3);	  
	  $container_suffix = str_replace(" ", "", $container_suffix);
	  $container = $container_prefix.$container_nbr.$container_suffix; 
	  
	  $contSize = $data->val($i, 4); 
	  $contSize = str_replace(" ","", $contSize);
	  $sizeTypeHeight = $contSize." ".$contHeight;
	  
    //  $dateIn = $data->val($i, 5); 
	//  $dateIn = str_replace(" ","",$dateIn);	  
      $dateport = $data->val($i, 6); 
	  $dateport = str_replace(" ","",$dateport);
	  $cctype = $data->val($i, 10); 
	  $cctype = str_replace(" ", "", $cctype);	  
	 		
	  $now_date = date('Y-m-d h:i:s');
	  	  
	  $valid_row=1;
	  if (strlen($container)==11) {
	    $isOK = validUnitDigit($container);
        if($isOK != "OK") {
		  $remark = "Invalid container digit";
          $status = "Rejected";		  
		  $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $now_date, $remark, $status);	
		  
		  $reject_count++;				
		  $valid_row = 0;
		}  
		else { $remark="";}
		
		$isOK="OK";
	  } 
	  
	  if (strpos($dateIn, "/") > 0) {
		$remark = "Invalid Date format";
        $status = "Rejected";		  
		$resul = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $now_date, $remark, $status);	
		  
		$reject_count++;				
		$valid_row = 0;		  
      }

	  if ($contSize == "") {
		$remark = "InComplete information of ContSize";
        $status = "Rejected";		  
		$result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $now_date, $remark, $status);	
		  
		$reject_count++;				
		$valid_row = 0;		  
      }
	  
	  if ($valid_row == 1 && $contSize != ""  && strpos($dateIn, "/") <= 0 && $dateIn != "") {    	    
        $do = "If Not Exists(Select containerNo From containerLog Where containerNo = '$container') Begin
	             Insert Into containerLog(containerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
		  	  	  	               Values('$container', 1, '/', 0, '$contSize', '$contType', '$contHeight', 'STL');
	   	       End; ";
		$rsl = mssql_query($do);		 
		
  	    $kodeBook = "";		  
		$numrows = 0;
		
		//And gateIn = '$dateIn' 
		$sql = "Update containerJournal set gateOut=gateIn where NoContainer='$container' And gateIn < '$dateIn' And
		        gateOut Is Null and bookInID Not Like '%BATAL' And bookInID Not Like '%*'; "; // and UPLOADDTTM IS NULL; ";
		$rsl = mssql_query($sql);
		
        $sql = "Select COUNT(1) jumlahBrs From containerJournal with (NOLOCK) 
		        Where NoContainer='$container' And gateIn <= '$dateIn' And
				gateOut Is Null And bookInID Not Like '%BATAL' And bookInID Not Like '%*'; "; 
        $rsl = mssql_query($sql);
		if (mssql_num_rows($rsl) > 0) {
		  $row_arr = mssql_fetch_array($rsl);
          $numrows = $row_arr["jumlahBrs"];		  
		}
 	    mssql_free_result($rsl);
		
        if ($numrows <= 0) { 
          $dateIn_converted = str_replace("-", "", $dateIn); 
		  $kodeBook = substr($dateIn_converted,0,1).substr($dateIn_converted,2,6);		  
		  //$kodeBook = $workshop.substr($kodeBook,0,1).substr($kodeBook,2,6);			  
  			  
		  $sql="Declare @bookInID VarChar(30), @LastIndex_ Int, @strLastIndex VarChar(20); 

	            If not exists (Select keyFName from logKeyField with (NOLOCK) where keyFName = '".$kodeBook."') 
	            Begin
	              Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."', 1);
	              Set @bookInID = Concat('".$kodeBook."', '00001');
	            End else 
	                Begin
		              Update logKeyField Set lastNumber = lastNumber +1 where keyFName = '".$kodeBook."';   
		              Set @LastIndex_ = (Select lastNumber from logKeyField where keyFName = '".$kodeBook."'); 
		              Set @strLastIndex = RTRIM(LTRIM(CONVERT(VARCHAR(20),@LastIndex_)));
		  
		              If Len(@strLastIndex)=1 Set @strLastIndex=CONCAT('0000', @strLastIndex)
		              Else If Len(@strLastIndex)=2 Set @strLastIndex=CONCAT('000', @strLastIndex)
		              Else If Len(@strLastIndex)=3 Set @strLastIndex=CONCAT('00', @strLastIndex)
		              Else If Len(@strLastIndex)=4 Set @strLastIndex=CONCAT('0', @strLastIndex);

		              Set @bookInID = Concat('".$kodeBook."', @strLastIndex); 
		            End;		 
				
			    Insert Into tabBookingHeader(bookID, bookType, blID, principle, consignee, operatorID) 
			                          Values(@bookInID, 0, @bookInID, '', '', ''); 
										 
			    Select bookID From tabBookingHeader Where bookID=@bookInID; "; 									  
		  $rsl=mssql_query($sql);
		  
		  if(!$rsl) { $err++; }
		  else { 
		    $row_arr = mssql_fetch_array($rsl);
		    $kodeBook = $row_arr["bookID"];
			
		    mssql_free_result($rsl);			  
		  }

          $sql = "DECLARE @UploadDate DATETIME;
		          SET @UploadDate = CONVERT(VARCHAR(10), GETDATE(), 23);
				  
		          Insert Into containerJournal(bookInID, NoContainer, gateIn, Cond, isPending, workshopID, GIPort, cleaningType,
		          rec_status, insert_date, userID, last_update, Remarks, UPLOADDTTM)
		          Values('$kodeBook', '$container', '$dateIn', 'AV', 'N', '$workshop', '$dateport', '$cctype',
				  1, '$upload_dttm', '$uid', '$upload_dttm', '$remark', @UploadDate); ";						 			
		  $rsl = mssql_query($sql);		  
		  
		  if(!$rsl) { $err++; }				
		  else { 
		    $success++;
		    
			$remark = "Reg. Number: ".$kodeBook;
            $status = "Inserted";		  
		    $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $dateIn, $remark, $status);				
            
			$sql = "";			
		    if ($cctype != "") { $sql=$sql."Update containerJournal SET cleaningType='$cctype' Where bookInID='$kodeBook'; "; }
		  
		    if ($sql !="") { $rsl = mssql_query($sql); }  
		  }
		} 
        else {
		  $remark = "Still exist";
          $status = "Passed";		  
		  $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $dateIn, $remark, $status);				
        }			
	
	  } 
	}  
	
    $sql="Update containerJournal Set GIPort=Null Where GIPort='1900-01-01';
	      Update containerJournal Set CRDate=Null Where CRDate='1900-01-01'; 
		  Update containerJournal Set CCleaning=Null Where CCleaning='1900-01-01'; 
	      Update containerJournal Set gateOut=Null Where gateOut='1900-01-01'; ";
	$rsl = mssql_query($sql);
	
	$sql = "DECLARE @UploadDate DATETIME;
		    SET @UploadDate = CONVERT(VARCHAR(10), GETDATE(), 23);
			
			Update containerJournal set gateOut = @UploadDate, last_update = GETDATE(), uid = '$uid'
			 where UPLOADDTTM != @UploadDate and UPLOADDTTM IS NOT NULL; ";
	$rsl = mssql_query($sql);		 
	
	if ($uid !="ROOT") {	  
	  $remark= " Upload Hamparan Workshop";
		
	  $sql = "Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$uid', '$upload_dttm', '$remark');";
      $rsl_exec=mssql_query($sql); 	
	}  
		
	$max_gateIn ="";
	$sql = "Select CONVERT(varchar, MAX(DTTM_IN_WORKSHOP), 23) dateInMax From LOG_HAMPARAN_INJECT_DETAIL Where log_index=$int_keyIndex; ";
	$rsl = mssql_query($sql);
	if (mssql_num_rows($rsl) > 0) {
	  $row_arr = mssql_fetch_array($rsl);
	  $max_gateIn = $row_arr["dateInMax"];
	}
    mssql_free_result($rsl);	
	
	if ($max_gateIn != "") {	
	  $sql = "Update containerJournal Set gateOut='$max_gateIn'
	          Where workshopID = '$workshop' And
	                noContainer Not In (Select container_nbr noContainer From LOG_HAMPARAN_INJECT_DETAIL Where log_index=$int_keyIndex) And
	                gateOut Is Null And gateIn <= '$max_gateIn'; ";
	  $rsl = mssql_query($sql);
	}	

    $xlsFileName = "";
	$dtm_upload = "";
	$workshop = "";
	$total_lines = 0;
	$accepted = 0;

    $sql = "Select * From LOG_HAMPARAN_INJECT_HEADER Where log_index = $int_keyIndex; ";
	$rsl = mssql_query($sql);
	if (mssql_num_rows($rsl) > 0) {
	  $row_arr = mssql_fetch_array($rsl);
      
      $xlsFileName = $row_arr["XLS_FILE_NAME"];
      $dtm_upload = $row_arr["DTTM_UPLOAD"];
      $total_lines = $row_arr["LINES_NUMBER"];
      $accepted = $row_arr["ACCEPTED"];
      $workshop = $row_arr["WORKSHOP_NAME"];	  
	}	
	mssql_free_result($rsl);	
?>

<div style="width: 100%;border-top:1px solid #ecf0f1;text-align: center">	  
  <strong>Upload Summary</strong>
</div>
<div class="height-10"></div>

<div id="current_log" style="width: 100%;padding:5px;border:1px solid #3498db">  
  <label><strong>Attached File Name :</strong></label>
  <input type="text" class="w3-input w3-border" readonly value="<?php echo $xlsFileName; ?>" />
  <div class="height-10"></div>
  <label><strong>Upload Date Time :</strong></label>
  <input type="text" class="w3-input w3-border" readonly value="<?php echo $dtm_upload; ?>" />
  <div class="height-10"></div>
  <label><strong>Lines :</strong></label>
  <input type="text" class="w3-input w3-border" readonly value="<?php echo $total_lines; ?>" />
  <div class="height-10"></div>
  <label><strong>Accepted Lines :</strong></label>
  <input type="text" class="w3-input w3-border" readonly value="<?php echo $accepted; ?>" />
  <div class="height-10"></div>
  <label><strong>Workshop ID/Name :</strong></label>
  <input type="text" class="w3-input w3-border" readonly value="<?php echo $workshop; ?>" />
  <div class="height-10"></div>
  
  <table class="w3-table-all w3-bordered">
    <thead>
     <tr>
	  <th style="vertical-align: middle">#</th>
	  <th style="vertical-align: middle">CONTAINER #</th>
	  <th style="vertical-align: middle">S/T/H</th>
	  <th style="vertical-align: middle">WORKSHOP IN</th>
	  <th style="vertical-align: middle">STATUS</th>
	  <th style="vertical-align: middle">DESCRIPTION</th>
	 </tr>
	</thead>
	<tbody>

<?php
    $print_line="";
	$indexLine=0;
	
    $sql = "Select *, convert(varchar, DTTM_IN_WORKSHOP, 23) inWorkshop FROM LOG_HAMPARAN_INJECT_DETAIL Where log_index= $int_keyIndex; ";
	$rsl = mssql_query($sql);
	while ($row_arr=mssql_fetch_array($rsl)) {
	  $indexLine++;
	  
	  $print_line = $print_line."<tr>";
	  $print_line = $print_line."  <td>".$indexLine."</td>";
	  $print_line = $print_line."  <td>".$row_arr["CONTAINER_NBR"]."</td>";
	  $print_line = $print_line."  <td>".$row_arr["SIZE_TYPE_HEIGHT"]."</td>";
	  $print_line = $print_line."  <td>".$row_arr["inWorkshop"]."</td>";
	  $print_line = $print_line."  <td>".$row_arr["RECORD_STATE"]."</td>";
	  $print_line = $print_line."  <td>".$row_arr["DESCRIPTION"]."</td>";	    
      $print_line = $print_line."</tr>";	  
	}	
	
	echo $print_line;
?>
		
	</tbody>
  </table>
</div>
<div class="height-5"></div>

<?php
  }
?>