    <div class="height-10"></div>	   
	<form class="search border-radius-3" id="fSearchCont" method="post">    
		<h1>Photo/Image Management</h1>
		<div class="height-20"></div>
		<div class="height-30" style="border-top: 1px solid #7a7a52"></div>	 
        <input placeholder="Container Number" type="text" id="noCnt" name="noCnt" required maxlength="11" style="text-transform:uppercase;" value="<?php echo $keywrd;?>" />
		<button type="submit">Search</button>	   
		<div class="height-20"></div>
	</form> 			
	   
	<div class="height-10"></div>	
	<div id="loader-icon" style="display:none;font-weight:500">&nbsp;&nbsp;.. Query on progress, please wait</div>   
	<div id="list"></div>    
 
	<div class="height-10"></div>
	<div id="album"></div> 	 

<script type="text/javascript">
  $(document).ready(function(){
    $("#fSearchCont").submit(function(event){
      event.preventDefault();
	  $("#list").hide();
      $('#loader-icon').show();
	  var formValues = $(this).serialize();	  
      $.post("manage-photoBrowseCnt.php", formValues, function(data){ 
	    $('#loader-icon').hide();
		$("#list").show();
	    $("#list").html(data); 		
	  });
    });
  });	
</script>