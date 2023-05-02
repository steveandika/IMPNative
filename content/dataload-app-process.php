<div class="se-pre-con"></div>

<?php  
  if($doc_Name!="") {
    include("asset/libs/upload_reader.php"); 
	
	$uid = $_SESSION["uid"];
	
	$copyName = "log_app/".$doc_Name;
	move_uploaded_file($_FILES["docName"]["tmp_name"], $copyName);	  
	$data = new Spreadsheet_Excel_Reader($copyName, false);
    $baris = $data->rowcount($sheet_index=0);
    
    if ($baris <= 300) {	
	  $err = 0;
	  $success = 0;
	  $rejected = "";
	  $reject_count = 0;
	
	  $upload_dttm = date("Y-m-d h:i:s");

 	  $keyIndex = date("Y-m");	
	  $keyIndex = str_replace("-", "", $keyIndex);
	  $keyIndex = substr($keyIndex,0,1).substr($keyIndex,2,4);
	
	  $keyFName = "APPDATE".$keyIndex;
	  $newIndex = 0;
	
	  $sql = "Select * From logKeyField Where KeyFName='".$keyFName."'";
	  $rsl = mssql_query($sql);
	  if (mssql_num_rows($rsl) > 0) { $newIndex = 1; }
	  mssql_free_result($rsl);
	
	  if ($newIndex ==0) { $sql="Insert Into logKeyField(keyFName, lastNumber) Values('".$keyFName."',".$newIndex."); ";	}
      else { $sql="Update logKeyField Set lastNumber=lastNumber +1 Where keyFName='".$keyFName."'"; } 			
	  $rsl = mssql_query($sql);

	  $sql= "Select lastNumber From logKeyField Where KeyFName='".$keyFName."'";
	  $rsl = mssql_query($sql);
      $row_arr = mssql_fetch_array($rsl);
	  $newIndex = $row_arr["lastNumber"]; 
	  mssql_free_result($rsl);
	
	  $keyIndex = '1'.$keyIndex.(string)$newIndex;
	  $int_keyIndex = (int)$keyIndex;		
	  $totalBaris = $baris-1;
		
	  $sql = "Insert Into LOG_HAMPARAN_INJECT_HEADER(xls_file_name, dttm_upload, lines_number, accepted, log_index, workshop_name, userProfile)
	          Values('$doc_Name', '$upload_dttm', $totalBaris, 0, $int_keyIndex, '$workshop' ,'$uid'); ";
      $rsl = mssql_query($sql);
	
      for ($i=2; $i<=$baris; $i++) {
	    $container = "";
	    $eorNumber = "";
	    $completeRepair = "";
	    $completeCleaning = "";
	    $approvalEstimate = "";
	    $avDate = "";
	    $ticketNumber = "";
	  
        $container_prefix = strtoupper($data->val($i, 1)); 
	    $container_prefix = str_replace(" ", "", $container_prefix);
        $container_nbr = $data->val($i, 2); 
	    $container_nbr = str_replace(" ", "", $container_nbr);
        $container_suffix = $data->val($i, 3);	  
	    $container_suffix = str_replace(" ", "", $container_suffix);
	    $container = $container_prefix.$container_nbr.$container_suffix; 
      
        $eorNumber = strtoupper($data->val($i, 4));
        $completeRepair = $data->val($i, 5);
        $completeCleaning = $data->val($i, 6);
        $approvalEstimate = $data->val($i, 7); 	  
	  
	    $now_date = date('Y-m-d h:i:s');	  	
        $dateIn = date('Y-m-d h:i:s');	  
	    $valid_row = 1;
	  
	    $sql = "Select containerID, estimateID, bookID From RepairHeader with (NOLOCK) Where containerID='$container' And estimateID='$eorNumber'; ";
	    $rsl = mssql_query($sql);
	  //secho $i." ".$sql."<br>";
	    if (mssql_num_rows($rsl) <= 0) {
		  $valid_row = 0;  
		  $remark = "Invalid pairing ".$container."/".$eorNumber;
		  $status = "Rejected";
  	      $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $now_date, $remark, $status);	
		  
		  $reject_count++;						
	    }	  
	    else {
	      if (strpos($completeCleaning, "/") > 0 || strpos($completeRepair, "/") > 0 || strpos($approvalEstimate, "/") > 0) {
            $valid_row = 0;		  
		    $remark = "Invalid Date format";
            $status = "Rejected";		  
		    $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $now_date, $remark, $status);	
		  
		    $reject_count++;						
          }		  
		
		  if ($valid_row == 1) {
		    $arr_row = mssql_fetch_array($rsl);
		    $ticketNumber = $arr_row["bookID"];
		    mssql_free_result($rsl);
		  
		    if ($completeRepair != "") { $dateCR = date("Y-m-d", strtotime($completeRepair)); }
		    if ($completeCleaning != "") { $dateCC = date("Y-m-d", strtotime($completeCleaning)); }
		  
		    if ($completeRepair != "" && $completeCleaning == "") { $avDate = $completeRepair; }
		    if ($completeRepair == "" && $completeCleaning != "") { $avDate = $completeCleaning; }
		    if ($completeRepair != "" && $completeCleaning != "") { 
		      if ($dateCR > $dateCC) { $avDate = $completeRepair; }
			  else if ($dateCC > $dateCR) { $avDate = $completeCleaning; }
			  else if ($dateCC == $dateCR){ $avDate = $completeRepair; }
		    }
		  
		    $sql = "";
		  
		    if ($completeRepair != "") {
		  	  $sql .= "Update containerJournal Set CRDate='$completeRepair', AVCond='$completeRepair', Cond='AV', isPending='N' where bookInID='$ticketNumber'; ";
              $sql .= "Update RepairHeader Set FinishRepair='$completeRepair', LAST_UPDATE='$upload_dttm' where bookID='$ticketNumber'; "; 			
		    }
		  
		    if ($completeCleaning != "") {
			  $sql = $sql."Update containerJournal Set CCleaning='$completeCleaning', last_update='$upload_dttm' where bookInID='$ticketNumber'; ";
              $sql = $sql."Update CleaningHeader Set cleaningDate='$completeCleaning' where bookID='$ticketNumber'; "; 						  
		    }
		  
		  /*
		  if ($avDate != "") { 
		    $sql .= "Update containerJournal Set AVCond='$avDate', last_update='$upload_dttm' where bookInID='$ticketNumber'; "; 
		  }	  	
		  */
		  
		    if ($approvalEstimate != "") { 
		      $sql .= "Update RepairHeader Set statusEstimate='APPROVE', tanggalApprove='$approvalEstimate', LAST_UPDATE='$upload_dttm' where bookID='$ticketNumber'; "; 
		    }
		
		    if ($sql != "") { 
		   // echo $sql;
		      $rsl = mssql_query($sql); 
		    }
		  
		    $remark = "Estimate Number: ".$eorNumber." updated ";
            $status = "Inserted";		  
		    $result = new_log_lhw($int_keyIndex, $container, $sizeTypeHeight, $dateIn, $remark, $status);	
		  }	
	    }
	  }		

		
	$max_gateIn = "";
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
	}	
	mssql_free_result($rsl);	
