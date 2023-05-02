 <div class="w3-container">
    <label style="font: 600 18px/35px Rajdhani, Helvetica, sans-serif;">S L D Log</label>
 </div>
 <div class="height-20"></div>
 <div class="w3-container">
   <label class="w3-text-grey" style="font-size:13px">Catatan:<br>
     &nbsp;&nbsp;1.&nbsp;&nbsp;Masukan data tanggal tanpa tanda baca (contoh: "-","/")<br>
     &nbsp;&nbsp;2.&nbsp;&nbsp;Format Tanggal: yyyyMMdd (contoh: 20171101).</label>
 </div>

 <div class="height-20"></div>
 <div class="w3-container">
   <form id="fquery" method="get">
     <div class="w3-row-padding">
       <div class="w3-quarter">
         <label>Upload Date Between</label>
	     <input class="style-input style-border" type="text" name="filter1" id="fDate1" required value="<?php echo date("Y-m-d")?>" title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") />
		 <div class="height-5"></div>
	   </div>
       <div class="w3-quarter">
         <label>Until</label>
	     <input class="style-input style-border" type="text" name="filter2" id="fDate2" required value="<?php echo date("Y-m-d")?>" title="Year-Month-Date" onKeyUp=dateSeparator("fDate2") />		
	   </div>	
	   <div class="w3-twoquarter"></div>
	  </div>
			 
	  <div class="height-10"></div>

      <button type="submit" class="w3-btn w3-blue w3-round-small">Start Query</button>
	  
   </form>
  </div>
  <div class="height-20"></div>
  <div id="summary"></div> 	
  <div class="height-10"></div>  

<script type="text/javascript">
  $(document).ready(function(){	  
    $("#fquery").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("search_logsld.php", formValues, function(data){ $("#summary").html(data); });
    });	  
  });  
</script>   

<script>
  function dateSeparator(varID) {
    var str = document.getElementById(varID).value;
	panjang = str.length;
	if (panjang==8) {
      var partYear = str.slice(0,4);
	  var partMonth = str.slice(4,6); 
	  var partDate = str.slice(6,8);
	  
	  result = partYear.concat('-', partMonth, '-', partDate);
	  document.getElementById(varID).value = result;
	} 		 
  }
</script>