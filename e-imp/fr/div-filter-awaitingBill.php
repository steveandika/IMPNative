<div class="height-40"></div>	
<div id="pageTitle">AWAITING BILL</div>        
<div class="height-10"></div>

<script language = "php">
  if (newvalidMenuAccess('roleFinance',strtoupper($_SESSION['uid'])) == 1){
</script>
    
	<div class="height-10"></div>	
    <div class="w3-row-padding" style="max-width:1000px;margin:0 auto">
      <div class="w3-third">
       <div class="frame">
	    <div class="frame-title" style="background-color:#ddd"><strong>Filter</strong></div> 
        <div class="height-20"></div>		
		
        <form id="filter-form" method="get">
	      <input type="hidden" name="src" value="<?php echo base64_encode('fr/div-filter-awaitingBill.php'); ?>" />
	  
	      <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Shipping Line</div>
	        <div class="w3-half" style="padding:0px">
		      <select id="privateStyleInput" name="mlo">
		        <option value="ALL"></option> 
	    
   	            <script language="php">
                  $html = '';
				
			      $sql = "Select custRegID, completeName, shortName From m_Customer with (NOLOCK) Where asMLO=1 Order By shortName;";
	              $result = mssql_query($sql);
				
	              while($arr = mssql_fetch_array($result)) {
			   	    if (isset($_GET['mlo'])) {
				      if ($_GET['mlo'] == $arr["shortName"]) { $html .= '<option selected value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>';	}
				      else { $html .= '<option value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>'; }
                    } 
				    else {					
                      $html .= '<option value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>';
				    }  
			      }
	              mssql_free_result($result);			
				
			      echo $html;
			    </script>
			  
		      </select>		  
	        </div>
	      </div>
	      <div class="height-3"></div>

	      <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Hamparan Date In</div>
	        <div class="w3-half" style="padding:0px">
		      <input id="privateStyleInput" type="date" name="activityDTTM1" value="<?php echo $_GET['activityDTTM1']; ?>" required />
		      <input id="privateStyleInput" type="date" name="activityDTTM2" value="<?php echo $_GET['activityDTTM2']; ?>" required />		
		    </div>  
	      </div>
	      <div class="height-3"></div>

	      <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Activity Type</div>
	        <div class="w3-half" style="padding:0px">
	          <select id="privateStyleInput" name="activityType">
		    
			    <script language="php">
			      $html = '';
			  
			      if (isset($_GET['activityType'])){
			        if ($_GET['activityType'] == 1) { $html .= '<option selected value=1>REPAIR&nbsp;</option>'; } 	  
				    else { $html .= '<option value=1>REPAIR&nbsp;</option>'; }
			        if ($_GET['activityType'] == 2) { $html .= '<option selected value=2>CLEANING&nbsp;</option>'; } 	  
				    else { $html .= '<option value=2>CLEANING&nbsp;</option>'; }		
			      }	  
			      else {
	                $html .= '<option value=1>REPAIR&nbsp;</option>';
	                $html .= '<option value=2>CLEANING&nbsp;</option>';  
			      }
              
                  echo $html; 			  
			    </script>
			
              </select>		  		    
		    </div>
          </div>
	      <div class="height-3"></div>

  	      <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Billing Party</div>
	        <div class="w3-half" style="padding:0px">
	          <select id="privateStyleInput" name="billingParty">
		  
		        <script language="php">
			      $html = '';
				  
			      if (isset($_GET['billingParty'])){
			        if ($_GET['billingParty'] == "O") {  $html .= '<option selected value="O">O</option>'; }
				    else {  $html .= '<option value="O">O</option>'; }
			        if ($_GET['billingParty'] == "U1") {  $html .= '<option selected value="U1">U1</option>'; }
				    else {  $html .= '<option value="U1">U1</option>'; }
			        if ($_GET['billingParty'] == "U2") {  $html .= '<option selected value="U2">U2</option>'; }
				    else {  $html .= '<option value="U2">U2</option>'; }
			        if ($_GET['billingParty'] == "T") {  $html .= '<option selected value="T">T</option>'; }
				    else {  $html .= '<option value="T">T</option>'; }				
			      }		
		          else {
  	                $html .= '<option value="O">O</option>';
  	                $html .= '<option value="U1">U1</option>';
  	                $html .= '<option value="U2">U2</option>';
  	                $html .= '<option value="T">T</option>';				  
			      }
			  
		          echo $html;
			    </script>
			
              </select>		  		    
		    </div>
	      </div>
          <div class="height-3"></div>	  
		
          <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Hamparan</div>
	        <div class="w3-half" style="padding:0px">
	          <select id="privateStyleInput" name="hamparanName" style="width:100%">
		        <option value="ALL"></option>
			
  		        <script language="php">
			      $html = '';
			  
                  $sql = "Select * From m_Location with (NOLOCK) Order By locationDesc ";
                  $result = mssql_query($sql);
			  
                  while($arr = mssql_fetch_array($result)){ 
  			        $html .= '<option value="'.$arr[0].'">'.$arr[1].'</option>';
                  }
                  mssql_free_result($result);
			  
			      echo $html;
		        </script>
			
              </select>		  		    
		    </div>
	      </div>
          <div class="height-3"></div>	  

  	      <div class="w3-row-padding">
	        <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Currency Type</div>
	        <div class="w3-half" style="padding:0px">
	          <select id="privateStyleInput" name="currency">
	            <option value="IDR">IDR</option>
	            <option value="USD">USD</option>
              </select>		  
		    </div>
	      </div>	
		
		  <div class="padding-top-20 padding-bottom-10 padding-left-10">
		    <button type="Submit" class="imp-button-grey-blue">Apply</button>
          </div>
        </form>
		
       </div>	
	   <div class="height-10"></div>   
      </div>
	  
      <div class="w3-twothird">
	    <div id="div-result">
	      <script language="php">
	        if(isset($_GET['activityDTTM1']) && isset($_GET['activityDTTM1'])) { include("fr/list-awaitingBill.php"); }
	      </script>
	    </div>
      </div>	  
    </div>
    <div class="height-60"></div>	

<script language="php">
  }
</script>