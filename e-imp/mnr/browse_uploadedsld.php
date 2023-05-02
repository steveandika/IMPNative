<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<script language="php">
  if(isset($_GET['file_n']) && isset($_GET['size']) && isset($_GET['type']) && isset($_GET['book'])) {
	include("../asset/libs/db.php");
	
    $file_Name = $_GET['file_n'];
	$contSize = $_GET['size'];
	$contType = $_GET['type'];
	$bookID = $_GET['book'];

    $query = "Select d.completeName, a.NoContainer, b.Size, b.Type, b.Height, b.Constr, b.Mnfr, Format(gateIn, 'yyyy-MM-dd') As gateIn,
	          Format(gateOut, 'yyyy-MM-dd') As gateOut, a.Remarks
              From containerJournal a
  			  Inner Join containerLog b On b.ContainerNo = a.NoContainer
			  Inner Join tabBookingHeader c On c.bookID = a.bookInID
			  Left Join m_Customer d On d.custRegID = c.principle
			  Where c.SLDFileName='$file_Name' And b.Size = '$contSize' And b.Type = '$contType' 
			  And a.bookInID = '$bookID'
			  Order By b.Size, b.Type, b.Height, a.NoContainer; ";
    $result = mssql_query($query);
	
    echo '<div class="w3-container"> 
            <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Container List - File Name: '.$file_Name.'</h2>';
		  
    if(mssql_num_rows($result) < 0) {
      echo "<label style='letter-spacing:1px;color:red;font-weight:bold;'>0 RECORD HAS FOUND</label>";
    }
    else {
	  echo '<div class="w3-responsive"> 
	         <table class="w3-table-all">
	          <thead>
	            <tr>
				  <th>Index</th>
	  		      <th>Container No</th>
				  <th>Size</th>
				  <th>Type</th>
				  <th>Height</th>	
                  <th>Mnfr</th>				  
				  <th>Constr</th>
				  <th>Event In</th>
				  <th>Remark</th>
				  <th>Event Out</th>				 
     		    </tr></thead>
			  <tbody>'; 	  
	  $liner = '';
	  $Index = 0;

	  while($arr=mssql_fetch_array($result)) {
  	    if($liner != $arr["completeName"]) {
		  $liner = $arr["completeName"];
		  $Index = 0;
		  echo '<tr>
		          <td colspan="9">Principle: '.$liner.'</td>';
        } 
		
		$Index++;
	    echo '<tr>
		        <td>'.$Index.'</td>		
		        <td>'.$arr["NoContainer"].'</td>
				<td>'.$arr["Size"].'</td>
				<td>'.$arr["Type"].'</td>
				<td>'.$arr["Height"].'</td>
                <td>'.$arr["Mnfr"].'</td>					
				<td>'.$arr["Constr"].'</td>				
				<td>'.$arr["gateIn"].'</td>	
				<td>'.$arr["Remarks"].'</td>	
                <td>'.$arr["gateOut"].'</td>					
		      </tr>';
	  }
	  echo '  </tbody></table></div><br><br>';
    }  
    echo '</div>';
  }
</script>