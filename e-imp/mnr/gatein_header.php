<?php
	$msg = "";
	
	if(isset($_GET['valid'])) 
	{
		if($_GET['valid'] == 1) {$msg = "Data sudah ada di dalam Database.";}
		if($_GET['valid'] == 0) {$msg = "Status Container masih aktif di data Hamparan.";}   
	}     
?>

	<div class="height-10"></div>	              
	<form class="search  border-radius-3" id="fheaderIn" method="get">
		<h1>In Hamparan</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	 
		<input type="text" name="noCnt" placeholder="Container Number" maxlength="11" required />		
		<button type="submit">Search</button>
		<div class="height-20"></div>		
	</form>

	<div class="height-10"></div>
	<div id="fcontent"></div>
	<div class="height-10"></div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#fheaderIn").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("gatein_detail.php", formValues, function(data){ $("#fcontent").html(data); });
    });	  
  });
</script>     
