<script language='php'>
  include_once ($_SERVER["DOCUMENT_ROOT"]."imp/prod/e-imp/asset/libs/common.php"); 	
  
  $mlo = $_POST['mlo'];
  $dttm1 = $_POST['activityDTTM1'];
  $dttm2 = $_POST['activityDTTM2'];
  $activity = $_POST['activityType']; 
  $billParty = $_POST['billingParty'];
  $workshop = $_POST['hamparanName'];
  $currency = $_POST['currency'];
    
  if (isset($_POST['whatToDo'])){	  
    $postconfirm = $_POST['whatToDo'];
  } else {
	  $postconfirm = "";
    }  
  
  if ($billParty == "U1" || $billParty == "U2"){ $viewname = "C_WaitingInvoiceRepair_User"; }
  if ($billParty == "O"){ $viewname = "C_WaitingInvoiceRepair_Owner"; }
  if ($billParty == "T"){ $viewname = "C_WaitingInvoiceRepair_ThirdParty"; }
  
  if ($activity == 1) { $activityStr = "RP"; }
  if ($activity == 2) { $activityStr = "CL"; }
  if ($activity == 3) { $activityStr = "ALL"; }  
  
  $dbconn = openDB();
  
  if ($postconfirm == ""){	
    if ($dbconn == "connected"){
      $sql = "Select * from ".$viewname." where (gateIn BETWEEN '$dttm1' and '$dttm2') 
                 and workshopID = '$workshop' and currencyAS = '$currency' ";
	  if ($activityStr != "ALL") { $sql .= "and ActivityType = '$activityStr' "; }
	  if ($mlo != "ALL") { $sql .= "and shortName = '$mlo' "; }
	  $sql .= "order by gateIn, shortName; ";
      $result = mssql_query($sql);
		
      $numrows = mssql_num_rows($result);
      mssql_free_result($result);  
	  
      if ($numrows > 125){
		floatMessage($numrows.' record has found. The amount of data exceeds the maximum query capacity (maximum 125 lines).');  
      } 
	  if ($numrows <= 0){
		floatMessage($numrows.' record has found.');    
	  }
	  if ($numrows > 0 && $numrows <= 125){		  
	    $html  = '<div class="frame w3-container">';
	    $html .= ' <form method="post" id="formConfirmity" action="newmnr/saveDocDN">';
		
		$html .= '  <input type="hidden" name="whatToDo" value="" />';
		$html .= '  <input type="hidden" name="mlo" value="'.$mlo.'" />';
		$html .= '  <input type="hidden" name="activityDTTM1" value="'.$dttm1.'" />';
		$html .= '  <input type="hidden" name="activityDTTM2" value="'.$dttm2.'" />';
		$html .= '  <input type="hidden" name="activityType" value="'.$activity.'" />';
		$html .= '  <input type="hidden" name="billingParty" value="'.$billParty.'" />';
		$html .= '  <input type="hidden" name="hamparanNam" value="'.$workshop.'" />';
		$html .= '  <input type="hidden" name="currency" value="'.$currency.'" />';

	    $html .= '  <div class="padding-top-10 padding-bottom-5" style="border-bottom:1px solid #ddd;font-size:15px;font-weight:600">Confirmity Form</div>';
		
		$html .= '  <div class="height-10"></div>';
		$html .= '  <div class="w3-row-padding">';
		$html .= '   <div id="privateStyleLabel" class="w3-third">Record Found</div>';
		$html .= '   <div id="privateStyleLabel" class="w3-twothird">'.$numrows.' row(s)</div>';
        $html .= '  </div><div class="height-5"></div>';

		$html .= '  <div class="padding-top-10" style="border-bottom:1px solid #ddd;"></div>';
		$html .= '  <div class="height-10"></div>';
        $html .= '  <button type="submit" class="imp-button-grey-blue" value="confirm" onclick="this.form.whatToDo.value = this.value;">Start Create Document</button>';		
		
		$html .= '  <div class="height-10"></div>';
		
		$html .= '  <div style="overflow-x:auto;height:50vh">';
		$html .= '   <table class="w3-striped">';
		$html .= '    <tr style="background:#000!important;color:#fff">';
        $html .= '     <th><input type="checkbox" class="select-all checkbox" name="select-all" checked /></th>';				
		$html .= '     <th>Container #</th>';		
		$html .= '     <th>Estimate #</th>';
		$html .= '     <th>Estimate Date</th>';
		$html .= '     <th>Date In</th>';		
		$html .= '     <th style="text-align:right">Value</th>';		
		$html .= '     <th>Activity</th>';		
		$html .= '     <th>Currency</th>';		
		$html .= '     <th>Shipping Line</th>';		
		$html .= '    </tr>';
		
		$result = mssql_query($sql);		
		while ($data = mssql_fetch_array($result)){
		  $val_send = $data['estimateID'];
		  
 		  $html .= '    <tr>';
		  $html .= '     <td><input type="checkbox"  class="select-item checkbox" name="select-item[]" value="'.$val_send.'" checked /></td>';
		  $html .= '     <td>'.$data['NoContainer'].'</td>';
		  $html .= '     <td>'.$data['estimateID'].'</td>';
		  $html .= '     <td>'.date('Y-m-d', strtotime($data['estimateDate'])).'</td>';
		  $html .= '     <td>'.date('Y-m-d', strtotime($data['gateIn'])).'</td>';
		  $html .= '     <td style="text-align:right">'.number_format($data['TotalEstimate'], 2,",",".").'</td>';	
		  $html .= '     <td>'.$data['ActivityType'].'</td>';	
          $html .= '     <td>'.$data['currencyAS'].'</td>';		
          $html .= '     <td>'.$data['shortName'].'</td>';			  
		  $html .= '    </tr>';			
		}
		mssql_free_result($result);
		
		$html .= '   </table>';
		$html .= '  </div>';		
		$html .= ' </form>';
		
		$html .= ' <div class="height-10"></div>';
		$html .= '</div><div class="height-10"></div>';

        echo $html;		
	  }	  
    }	
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