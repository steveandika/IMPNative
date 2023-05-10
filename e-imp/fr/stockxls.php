<script language="php">
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");

  $filename = 'stock-imp_'.date('YmdHis');
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$filename.".xls");	  
</script>

<table style="font-size:12px;border:1px solid #ccc;border-collapse:collapse" >
  <thead>
	<tr><th colspan="13" style="border:1px solid #ccc;font-size:14px;"> Workshop Stock Container</th></tr>
	<tr>
      <th style="border:1px solid #ccc;">Container Number</th>
	  <th style="border:1px solid #ccc;">Size/Type/Height</th>
	  <th style="border:1px solid #ccc;">Manufacture Year</th>
	  <th style="border:1px solid #ccc;">Construction</th>
      <th style="border:1px solid #ccc;">Date In</th>
      <td style="border:1px solid #ccc;">Due Date</th>
      <th style="border:1px solid #ccc;">Repair</th>
	  <th style="border:1px solid #ccc;">Cleaning</th>
	  <th style="border:1px solid #ccc;">Cond</th>	  
	  <th style="border:1px solid #ccc;">Principle</th>
	  <th style="border:1px solid #ccc;">Consignee</th>
    </tr></thead><tbody>


<script language="php">
  $query="Select a.NoContainer, Format(gateIn,'yyyy-MM-dd') As DTMIn, JamIn, TruckingIn, VehicleInNumber, isPending, 
	      b.principle, b.consignee, b.vessel, a.isCleaning, a.isRepair,
	      c.Mnfr, c.Size, c.Type, c.Height, c.Constr, a.workshopID, d.locationDesc,  DATEDIFF(day, a.gateIn, GETDATE()) AS dueDate, a.Cond,
          Format(a.CRDate, 'yyyy-MM-dd') As CRDate, Format(a.CCleaning, 'yyyy-MM-dd') As CCleaning		  
          From containerJournal a 
	      Inner Join tabBookingHeader b On b.bookID = a.bookInID
		  Inner Join containerLog c On c.ContainerNo = a.NoContainer
		  Left Join m_Location d On d.locationID = a.workshopID
		  Where gateIn Is Not Null And gateOut Is Null Order By a.workshopID Asc, dueDate Desc";
  /*if($_SESSION["location"] != "ALL") { $query=$query." Where b.locationID='".$_SESSION["location"]."' "; }
  $query=$query."Where gateOut Is Null Order By b.locationID Asc, dueDate Desc"; */
  $result=mssql_query($query);
  $numRows=mssql_num_rows($result);
  
  if($numRows <= 0) { echo '<tr><td colspan="13" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; } 
  $loc='';
  while($arr=mssql_fetch_array($result)) {
	$sizeTypeHeight=$arr[12].'/'.$arr[13].'/'.$arr[14];  
    $principle=haveCustomerName($arr[6]);
	$consignee=haveCustomerName($arr[7]);	
	
	if($loc != $arr["locationID"]) {
	  echo '<tr><td colspan="13" style="background-color:#00ffff">Location: '.$arr["locationID"].'</td></tr>';
	  $loc=$arr["locationID"];
	}
    echo '<tr>				
			<td style="border:1px solid #ccc;">'.$arr[0].'</td>
			<td style="border:1px solid #ccc;">'.$sizeTypeHeight.'</td>
			<td style="border:1px solid #ccc;">'.$arr["Mnfr"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["Constr"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["DTMIn"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["dueDate"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["CRDate"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["CCleaning"].'</td>
			<td style="border:1px solid #ccc;">'.$arr["Cond"].'</td> 
	        <td style="border:1px solid #ccc;">'.$principle.'</td>
            <td style="border:1px solid #ccc;">'.$consignee.'</td></tr>';
  }
  mssql_close($dbSQL);
</script>
  	
  </tbody>
</table><br> 
	