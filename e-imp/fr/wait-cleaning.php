<script language="php">
  include("../asset/libs/db.php");
  
  $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID,
          a.remarkCond, a.Surveyor, c.Constr, c.Mnfr
          From containerJournal a 
		  Inner Join tabBookingHeader b On b.bookID=a.bookInID
		  Inner Join containerLog c On c.ContainerNo=a.NoContainer 
		  Where (a.gateOut Is Null) And (a.isCleaning=1) And 
		        (b.bookID Not In (Select b.bookID 
				                  From CleaningHeader a
								  Inner Join tabBookingHeader b On b.bookID=a.bookID))
		  Order By a.workshopID, a.gateIn, c.Size, c.Type, c.Height ";
  $result=mssql_query($query);  
</script>

<div class="w3-container" style="background:#fff">
 <div style="padding:10px 10px 15px 15px;border:0"><img src="../asset/img/pt-imp.png" ></div> 		
 <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">&nbsp;&nbsp;Awaiting Cleaning Report</h2>  

<div class="w3-responsive" style="padding:10px 10px 15px 15px;border:0">   
<table class="w3-table w3-bordered">
  <thead>  
	<tr>
      <th>Container No.</th>
	  <th>Size/Type/Height</th>
	  <th>Manufacture Year</th>
	  <th>Construction</th>
	  <th>Date In</th>
	  <th>Survey Remark</th>
	  <th>Surveyor</th>
    </tr>
  </thead>
  <tbody>
  
  <script language="php">
    if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="7" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; }
	
	else {
	  $loc='';
	  while($arr=mssql_fetch_array($result)) {
		$sizeType=$arr[2].'/'.$arr[3].'/'.$arr[4];
		
	    if($loc != $arr[6]) {
		  $loc=$arr[6];
		  echo '<tr><td colspan="7" style="text-align:left"><b>Location: '.$loc.'</b></td></tr>'; }
        
		echo '<tr>
				  <td>'.$arr[1].'</td>
				  <td>'.$sizeType.'</td>
				  <td>'.$arr["Mnfr"].'</td>
				  <td>'.$arr["Constr"].'</td>
				  <td>'.$arr[5].'</td>
				  <td>'.$arr["remarkCond"].'</td>
				  <td>'.$arr["Surveyor"].'</td></tr>';
	  }
	}
    
  </script>
  
  </tbody>
</table>
</div>
<div class="height-10"></div>
</div>

<script language="php">
  mssql_Close($dbSQL);
</script>

<script type="text/javascript">
  function exportxls() {  
    var w=window.open("wclXLS.php"); 
 	$(w.document.body).html(response);}	 
</script>