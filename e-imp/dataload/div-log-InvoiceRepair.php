<div id="pageTitle">UPLOAD DN DOCUMENT</div>

<?php
  $roleFin = newvalidMenuAccess('roleFinance',strtoupper($_SESSION['uid']));
  $roleLoader = newvalidMenuAccess('roleLoader',strtoupper($_SESSION['uid']));
  
  if ($roleFin == 1 && $roleLoader == 1){
?>

    <div class="height-20"></div>
    <div id="uploaddiv" class="w3-container" style="max-width:70%;margin:0 auto">
	  <div class="frame" style="padding:10px 10px">		  
	    <form id="frm" method="post" enctype="multipart/form-data">	    
		  <div class="w3-row-padding">
            <div id="privateStyleLabel" class="w3-third">File/Document to Attach</div>	  
		    <div class="w3-third" style="padding:0px">
			  <input id="privateStyleInput" type="file" required name="docName" onchange="validateForm()" style="width:100%!important" />
			  <div class="height-3"></div>
		    </div>
		    <div class="w3-third">
		      <button id="progressBtn" type="submit" class="imp-button-grey-blue">Start Upload</button>
	        </div>
	      </div>
	    </form>
	  </div>
	  
	  <div class="height-20"></div>
      <div id="loader-icon" class="border-radius-3" style="display:none;">..Processing, please wait</div>
	  <div id="uploadResult"></div>
	
    </div>	
	<div class="height-50"></div> 
 
<?php
  }
?>
	
	<script type="text/javascript">
      $(document).ready(function(){  
        $("#frm").on('submit',(function(e) {
         e.preventDefault();
         $.ajax({
           url: "dataload/uploadLogInvoice.php",
           type: "POST",
           data:  new FormData(this),
           contentType: false,
           cache: false,
           processData:false,
           beforeSend : function()
           {
             $("#uploadResult").hide();
			 $("#loader-icon").fadeIn();
			 
             //$("#err").fadeOut();
           },
           success: function(data)
           {
             if(data=='invalid')
             {
		       alert("Invalid");
               // invalid file format.
               // $("#err").html("Invalid File !").fadeIn();
             }
             else
             {
               // view uploaded file.
			   $("#loader-icon").hide();
               $("#uploadResult").html(data).fadeIn();
               $("#frm")[0].reset(); 
             }
           },
           error: function(e) {/* $("#err").html(e).fadeIn();*/}          
         });
        }));
      });	
	</script>