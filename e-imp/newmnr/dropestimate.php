<div id="filterSummaryRepair" class="frame boxshadow border-radius-3" style="height:88vh">
  <div id="pageTitle">#Workshop Management > <strong>Drop Estimate</strong></div> 
  <div class="height-10"></div>	  

  <script language="php">
    $defHTML = "//localhost:8080/imp/prod";
	
    $estimateNo = base64_decode($_GET['en']);
  </script> 
  
  <form id="dropfrm" method="post">
	 <input type="hidden" name="estimate" value="<?php echo $estimateNo ?>" />
		
	 <div class="w3-row-padding">
	  <div class="w3-third">
	    <div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half">Estimate Number</div>
	      <div id="privateStyleLabel" class="w3-half"><strong><?php echo $estimateNo ?></strong></div>
	    </div>
	    <div class="height-5"></div>
	    <div class="w3-row-padding">
	      <div id="privateStyleLabel" class="w3-half">Reason</div>
	      <div class="w3-half" style="padding:0px"><input id="privateStyleInput" type="text" name="reason" maxlength="100" style="width:100%" required /></div>
        </div>
        <div class="height-20"></div>
        <div id="ButtonNavigation" class="border-radius-3" style="width:100%!important;background-color: #f4f6f7">
          <div class=" padding-top-5 padding-bottom-5 padding-left-5">
           <button type="submit" class="w3-button w3-blue-grey">Save</button>
	       <a href=<?php echo $defHTML.'/e-imp/1?src='.base64_encode("newmnr/indexmnr.php").
		                    '&fby='.base64_encode("estimateID").'&kwd='.base64_encode($estimateNo)?> class="w3-button w3-blue-grey">M n R</a>
          </div>
        </div>

        <div class="height-30"></div>
        <div id="viewresult"></div> 		
	  </div>
	  <div class="w3-twothird"></div>
	 </div>
		
  </form>
	 
</div>
<div class="height-5"></div>  

<script type="text/javascript">
  $(document).ready(function(){  
    $("#dropfrm").submit(function(event){
      event.preventDefault();
	  $("#viewresult").hide(); 	  ;
      var formValues = $(this).serialize();
      $.post("newmnr/dropestimate_execute.php", formValues, function(data){ 
	    $("#viewresult").html(data);
        $("#viewresult").show(); 		 		
	  });
    });
  }); 
</script> 