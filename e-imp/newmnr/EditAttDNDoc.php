<script language="php">
  include_once ($_SERVER["DOCUMENT_ROOT"]."e-imp/asset/libs/common.php"); 	
  $dbConn = openDB();  
</script>

<div class="height-40"></div>	
<div id="pageTitle"><strong>EDIT DOCUMENT</strong></div> 
  
<script language="php">
  $defHTML = $_SESSION['defurl'];
	  
  $action = "";
  $invoice = "";
  
  if ($dbConn == "connected"){
    $invoiceNo = base64_decode($_GET['prm']);
    $docNumber = base64_decode($_GET['dcn']);	
    $filtervalue = $_GET['is'];		
	$condition = $_GET['cnd'];
	$filtername = $_GET['filter'];		

    $html  = '';
	
	$html .= '<div class="padding-top-10 w3-row-padding">';
	$html .= ' <div class="w3-half">';	    
	$html .= '  <div class="w3-row-padding">';
	$html .= '    <div id="privateStyleLabel" class="w3-quarter">Invoice Number</div>';
	$html .= '    <div id="privateStyleLabel" class="w3-threequarter"><strong>'.$invoiceNo.'</strong></div>';
	$html .= '  </div>';		
	$html .= ' </div>';	  
	$html .= ' <div class="w3-half"></div>';
	$html .= '</div>';
  
	$html .= '<div class="w3-row-padding">';
	$html .= ' <div class="w3-half">';	    
	$html .= '  <div class="w3-row-padding">';
	$html .= '    <div id="privateStyleLabel" class="w3-quarter">Document Name</div>';
	$html .= '    <div id="privateStyleLabel" class="w3-threequarter"><strong>'.$docNumber.'</strong></div>';
	$html .= '  </div>';
	$html .= ' </div>';	  
	$html .= ' <div class="w3-half"></div>';
	$html .= '</div>';

    echo $html;	
  }	  
  
  if (isset($_POST['whatToDo'])){
    $action = $_POST['whatToDo'];
	$invoice = $_POST['invoice'];
	$document = $_POST['document'];
  }	
        
  if ($action == "sync"){
	if ($dbConn == "connected"){
	  $numchecked = Count($_POST['select-item']); 
		
      if ($numchecked > 0){
		for($i=0; $i<$numchecked; $i++) {	
		  $x = strlen($_POST['select-item'][$i]);		 
		  $j = 0;
		  $temp = "";
				
		  while($j < $x){	
		    if (substr($_POST["select-item"][$i], $j, 1) == "*"){
		      $estimateID = $temp; 
              $temp = "";			 
            } 
			else { 
			  $temp = $temp.substr($_POST["select-item"][$i], $j, 1); 
			}	 		   
            $j++;
		  }			 
		  $billParty = $temp;					
				
		  $sql = "EXEC C_SyncDNDocument @estimateID = '".$estimateID."', 
		                                @billParty = '".$billParty."', 
										@invoiceNo = '".$invoice."'; ";
		  $result = mssql_query($sql);
	    }
      }				
    }			  
  }				
  
  if ($action == "edit" && $dbConn == "connected"){
		
    $counter = COUNT($_POST['select-item']);
</script>
		
      <div id="frmedit" class="w3-modal">
	   <div class="w3-modal-content border-radius-3" style="max-width:550px">
	     <div class="frame-title"><strong>Edit Form</strong></div>
		 <div class="height-10"></div>
         <form id="updateCC" method="post">
		  <input type="hidden" name="whatToDo" value="" />
		  
	      <div class="w3-container">
		    <div class="w3-row-padding">
			  <div id="privateStyleLabel" class="w3-third">Document Name</div>
			  <div class="w3-twothird" style="padding:0!important">
			    <input id="privateStyleInput" type="text" name="document" value="<?php echo $document; ?>" readonly style="width:100%" /> </div>
			</div>
            <div class="height-5"></div>
			
			<div class="w3-row-padding">
			  <div id="privateStyleLabel" class="w3-third">Selected Estimate</div>
			  <div class="w3-twothird" style="padding:0!important">
			    <div style="overflow-x:auto;height:100px">
			      <table>
				    <tr style="background-color:#ddd">
					  <th style="padding:3px">Selected Estimate</th>
					  <th style="padding:3px">Bill Party</th>
					</tr>
				  
				    <script language="php">
 				      $html = '';
				    
					  for ($i=0; $i<$counter; $i++){
                        if (isset($_POST['select-item'][$i])){   
		                  $x = strlen($_POST['select-item'][$i]);		 
		                  $j = 0;
		                  $temp = "";
				
		                  while($j < $x){	
		                    if (substr($_POST['select-item'][$i], $j, 1) == "*"){
		                      $estimateID = $temp; 
                              $temp = "";			 
                            } 
			                else { 
			                  $temp = $temp.substr($_POST['select-item'][$i], $j, 1); 
			                }	 		   
                            $j++;
		                  }			 
		                  $billParty = $temp;							  
				        
						  $html .= '<tr>';
						  $html .= ' <td style="padding:3px!important"><input type="hidden" name="estimateID[]" value="'.$estimateID.'" /> '.$estimateID.'</td>';
						  $html .= ' <td style="padding:3px!important">'.$billParty.'</td>';
						  $html .= '</tr>';
					    }  
				      }	  
                      echo $html;   				  				  				  
				    </script>
				  
				  </table>
				</div>
				
			  </div>  
			</div>
			<div class="height-5"></div>

		    <div class="w3-row-padding">
			  <div id="privateStyleLabel" class="w3-third">Cost Center</div>
			  <div class="w3-twothird" style="padding:0!important">
			    <input id="privateStyleInput" type="text" name="costcenter" maxlength="10" /> </div>
			</div>			
			
		  </div>
		  
  	      <div class="height-20"></div>
		  <div style="border-top:1px solid #ddd"></div>
		    <div class="height-20"></div>
		    <div class="w3-container">    
	          <span onclick="document.getElementById('frmedit').style.display='none'" style="cursor:pointer;padding-right-20;color:#555351">CANCEL</span>
              <button type="submit" style="border:0px!important;outline:none!important;background:none;color:#555351" value="updateCC" onclick="this.form.whatToDo.value = this.value;">UPDATE</button>		   
			</div>  
	     </form>
	     <div class="height-20"></div>
	     
	   </div>
      </div>
	  
<script language="php">	
  }
  
  if ($action == "updateCC" && $dbConn == "connected"){	
	$strResult = "";
	$counter = COUNT($_POST['estimateID']);
	
	for ($i=0; $i<$counter; $i++){
      if (isset($_POST['estimateID'][$i])){   
	    $execResult = "";  
		
        $stmt = mssql_init("C_UpdateCostCenterCollectedEOR"); 
        mssql_bind($stmt, "@userID", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);	  
	    mssql_bind($stmt, "@DocNumber", $_POST['document'], SQLVARCHAR, false, false, 100);
	    mssql_bind($stmt, "@EOR", $_POST['estimateID'][$i], SQLVARCHAR, false, false, 30);
	    mssql_bind($stmt, "@CostCenter", $_POST['costcenter'], SQLVARCHAR, false, false, 10);
        mssql_bind($stmt, "@Result", $execResult, SQLVARCHAR, true, false, 150);	  
	    $result = mssql_execute($stmt);
		
	    $strResult = $strResult."<br>".$execResult;
		floatMessage($strResult);
	  }
    }
  }	  
  
  if ($dbConn == "connected"){
    $html  = '';
		
    $sql = "Select a.estimateID, CONVERT(VARCHAR(10), b.estimateDate, 105) estimateDTTM, b.containerID,
                   BillParty, ISNULL(SUM(c.totalValue), 0) totalBill, b.currencyAs, a.CostCenter
	        from CollectedRepair a left join RepairHeader b on b.estimateID = a.estimateID
		                           left join RepairDetail c on c.estimateID = b.estimateID 
		    where a.invoiceNumber = '$invoiceNo' or a.DocNumber = '$docNumber' or c.invoiceNo like '$invoiceNo'
		    group by a.estimateID, c.isOwner, b.estimateDate, b.containerID, BillParty, b.currencyAs, a.CostCenter
			order by b.estimateDate, a.estimateID; ";
    $result = mssql_query($sql);	
</script>
   
   <form id="attDoc" method="post"> 
    <input type="hidden" name="whatToDo" value="" />
    <input type="hidden" name="invoice" value="<?php echo $invoiceNo; ?>" />
	<input type="hidden" name="document" value="<?php echo $docNumber; ?>" />
	
	<div class="w3-container">
     <div class="padding-top-5" style="overflow-x:auto;height:70vh;background-color:#fff">
	  <table>
	   <thead>
	    <tr style='background-color:#000;color:#fff'>
		  <th><input type="checkbox" class="select-all checkbox" name="select-all" /></th>
		  <th>Estimate#</th>
		  <th>Estimate Date</th>
		  <th>Container#</th>
		  <th>Billing Party</th>
		  <th>Total Value</th>
		  <th>Currency</th>
		  <th>Cost Center</th>
		  <th>Remark</th>
		</tr>
       </thead>
       <tbody>
		
       <script language="php">
         while($arr = mssql_fetch_array($result)){
	       if ($arr["totalBill"] <= 0) { $NA = "BillParty Not Valid"; }
	       else { $NA = ""; }
		  
	       $val_send = $arr["estimateID"]."*".$arr["BillParty"];
		  
           $html .= "<tr>
		              <td> <input type='checkbox'  class='select-item checkbox' name='select-item[]' value=".$val_send." /></td> 
		              <td>".$arr['estimateID']."</td>
		              <td>".$arr['estimateDTTM']."</td>
		              <td>".$arr['containerID']."</td>
		              <td>".$arr['BillParty']."</td>
		              <td>".number_format($arr['totalBill'],2,",",".")."</td>
		              <td>".$arr['currencyAs']."</td>
		  	          <td>".$arr['CostCenter']."</td>
		              <td>".$NA."</td>					  
                    </tr>";		  
         }
         mssql_free_result($result);		
		
         echo $html;
       </script>		
	
       </tbody>  		
	  </table>				 
     </div>
	</div>
	
	<div class="padding-top-20 padding-left-20 padding-bottom-10" style="width:600px">	  
	  
      <script language="php">
	    $html = '';
	    $data = array('src'=>base64_encode("newmnr/logDebetNote.php"),'filter'=>$filtername,'cnd'=>$condition,'is'=>$filtervalue);
		
		$html .= '<button type="submit" class="imp-button-grey-blue" value="sync" onclick="this.form.whatToDo.value = this.value;">Synchronize</button>&nbsp;';
        $html .= '<button type="submit" class="imp-button-grey-blue" value="edit" onclick="this.form.whatToDo.value = this.value;">Edit</button>&nbsp;';		
		$html .= '<a type="submit" class="imp-button-grey-blue" href='.$defHTML.'/e-imp/1?'.http_build_query($data).'>DN Filter</a>';
		  
		echo $html;
	  </script>	  
	</div>
	
   </form>	
   <div class="height-50"></div>

<script language="php">
  }	  
  
  if ($action == "edit" && $dbConn == "connected"){
      echo "<script>document.getElementById('frmedit').style.display = 'block';</script>";   
  }	
</script>	

<script type='text/javascript'>
  $(function(){ 
    $('.select-all').on('click', function () {              
      if ($(this).is(":checked")) {
         $('.select-item').prop('checked', this.checked);                  
      } else {
         $(".select-item").removeAttr('checked');
         $(".select-all").removeAttr('checked');                  
       }
    });
    $('.select-item').on('click', function () {
      if ($('.select-item:checked').length === $('.select-item').length) {
         $(".select-all").prop('checked', true);        
      }
      var s = $(this).is(":checked");
      if (s === false) {
        $(".select-all").removeAttr('checked');               
    }});
  });
</script>