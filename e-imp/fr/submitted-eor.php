<div class="w3-container">
 <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Submitted Estimate of Repair</h2>

 <div class="height-20"></div>
 <form id="fquery" method="post">
   <label>Container Number</label><br>
   <input type="text" class="w3-input w3-border searchtext" name="noCnt" maxlength="11" style="text-transform:uppercase;" id="noCnt" required />
 </form>
  <div class="height-20"></div>
  <div id="report"></div>
</div> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script type="text/javascript">
  $(document).ready(function(){	  
    document.getElementById('noCnt').addEventListener('keyup', function(e) {
      if(this.value.length == 11) {
        var formValues = $(this).serialize(); 		
        $.post("browse_submitted_eor.php", formValues, function(data){ $("#report").html(data); }); };
    });		
  });  
</script> 
