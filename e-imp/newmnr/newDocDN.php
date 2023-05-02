<div class="height-40"></div>	
<div id="pageTitle">CREATE ATTACHMENT</div>        
<div class="height-10"></div>

<script language="php">
  if (newvalidMenuAccess('roleFinance',strtoupper($_SESSION['uid'])) == 1){
</script>
	  
    <div class="w3-container" style="max-width:800px;margin:0 auto">
      <div class="frame" >
	    <div class="frame-title" style="background-color:#ddd"><strong>Filter</strong></div> 
	    <div class="height-20"></div>	
        <div class="w3-container">
	
          <form id="filterActivity" method="post">
	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Shipping Line</div>
	          <div class="w3-twothird" style="padding:0px">
		       <select id="privateStyleInput" name="mlo" required>
	    
   	             <script language="php">
                   $html = '';
				
			       $cmd = "Select custRegID, completeName, shortName From m_Customer with (NOLOCK) Where asMLO=1 Order By shortName;";
	               $result = mssql_query($cmd);
				
	               while($arr = mssql_fetch_array($result)) {
                     $html .= '<option value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>';
			       }
	               mssql_free_result($result);			
				
			       echo $html;
			     </script>
			  
		       </select>		  
	          </div>
	        </div>
	        <div class="height-3"></div>

	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Hamparan In</div>
	          <div class="w3-twothird" style="padding:0px">
		        <input id="privateStyleInput" type="date" name="activityDTTM1" required />
		        <input id="privateStyleInput" type="date" name="activityDTTM2" required />		
		      </div>  
	        </div>
	        <div class="height-3"></div>

	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third" >Activity Type</div>
	          <div class="w3-twothird" style="padding:0px">
	            <select id="privateStyleInput" name="activityType">
	             <option value=1>REPAIR&nbsp;</option>
	             <option value=2>CLEANING&nbsp;</option>
			     <option value=3>ALL&nbsp;</option>
                </select>		  		    
		      </div>
            </div>
	        <div class="height-3"></div>

  	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Billing Party</div>
	          <div class="w3-twothird" style="padding:0px">
	            <select id="privateStyleInput" name="billingParty">
	             <option value="O">O</option>
	             <option value="U1">U1</option>
			     <option value="U2">U2</option>
			     <option value="T">T</option>
                </select>		  		    
		      </div>
	        </div>
            <div class="height-3"></div>	  
		
            <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Hamparan</div>
	          <div class="w3-twothird" style="padding:0px">
	            <select id="privateStyleInput" name="hamparanName">
		  
  		          <script language="php">
			        $html = '';
			  
                    $cmd = "Select * From m_Location with (NOLOCK) Order By locationDesc ";
                    $result = mssql_query($cmd);
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
	          <div id="privateStyleLabel" class="w3-third">Currency Type</div>
	          <div class="w3-twothird" style="padding:0px">
	           <select id="privateStyleInput" name="currency">
	             <option value="IDR">IDR</option>
	             <option value="USD">USD</option>
               </select>		  
		      </div>
	        </div>	
		
  	        <div class="height-20"></div>
	        <div class="padding-top-5 padding-bottom-20 padding-left-5">
	          <button type="submit" class="imp-button-grey-blue">Apply</button>
		      <div id="onprogress" style="padding:6px 0px;float:right;display:none">.. Gathering information&nbsp;</div>
	        </div>
          </form>
        </div>
    
      </div>
      <div class="height-10"></div>   
  
      <div id="div-result"></div>
      <div class="height-10"></div>
    </div>  

<script language="php">
  }
</script>	
	
<script type="text/javascript">
  $(document).ready(function(){  
    $("#filterActivity").submit(function(event){
      event.preventDefault();
	  $("#div-result").hide(); 		
	  $("#onprogress").show();
      var formValues = $(this).serialize();
      $.post("newmnr/selectedEstimate.php", formValues, function(data){ 
	    $("#onprogress").hide();
	    $("#div-result").html(data);
        $("#div-result").show(); 		 		
	  });
    });
  }); 
</script> 	