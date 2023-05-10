<div class="height-20"></div>
<div class="form-main display-form-shadow w3-round-medium">
<div class="form-header">&nbsp;&nbsp;Summary Of Hamparan/Workshop Out</div>
 <div class="w3-container"> 
  <div class="height-10"></div>		 
  <label class="w3-text-grey">Notes For Date Field:<br>
	&nbsp;&nbsp;1.&nbsp;&nbsp;Enter a date without punctuation (i.e "-","/")<br>
	&nbsp;&nbsp;2.&nbsp;&nbsp;Date format yyyyMMdd (i.e 20171101).</label>
 </div> 
 <div class="height-20"></div>
 <div class="w3-row-padding">
 <div class="w3-half">	
  <form id="fquery" method="post" action="query_outworkshop" target="_blank">
   <label>From Date</label>
   <input class="style-input style-border" type="text" name="tglOut" id="fDate1" required
	      value=<?php echo date("Y-m-d");?> title="Year-Month-Date" onKeyUp="dateSeparator('fDate1')" />
   <div class="height-5"></div>		  
   <label>Until Date</label>
   <input class="style-input style-border" type="text" name="tglOut2" id="fDate2" required
	      value=<?php echo date("Y-m-d");?> title="Year-Month-Date" onKeyUp="dateSeparator('fDate2')" />		
   <div class="height-5"></div>		  	  	  
   <label>Hamparan/Workshop Location</label>
	<?php include("../asset/libs/db.php"); 	   
               
	      $query = "Select * From m_Location Order By locationDesc ";
          $result = mssql_query($query);
          echo '<select name="location" class="style-select style-border">';
          while($arr=mssql_fetch_array($result)) { 
            echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; 
	      }
          echo '</select>'; ?>
   <div class="height-10"></div>
   <button type="submit" class="w3-btn w3-blue w3-round-small">Query</button>
  </form>  
 </div>   
 <div class="w3-half"></div>   	 
</div>
<div class="height-10"></div>
</div> 