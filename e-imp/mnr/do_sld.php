<script language="php">

 echo '<div class="w3-container">
         <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Upload Container List from SLD</h2>
           <div class="height-20"></div>';

 echo '  <form id="fsld" method="post">
           <div class="w3-container"> 
             <label class="w3-text-teal">Feeder Operator</label>
		     <select name="feederOpr" class="w3-select w3-border">';
 $query="Select custRegID, completeName From m_Customer Where asFeed=1 Order By completeName";
 $result=mssql_query($query);
 while($arr=mssql_fetch_array($result)) {
   echo '     <option value='.$arr[0].'>&nbsp;'.$arr[1].'</option>';
 }
 mssql_free_result($result);
 echo '      </select>
          </div>';
 
 echo '    <div class="height-10"></div>
           <div class="w3-row-padding">
             <div class="w3-quarter">
			   <label>Vessel Name</label>
               <input type="text" class="w3-input w3-border" name="vesselName" maxlength="50" style="text-transform:uppercase" required>			   
			 </div>
             <div class="w3-quarter">
			   <label>Voyage Number</label>
               <input type="text" class="w3-input w3-border" name="voyageNumber" maxlength="10" style="text-transform:uppercase" required>			   
			 </div>			 
           </div>';
		   
 echo '   <div class="height-10"></div>
          <label class="w3-text-teal">Voyage Number</label>
          <div class="height-10"></div>
          <label class="w3-text-teal">Port Name</label>
          <div class="height-10"></div>
          <label class="w3-text-teal">E T A</label>
          <div class="height-10"></div>
         </form> 
       </div>';
	   
 mssql_close($dbSQL);
</script>