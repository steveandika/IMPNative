<script languange="php">
  include("../asset/libs/db.php");  
</script>

<div class="height-10"></div>
<div class="addon-form w3-round-medium">
  <div class="w3-container w3-belize-hole">
    <h2><i class="fa fa-server"></i>&nbsp; Estimate Of Repair</h2>
  </div>
  <div class="height-10"></div>
  <form id="fHeaderEORRepair" method="get">
    
	<div class="container"> 
      <label class="w3-text-teal">Container Number</label><br>
	  <input type="text" class="w3-input w3-border w3-white" name="noCnt" maxlength="11" style="text-transform:uppercase;" id="noCnt" required />	 
	</div>
	<div class="height-10"></div>  	  
	
  </form>  
  <div id="detail"></div>
</div>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script src="../asset/js/sweetalert2.min.js"></script>   
<script type="text/javascript">
  $(document).ready(function(){
 	document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		  
        $.post("browse-regeor.php", formValues, function(data){ $("#detail").html(data); }); };
      });		  
  });	
</script>