?>

<div class="height-20"></div>
<div style="width: 100%;border-top:1px solid #ecf0f1;text-align: center;position: absolute;left: 15%;width: 575px;">	  
  <strong>Upload Summary</strong>
</div>
<div class="height-10"></div>

<div id="current_log" style="width: 100%;padding:5px;border:1px solid #3498db;position: absolute;left: 15%;width: 575px;">  
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
  
  <table class="w3-table-all w3-bordered">
    <thead>
     <tr>
	  <th style="vertical-align: middle">#</th>
	  <th style="vertical-align: middle">CONTAINER #</th>
	  <th style="vertical-align: middle">S/T/H</th>
	  <th style="vertical-align: middle">STATUS</th>
	  <th style="vertical-align: middle">DESCRIPTION</th>
	 </tr>
	</thead>
	<tbody>

<?php
    $print_line="";
	$indexLine=0;
	
    $sql="Select * FROM LOG_HAMPARAN_INJECT_DETAIL Where log_index= $int_keyIndex; ";
	$rsl=mssql_query($sql);
	while ($row_arr=mssql_fetch_array($rsl)) {
	  $indexLine++;
	  
	  $print_line = $print_line."<tr>";
	  $print_line = $print_line."  <td>".$indexLine."</td>";
	  $print_line = $print_line."  <td>".$row_arr["CONTAINER_NBR"]."</td>";
	  $print_line = $print_line."  <td>".$row_arr["SIZE_TYPE_HEIGHT"]."</td>";
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
	else {
	    echo '<script languange="text/javascript">
		       alert("Failed to process request. Row count more than 300 rows.");
		      </script>';
	}	
  }
?>