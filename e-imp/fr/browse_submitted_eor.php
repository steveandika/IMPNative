<script language="php">
  include("../asset/libs/db.php");  
  
  if(isset($_POST["noCnt"])) {
    $keywrd=$_POST["noCnt"];
	
	$query="Select a.estimateID, a.containerID, CONCAT(b.Size,'/',b.Type,'/',b.Height) As ISOCode, b.Constr, b.Mnfr,
	        Format(c.gateIn, 'yyyy-MM-dd') As DTMin, c.JamIn, a.BookID
	        From RepairHeader a
			Inner Join containerLog b On b.ContainerNo=a.containerID
			Inner Join containerJournal c On c.bookInID=a.BookID And c.NoContainer=a.containerID
			Where (ContainerID='$keywrd') And (EstimateID Not Like 'DRF%') Order By estimateDate";
	$result=mssql_query($query);
	$rowNum=mssql_num_rows($result);	
  }
</script>

<div class="w3-responsive w3-animate-opacity">
 <table class="w3-table w3-border w3-bordered">
  <thead>
	<tr><th colspan="8" style="text-align:left"><h2>Log EOR</h2></th></tr>
	<tr>
	  <th>Estimate Number</th> 
	  <th>&nbsp;</th>
      <th>Container Number</th>
	  <th>Size/Type/Height</th>
	  <th>Manufacture Year</th>
	  <th>Construction</th>
      <th>Date In</th>
      <th>Time Log</th>
    </tr></thead><tbody>

<script language="php">
  if($rowNum <= 0) { echo '<tr><td colspan="8" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; } 
  while($arr=mssql_fetch_array($result)) {
	$url="estimate=".$arr[0]."&unit=".$arr["containerID"]."&book=".$arr["BookID"];
	
    echo '<tr style="text-align:center">
	        <td>'.$arr[0].'</td>
			<td><a onclick=openTab("'.$url.'") style="cursor:pointer"><i class="fa fa-print"></i> Print</a></td>
			<td>'.$arr["containerID"].'</td>
			<td>'.$arr["ISOCode"].'</td>
			<td>'.$arr["Mnfr"].'</td>
			<td>'.$arr["Constr"].'</td>
			<td>'.$arr["DTMin"].'</td>
			<td>'.$arr["JamIn"].'</td></tr>';
  }
  mssql_free_result($result);
  mssql_close($dbSQL);
</script>
	
  </tbody>
 </table>  
</div> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script type="text/javascript">
  function openTab(urlVariable) { 
    var w=window.open('print_eor.php?'+urlVariable);
	$(w.document.body).html(response); }		
</script>