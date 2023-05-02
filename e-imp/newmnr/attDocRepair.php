	<div class="height-5"></div>
    <div id="filterSummaryRepair" class="frame border-radius-3" style="height:90vh !important">
     <div id="pageTitle">
      #Workshop Management > <strong>Filter</strong>
     </div>        
	  
	 <div class="height-10"></div>
	 <div class="w3-row-padding">
	  <div class="w3-half" style="width: 350px!important">
		
	   <form id="filterActivity" method="post">
		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Shipping Line Name</div>
	      <div class="w3-half" style="padding:0px">
		    <select id="privateStyleInput" name="mlo" required>
			  <option value="ALL"></option>
		      <script language="php">
                $cmd = "Select custRegID, completeName, shortName From m_Customer with (NOLOCK) Where asMLO=1 Order By shortName;";
	            $result = mssql_query($cmd);
	            while($arr = mssql_fetch_array($result)) {
                  echo '<option value="'.$arr["shortName"].'">'.$arr["shortName"].'&nbsp;</option>';
			    }
	            mssql_free_result($result);			
			  </script>
		    </select>
		    <div class="height-3"></div>
		  </div>
	    </div>

		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Hamparan Date In*</div>
	      <div class="w3-half" style="padding:0px">
		    <input id="privateStyleInput" type="date" name="activityDTTM1" required />
		    <input id="privateStyleInput" type="date" name="activityDTTM2" required />
		    <div class="height-3"></div>
		  </div>  
	    </div>

		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Activity Type*</div>
	      <div class="w3-half" style="padding:0px">
	        <select id="privateStyleInput" name="activityType">
	          <option value=1>REPAIR&nbsp;</option>
	          <option value=2>CLEANING&nbsp;</option>
			  <option value=3>ALL&nbsp;</option>
            </select>		  
		    <div class="height-3"></div>
		  </div>
	    </div>

		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Billing Party*</div>
	      <div class="w3-quarter" style="padding:0px">
	        <select id="privateStyleInput" name="billingParty">
	          <option value="O">O</option>
	          <option value="U1">U1</option>
			  <option value="U2">U2</option>
			  <option value="T">T</option>
            </select>		  
		    <div class="height-3"></div>
		  </div>
	    </div>		
		
		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Hamparan Name/Location*</div>
	      <div class="w3-half" style="padding:0px">
	        <select id="privateStyleInput" name="hamparanName">
		      <script language="php">
                $cmd = "Select * From m_Location with (NOLOCK) Order By locationDesc ";
                $result = mssql_query($cmd);
                while($arr = mssql_fetch_array($result)){ 
  			      echo '<option value="'.$arr[0].'">'.$arr[1].'</option>';
                }
                mssql_free_result($result);
		      </script>
            </select>		  
		    <div class="height-3"></div>
		  </div>
	    </div>	

		<div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half" style="font-weight:500">Currency Type*</div>
	      <div class="w3-half" style="padding:0px">
	        <select id="privateStyleInput" name="currency">
	          <option value="IDR">IDR</option>
	          <option value="USD">USD</option>
            </select>		  
		    <div class="height-3"></div>
		  </div>
	    </div>	
		
		<div class="height-20"></div>
		<div id="ButtonNavigation" class="border-radius-3" style="width:100%;background-color: #f4f6f7">
		  <div class=" padding-top-5 padding-bottom-5 padding-left-5">
		  <button type="submit" class="w3-button w3-blue-grey">Apply</button>
		  </div>
		</div>
	   </form>
	   
	  </div>
	  <div class="w3-half">
	    <div id="loader-icon" style="display:none">..Processing request</div>
	    <div id="div-result"></div>
	  </div>

	 </div>  
    </div>
	
	<script type="text/javascript">
      $(document).ready(function(){  
        $("#filterActivity").submit(function(event){
          event.preventDefault();
	      $("#div-result").hide(); 		
	      $("#loader-icon").show();
          var formValues = $(this).serialize();
          $.post("newmnr/selectedEstimate.php", formValues, function(data){ 
	        $("#loader-icon").hide();
	        $("#div-result").html(data);
            $("#div-result").show(); 		 		
	      });
        });
      }); 
    </script> 	