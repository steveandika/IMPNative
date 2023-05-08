<?php
  include("../asset/libs/db.php");
?>

<div class="height-10"></div>
<div  class="bw-light-blue border-radius-3 padding-bottom-10" style="background-color:#D4E6F1"> 
	<div class="height-10"></div>  
	<form id="formCedexFilter" method="get" action=""> 
		<input type="hidden" name="do" value="cedexQuery">   

		<ul class="flex-container">
			<li class="flex-item">* Price Code :&nbsp;</li>	 
			<li class="flex-item" style="width:120px">
				<select name="repairCode" required class="style-input" style="width:100px">
					<?php 
						$sql = "Select Distinct a.priceCode From m_RepairPriceList a Inner Join m_RepairPriceList_Header b On b.priceCode=a.priceCode Order By priceCode";	   
						$res = mssql_query($sql);
						while($arrfetch = mssql_fetch_array($res))
						{
							echo '<option value="'.$arrfetch["priceCode"].'">'.$arrfetch["priceCode"].'</option>'; 
						}
						mssql_free_result($res);		 
					?>	   
				</select>	 
			</li>
			<li class="flex-item" >ISO Size :&nbsp;</li>	 
			<li class="flex-item" style="width:62px">
 	  <select name="unitsize" class="style-input" style="width:50px">
	   <option value="0">&nbsp;</option>
	   <?php
	     if($size == '20') { echo '<option selected value="20">20</option>'; }
		 else { echo '<option value="20">&nbsp;20</option>'; }
	     if($size == '40') { echo '<option selected value="40">40</option>'; }
		 else { echo '<option value="40">&nbsp;40</option>'; }
	     if($size == '45') { echo '<option selected value="45">45</option>'; }
		 else { echo '<option value="45">&nbsp;45</option>'; }		 
	   ?>
      </select> 	 		 
	 </li>
	 <li class="flex-item">Dmg Location :&nbsp;</li>	  
	 <li class="flex-item" style="width:60px">
	   <input type="text" style="text-transform:uppercase; width:50px;" maxlength="1" class="style-input" name="damloc" /> </li>
	 <li class="flex-item">Dmg Part :&nbsp;</li>	  
	 <li class="flex-item" style="width:60px">
	   <input type="text" style="text-transform:uppercase; width:50px;" maxlength="5" class="style-input" name="dampart" /></li>
	 <li class="flex-item">Repair/Action :&nbsp;</li>	  	 
	 <li class="flex-item" style="width:50px">
	   <input type="text" style="text-transform:uppercase; width:40px" maxlength="3" class="style-input" name="act" /></li>	 

	 <li class="flex-item">Qty :&nbsp;</li>	  	 
	 <li class="flex-item" style="width:50px">
	   <input type="text" onkeypress="return isNumber(event)" style="text-align:right; width:40px" class="style-input" name="qty" /></li>
	 <li class="flex-item">L1 :&nbsp;</li>	  	 
	 <li class="flex-item" style="width:50px">
	   <input type="text" onkeypress="return isNumber(event)" style="text-align:right; width:40px" class="style-input" name="size1" /></li>
	 <li class="flex-item">L2 :&nbsp;</li>	  	 
	 <li class="flex-item" style="width:50px">
	   <input type="text" onkeypress="return isNumber(event)" style="text-align:right; width:40px" class="style-input" name="size2" /></li>	 

	</ul>
	<div class="height-10"></div>
	<ul class="flex-container">
	 <li class="flex-item"><button type="submit" class="button-blue">Search</button></li>
	 <li class="flex-item"><input type="button" onclick="discharge()" class="button-blue" name="batal" value="Reset Entry" /></li>
	</ul>
   
   </form>
   <div class="height-10"></div>
</div>

<?php  
  mssql_close($dbSQL);
?>  

<script type="text/javascript">
  $(document).ready(function(){
    $("#formCedexFilter").submit(function(event){
      event.preventDefault();	 
	  $('#loader-icon').show();
      var formValues = $(this).serialize();
      $.get(
	   "cedexQuery.php", formValues, function(data)
	   { 
	     $("#loader-icon").hide();
	     $("#process").html(data); 
	   });	  
    });	  
  });

  function discharge() 
  { 
    $url="/e-imp/master.data/?show=cedexBrowse";  
	location.replace($url); 
  }     
</script> 