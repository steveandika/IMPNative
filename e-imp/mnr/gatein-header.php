<div class="page-title">Event In Hamparan</div>
<div class="height-10"></div>
<form method="post">
  <div class="w3-row-padding">
    <div class="w3-quarter"></div>
	<div class="w3-quarter">Container Number</div>
	<div class="w3-quarter"><input class="w3-input w3-border" type="text" name="noCnt" maxlength="11" style="text-transform:uppercase;" id="noCnt" required /></div>
    <div class="w3-quarter"></div>     
   </div>	 
</form>

<div class="height-5"></div>		
<div id="content"></div>  
    
<script type="text/javascript">
  $(document).ready(function(){
    document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		
        $.post("gatein-detail.php", formValues, function(data){ $("#content").html(data); }); };
    });		    
  });	
</script>