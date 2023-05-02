<div class="w3-container w3-belize-hole w3-text-white">
  <h2><i class="fa fa-server"></i>&nbsp; Container Cleaning</h2>
</div>

<div class="height-20"></div>
<div class="addon-form">
  <form method="post">
	<div class="w3-container">
	  <label class="w3-text-teal">Container Number</label>
	  <input type="text" class="w3-input w3-border searchtext" id="noCnt" name="noCnt" maxlength="11" style="text-transform:uppercase" required />
	</div> 
	<div class="height-10"></div> 
    <div id="content"></div>	  	
  </form>
</div>

<script language="php">
  mssql_close($dbSQL);
</script> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
<script src="../asset/js/sweetalert2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		
        $.post("cleaning-detail.php", formValues, function(data){ $("#content").html(data); }); };
    });		    
  });	
</script>