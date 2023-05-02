<?php
  if (isset($_FILES["docName"])){
    include_once($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/upload_reader.php"); 
	
	$uid = strtoupper($_SESSION["uid"]);
	$doc_name = $doc_Name=basename($_FILES["docName"]["name"]);
	
	$copyName = "log/".$doc_Name;
	move_uploaded_file($_FILES["docName"]["tmp_name"], $copyName);	  
	$data = new Spreadsheet_Excel_Reader($copyName, false);
    $baris = $data->rowcount($sheet_index=0);	
?>

    <div class="frame border-radius-3">
	  <div class="frame-title">Upload Summary</div>
	  
      <div class="height-10"></div>
	  <div id="resultSummary" style="width:100%">
	    <div class="w3-row-padding" style="border-bottom:1px solid #f4d03f;padding:5px">
	      <div class="w3-third">Total Row</div>
	      <div class="w3-third w3-text-orange"><strong><?php echo $baris ?></strong></div>
	      <div class="w3-third"><?php if ($baris > 500) { echo ''; }
	                                  else { echo 'Status: OK'; } ?></div>
	    </div>
	    <div class="w3-row-padding" style="border-bottom:1px solid #f4d03f;padding:5px">
	      <div class="w3-third">Progress</div>
	      <div class="w3-third w3-text-orange" id="progress"></div>
	      <div class="w3-third"></div>
	    </div>
		
		<script language="php">
		  $Failed = 0;
		  $Updated = 0;
		  $validHeader = 1;
		  
		  if ($baris <= 500){
			$coltitle = strtoupper($data->val(1, 1));
            if (strtoupper($coltitle != "INVOICE_NUMBER")) { $validHeader = 0; }			
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 2));	
			  if ($coltitle != "INVOICE_DATE_YYYY-MM-DD") { $validHeader = 0; }
			}	
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 3));	
			  if ($coltitle != "EOR_NUMBER") { $validHeader = 0; }
			}	
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 4));	
			  if ($coltitle != "DOCUMENT_NUMBER") { $validHeader = 0; }
			}	
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 5));	
			  if ($coltitle != "BILLING__PARTY_O_U1_U2_T") { $validHeader = 0; }
			}	
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 6));	
			  if ($coltitle != "VOID_(Y/N)") { $validHeader = 0; }
			}	
			if ($validHeader == 0) {
			  $coltitle = strtoupper($data->val(1, 7));	
			  if ($coltitle != "COSTCENTER") { $validHeader = 0; }
			}				
		  }	  
		  
		  if ($baris <= 500 && $validHeader == 1){
            include ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php"); 		  
		    $connDB = openDB();
		  
		    for ($i=2; $i<=$baris; $i++){
		      echo '<script language="javascript">document.getElementById("progress").innerHTML="'.$i.' from '.$baris.'";</script>';
			
			  $estimateNo = "";
			  $invoiceNo = "";
			  $invoiceDate = "";
			  $docNo = "";
			  $billParty = "";
			  $isVoid = "";
			
			  $invoiceNo = strtoupper($data->val($i, 1));  
			  $invoiceDate = $data->val($i, 2);  
			  $estimateNo = strtoupper($data->val($i, 3));  
			  $docNo = strtoupper($data->val($i, 4));  
			  $billParty = strtoupper($data->val($i, 5));  
			  $isVoid = strtoupper($data->val($i, 6));
			
			  if ($connDB == "connected"){
			    $qry = "EXEC C_InvoiceLog @InvoiceNumber = '".$invoiceNo."',
                                          @EstimateNo = '".$estimateNo."',
                                          @InvoiceDate = '".$invoiceDate."',
                                          @DocNumber = '".$docNo."',
                                          @BillParty = '".$billParty."',
                                          @Void = '".$isVoid."',
                                          @SesUserID = '".$uid."'; ";
			    $result = mssql_query($qry);
			    if (!$result) { $Failed++; }
			    else { $Updated++; }
			  } else { $Failed++; }  
		    }	
		  
		    echo '<script language="javascript">document.getElementById("progress").innerHTML="Finished";</script>';
		  } else {
			  echo '<script language="javascript">document.getElementById("progress").innerHTML="Stopped, allow maximum row: 500 or invalid header";</script>';
		    }			  
		</script>
		
	    <div class="w3-row-padding" style="border-bottom:1px solid #f4d03f;padding:5px">
	      <div class="w3-third">Updated</div>
	      <div class="w3-third w3-text-orange"><strong><?php echo $Updated ?></strong></div>
	      <div class="w3-third"></div>
	    </div>
	    <div class="w3-row-padding" style="border-bottom:1px solid #f4d03f;padding:5px">
	      <div class="w3-third">Failed</div>
	      <div class="w3-third w3-text-orange"><strong><?php echo $Failed ?></strong></div>
	      <div class="w3-third"></div>
	    </div>
		
	  </div>
	  <div class="height-10"></div>
    </div>
	
<script language="php">	
    unlink($copyName);
  }
</script>