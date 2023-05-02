<div class="height-10"></div>
<div class="form-main w3-round-medium display-form-shadow">  
  <div class="form-header">Event Out Container From Hamparan</div>  	
  <div class="w3-container"> 

	<div style="padding:3px 3px;border:1px solid #f1f1f1;border-top:0;width:296px">
	 <label>
	    1.&nbsp;&nbsp;Data tanggal tanpa tanda baca (contoh: -, /)<br>
        2.&nbsp;&nbsp;Format Tanggal: yyyyMMdd</label>
    </div>
	
   <div class="height-10"></div>       
    <form id="fHeaderGate" method="post">   
  	  <div class="w3-row-padding">
	   <div class="w3-third">
        <label>Container No.</label>
	    <input class="style-input style-border" type="text" name="noCnt" maxlength="11" style="text-transform:uppercase;text-transform:uppercase;font-weight:600;letter-spacing:.05em" id="noCnt" required />
  	    <div class="height-5"></div>
		<button type="submit" style="padding:3px 10px">Search</button>
		
	   </div> 
	   <div class="w3-third"></div>
	   <div class="w3-third"></div>
	  </div>	    
    </form>   
    <div class="height-10"></div>		
	<div id="content"></div>    
	<div class="height-10"></div>		
  </div> 
</div>  

<?php
  if(isset($_GET['valid'])) {
    if($_GET['valid'] == 0) {echo '<script>swal("Found error while trying to store value given into Database.");</script>';}
	if($_GET['valid'] == 1) {echo '<script>swal("Value has been stored into Database.");</script>';}
  }
?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#fHeaderGate").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("gateout-detail.php", formValues, function(data){ $("#content").html(data); });
    });	  
	  
/*	  
    document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		
        $.post("gateout-detail.php", formValues, function(data){ $("#content").html(data); }); };
    });		    
*/	
  });	
</script>