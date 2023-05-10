<script language="php">
  include("../asset/libs/db.php");
  
  $filename = 'wcleaning-imp_'.date('YmdHis');
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$filename.".xls");	
  
  $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, b.locationID, 
          a.remarkCond, a.Surveyor, c.Constr, c.Mnfr
          From containerJournal a 
		  Inner Join tabBookingHeader b On b.bookID=a.bookInID
		  Inner Join containerLog c On c.ContainerNo=a.NoContainer 
		  Where (a.gateOut Is Null) And (b.isCleaning=1) And 
		        (b.bookID Not In (Select b.bookID 
				                  From CleaningHeader a
								  Inner Join tabBookingHeader b On b.bookID=a.bookID))
		  Order By b.locationID, a.gateIn, c.Size, c.Type, c.Height ";
  $result=mssql_query($query);  
</script>

<table style="font-size:12px;border:1px solid #ccc;border-collapse:collapse" >
  <thead>  
    <tr><th colspan="7" style="border:1px solid #ccc;font-size:14px;">WAITING CLEANING</th></tr>
	<tr>
      <th style="border:1px solid #ccc;">Container No.</th>
	  <th style="border:1px solid #ccc;">Size/Type/Height</th>
	  <th style="border:1px solid #ccc;">Manufacture Year</th>
	  <th style="border:1px solid #ccc;">Construction</th>
	  <th style="border:1px solid #ccc;">Date In</th>
	  <th style="border:1px solid #ccc;">Survey Remark</th>
	  <th style="border:1px solid #ccc;">Surveyor</th>
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
		  echo '<tr><td colspan="7" class="w3-deep-orange" style="text-align:left;border:1px solid #ccc;"><b>Location: '.$loc.'</b></td></tr>'; }
        
		echo '<tr>
			   <td style="border:1px solid #ccc;">'.$arr[1].'</td>
			   <td style="border:1px solid #ccc;">'.$sizeType.'</td>
			   <td style="border:1px solid #ccc;">'.$arr["Mnfr"].'</td>
			   <td style="border:1px solid #ccc;">'.$arr["Constr"].'</td>
			   <td style="border:1px solid #ccc;">'.$arr[5].'</td>
			   <td style="border:1px solid #ccc;">'.$arr["remarkCond"].'</td>
			   <td style="border:1px solid #ccc;">'.$arr["Surveyor"].'</td></tr>'; }
	}
    
  </script>
  
  </tbody>
</table>

<script language="php">
  mssql_Close($dbSQL);
</script>