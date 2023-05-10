<div class="height-20"></div>
 <div class="form-main display-form-shadow w3-round-medium">
  <div class="form-header">&nbsp;&nbsp;Summary Complete Repair and Cleaning</div>
  <div class="height-5"></div>
  <div class="w3-container">
   <div class="height-20"></div>
   <form id="fquery" method="post" action="query_complete_cl_fr" target="_blank">
     <label>Start Date</label>
<!--	    <input class="w3-input w3-border" type="text" name="instart" id="fDate1" required value=<?php echo date("Y-m-d");?> maxlength="10"
		   title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") />  -->
     <input class="w3-input w3-border" type="date" name="instart" required value=<?php echo date("Y-m-d");?> style="width:180px" />		   
	 <div class="height-5"></div>
     <label>End Date</label>		  	
<!--	    <input class="w3-input w3-border" type="text" name="inlast" id="fDate2" required value=<?php echo date("Y-m-d");?> maxlength="10" 
		   title="Year-Month-Date" onKeyUp=dateSeparator("fDate2") /> -->
     <input class="w3-input w3-border" type="date" name="inlast" required value=<?php echo date("Y-m-d");?> style="width:180px" />		   		   
     <div class="height-5"></div>
     <label>Hamparan/Workshop</label>
	 <select name="loc" class="w3-select w3-border" style="210px">
      <?php
		     include("../asset/libs/db.php");
             $query = "Select * From m_Location Order By locationDesc ";
             $result = mssql_query($query);
             while($arr=mssql_fetch_array($result)) 
			 { 
  			   echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';
             }
             mssql_free_result($result);
      ?>
  
     </select>	 
	 <div class="height-5"></div> 
     <label>Activity</label>
	 <select name="do" class="w3-select w3-border">
	  <option value="REPAIR">REPAIR&nbsp;</option>
	  <option value="CLEANING">CLEANING&nbsp;</option>
     </select>
	
	 <div class="height-10"></div>
     <button type="submit" class="button-blue">Start Query</button>
  </form>
  <div class="height-20"></div>
</div> 