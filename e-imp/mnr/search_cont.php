<script language="php">
  echo '<div class="height-20"></div>
        <div class="w3-container">
         <table class="w3-table w3-bordered" style="background-color:#2196F3;font-weight:600">
	 	  <tr style="color:#fff!important;">
		   <td>SEARCH CONTAINER</td>
		  </tr>
		 </table>  
		 <div class="height-5"></div>
		 <form id="fdoquery" method="get" action="?">
		   <input type="hidden" name="do" value="cont_list">
           <label>Container Number</label>
		   <input class="w3-input w3-border" type="text" name="noCnt" maxlength="11" style="text-transform:uppercase" id="noCnt" required />  
		   
		   <div class="height-10"></div>
		   <label>Workshop Location</label>
		    <select name="location" class="w3-select w3-border">';
			
  $query = "Select * From m_Location Order By locationDesc ";
  $result = mssql_query($query);
  while($arr = mssql_fetch_array($result)) { 
    echo '    <option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; 
  }
  echo'     </select>		   
            <div class="height-20"></div>
			<button type="submit" class="w3-btn w3-light-grey w3-border">Query</button>
		 </form>
		 <div class="height-20"></div>
		 <div id="sub_content"></div>
		 <div class="height-5"></div>
        </div>';
</script>