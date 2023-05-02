<div class="w3-container w3-belize-hole w3-text-white">
  <h2><i class="fa fa-edit"></i>&nbsp; Container Journal</h2>
</div>

<div class="height-20"></div>
<div class="addon-form">
  <form id="fSearch" method="post">
	<div class="w3-container">
      <label class="w3-text-teal">Container Number</label>
	  <input class="w3-input w3-border" type="text" name="noCnt" maxlength="11" style="text-transform:uppercase;" id="noCnt" required />
	</div>	    
  </form>
  <div class="height-10"></div>		
  <div id="content"></div>  
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
<script src="../asset/js/sweetalert2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		
        $.post("cont-state-detail.php", formValues, function(data){ $("#content").html(data); }); };
    });		    
  });	
</script>