<script language="php">
  include("../asset/libs/db.php");
  
  echo '<div class="w3-container">
         <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Summary of Hamparan Workshop</h2>
		 <div class="height-10"></div>

		 <div class="w3-row-padding">
		  <div class="w3-twothird">
           <fieldset style="padding-left:0 40px 0 40px; background-color:#f1f1f1"> 
            <legend style="background-color:#fff;font-size:13px" class="w3-text-grey">&nbsp;Event Filter&nbsp;</legend>
            <div class="height-10"></div>		 
            <label class="w3-text-grey">Notes For Date Field:<br>
              &nbsp;&nbsp;1.&nbsp;&nbsp;Enter a date without punctuation (i.e "-","/")<br>
	          &nbsp;&nbsp;2.&nbsp;&nbsp;Date format yyyyMMdd (i.e 20171101).</label>
            <div class="height-20"></div>				 
            
			<form id="fquery" method="get" action="hw_summary_detail.php" target="_blank">
             <div class="w3-row-padding">
              <div class="w3-third">
               <label>Event Activity</label>
	           <input class="w3-input w3-border" type="text" name="filter1" id="fDate1" maxlength="8" required value='.date("Y-m-d").' 
				      title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") />
	          </div>
              <div class="w3-third">
               <label>Until</label>
	           <input class="w3-input w3-border" type="text" name="filter2" id="fDate2" maxlength="8" required value='.date("Y-m-d").' 
				      title="Year-Month-Date" onKeyUp=dateSeparator("fDate2") />		
	          </div>	
			 <div class="w3-third">&nbsp;</div>		
		     </div>
			 
			 <div class="height-20"></div>
			 <div class="w3-row-padding">
			  <div class="w3-half">
			   <label>Workshop Location</label>';
			   
  $query = "Select * From m_Location Order By locationDesc ";
  $result = mssql_query($query);
  echo '       <select name="location" class="w3-select w3-border">';
  while($arr=mssql_fetch_array($result)) { 
    if(isset($_GET['lastLoc'])) {
	  if($_GET['lastLoc'] == $arr[0]) {
	    echo '     <option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; 
	  }
    }		
	else {
      echo '     <option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; 
	}
  }			   
  
  echo'        </select>
              </div>              
			  <div class="w3-half">&nbsp;</div>
             </div>			 
		     
			 <div class="height-20"></div>
  		     <div class="w3-container">		  
              <input type="submit" class="w3-btn w3-blue" name="query" value="Start Query" />
		     </div> 
			</form>             
	       </fieldset>
		  </div>		  
		  <div class="w3-third">&nbsp;</div>
         </div>
        </div>';
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