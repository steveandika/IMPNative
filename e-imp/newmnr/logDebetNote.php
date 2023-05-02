<div class="height-40"></div>	
<div id="pageTitle">DEBET NOTE LOG</div> 
<div class="height-20"></div>	 

<script language="php">
  if (newvalidMenuAccess('roleFinance',strtoupper($_SESSION['uid'])) == 1){
    $filter = "";
    $condition = "";
    $value = "";
  
    if (isset($_GET['filter'])) { $filter = $_GET['filter']; }
    if (isset($_GET['cnd'])) { $condition = $_GET['cnd']; }
    if (isset($_GET['is'])) { $value = $_GET['is']; }  
</script>

    <div class="w3-row-padding" style="max-width:1000px!important;margin:0 auto">
      <div class="w3-third">
        <div class="frame">
	      <div class="frame-title" style="background-color:#ddd"><strong>Filter</strong></div> 
	      <div class="height-20"></div>
	 
	      <form id="filter" method="get" action="1">
	        <input type="hidden" name="src" value=<?php echo base64_encode('newmnr/logDebetNote.php'); ?> />
	        
			<div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Filter By</div>
	          <div class="w3-twothird" style="padding:0px">
	            <select id="privateStyleInput" name="filter" style="width:100%">
		    
			    <script language="php">
			      $html = "";
			  
			      if ($filter == "invoiceNumber"){
			        $html .= '<option selected value="invoiceNumber">Invoice Number</option>'; 
			   	    $html .= '<option value="DocNumber">Document Number</option>'; 
			      }
			      if ($filter == "DocNumber"){
			        $html .= '<option selected value="DocNumber">Document Name</option>'; 
				    $html .= '<option value="invoiceNumber">Invoice Number</option>'; 
			      }
			      if ($filter == ""){
			        $html .= '<option selected value="DocNumber">Document Name</option>'; 
				    $html .= '<option value="invoiceNumber">Invoice Number</option>'; 
			      }
			  
			      echo $html;
			    </script>
			
		        </select>
	          </div>
	        </div>
	        <div class="height-5"></div>
			
	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Condition</div>
	          <div class="w3-twothird" style="padding:0px">
	            <select id="privateStyleInput" name="cnd" style="width:100%">
		  
			    <script language="php">
			      $html = "";
			  
			      if ($condition == "LIKE"){ 
			        $html .= '<option selected value="LIKE">LIKE</option>'; 
				    $html .= '<option value="EQUAL">EQUAL</option>'; 
			      }
			      if ($condition == "EQUAL"){ 
			        $html .= '<option selected value="EQUAL">EQUAL</option>'; 
				    $html .= '<option value="LIKE">LIKE</option>'; 
			      }
			      if ($condition == ""){ 
			        $html .= '<option value="EQUAL">EQUAL</option>'; 
				    $html .= '<option value="LIKE">LIKE</option>'; 
			      }
				  
			  
			      echo $html;
			    </script>
			
		        </select>		
		      </div>
	        </div>	   
	        <div class="height-5"></div>
			
	        <div class="w3-row-padding">
	          <div id="privateStyleLabel" class="w3-third">Filter Value*</div>
	          <div class="w3-twothird" style="padding:0px"><input id="privateStyleInput" type="text" name="is" style="width:100%" value="<?php echo $value; ?>" required /></div>
	        </div>

	        <div class="padding-top-20 padding-bottom-5 padding-left-10">
		      <button type="submit" class="imp-button-grey-blue">Apply</button>&nbsp;
	        </div>
          </form>
	 
	      <div class="height-10"></div>
	    </div> 
      </div>
   
      <div class="w3-twothird">
        <div id="divresult" style="height:80vh">
		
	      <script language="php">
	        if ($value != "") {
	          include ("fr/logAttDN.php");
		    }  
	      </script>
	    </div>
      </div>
   
    </div>
	
<script language="php">
  }
</script>	