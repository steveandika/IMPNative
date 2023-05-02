<script language="php">
  echo '<div class="w3-container">
         <div class="w3-responsive">'         ;
		 
  if(isset($_GET['filter1']) && isset($_GET['filter2'])) {
	include("../asset/libs/db.php");
	
    $filter1 = $_GET['filter1'];
	$filter2 = $_GET['filter2']; 
	
	$query = "Select SLDFileName, Format(logDate, 'yyyy-MM-dd') As logDate From logSLD Where logDate Between '$filter1' And '$filter2' Order By logDate; ";
	$result = mssql_query($query);
	
	if(mssql_num_rows($result) <= 0) {
      echo "<label style='letter-spacing:1px;color:red;font-weight:bold;'>0 RECORD HAS FOUND</label>";
	}
    else {
	  echo '<table class="w3-table-all">
	          <thead style="background:#000;color:red">
	            <tr>
				  <th>File Name</th>
				  <th>Upload Date</th>
				  <th>&nbsp;</th>
     		    </tr></thead>
			  <tbody>'; 	  
	  while($arr=mssql_fetch_array($result)) {
	    echo '<tr>
		        <td>'.$arr["SLDFileName"].'</td>
				<td>'.$arr["logDate"].'</td>
				<td><a onclick=opensummary("'.$arr["SLDFileName"].'") class="w3-btn w3-light-grey w3-border" 
				       style="padding:1px 10px;border-radius:4px">Open It >></a> </td>
		      </tr>';
	  }
	  echo '  </tbody></table><br>';
    }
	
    mssql_close($dbSQL);  	
  }
    
  echo ' </div>
         <div id="summary_sld"></div>
		</div>';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script type="text/javascript">
  function opensummary(urlVariable) { 
    $("#summary_sld").load("summary_sld.php?key="+urlVariable);
  }
  
  function opendetail(urlVariable) { 
    $url="/e-imp/mnr/?do=browsesld&key="+urlVariable;
    location.replace($url); 
  }
</script>