<div class="hdr25-dark-grey">Hamparan/Workshop Inquiry</div>	
<div style="w3-container">
 <div class="height-10"></div>
 
 <form id="filterLokasi" method="post">

   <div class="w3-row-padding">
     <div class="w3-third">
       <label class="w3-text-grey">Hamparan/Workshop Name</label>
       <select name="location" class="style-select" required >
	    <option value="">&nbsp;</option> 
         <?php include("../asset/libs/db.php");  
		 
		       $query = "Select * From m_Location Order By locationDesc ";
               $result = mssql_query($query);
               while($arr = mssql_fetch_array($result)) { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }		
               mssql_free_result($result);
		       mssql_close($dbSQL);?>
	   </select>
     </div>
	 <div class="w3-twothird"></div>
   </div>
   <div class="height-5"></div>
   <div class="w3-row-padding">
     <div class="w3-third">
	   <label class="w3-text-grey">Hamparan/Workshop In Date</label>
       <input class="style-input style-border" type="text" name="tglIn1" id="fDate1" required
	           value=<?php echo date("Y-m-d");?> title="Year-Month-Date" onKeyUp="dateSeparator('fDate1')" />	   
	 </div>
	 <div class="w3-third">
	   <label class="w3-text-grey">Until</label>	 
       <input class="style-input style-border" type="text" name="tglIn2" id="fDate2" required
	           value=<?php echo date("Y-m-d");?> title="Year-Month-Date" onKeyUp="dateSeparator('fDate2')" />	   	   
	 </div>
	 <div class="w3-third"></div>
   </div>
   
   <div class="height-10"></div>
    <div class="w3-row-padding">
     <div class="w3-half">
	   <button type="submit" class="w3-button w3-blue w3-round-small">Query</submit>
	 </div>
     <div class="w3-half"></div>	   
    </div>	 

 </form>
</div>

<div id="loader-icon" style="display:none;font-weight:500">&nbsp;&nbsp;.. Query on progress, please wait</div>
<div class="height-10"></div>   

<script type="text/javascript">
  $(document).ready(function(){  
    $("#filterLokasi").submit(function(event){
      event.preventDefault();
	  $("#mnr_form").hide(); 		
	  $('#loader-icon').show();
      var formValues = $(this).serialize();
      $.post("mnguntFiltered.php", formValues, function(data){ 
	    $('#loader-icon').hide();
	    $("#mnr_form").html(data);
        $("#mnr_form").show(); 		 		
	  });
    });
  }); 
</script> 