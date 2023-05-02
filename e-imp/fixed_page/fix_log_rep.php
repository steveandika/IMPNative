<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<div class="w3-container">
  <div id="progress" style="padding:10px 10px;"></div>
</div> 

<?php
  include("../asset/libs/db.php");
  
  $qry="Select estimateID, totalMaterial From RepairHeader Where estimateID Not Like 'REP%' And estimateID Not Like 'DRF%' And totalMaterial=0 Order By estimateDate";
  $result=mssql_query($qry);
  $found_rows=mssql_num_rows($result);
  $i=0;
  
  while($arr=mssql_fetch_array($result)) {
	$i++;  
	$estimateNum=$arr["estimateID"];  
	echo '<script language="javascript">document.getElementById("progress").innerHTML="on progress '.$i.' from '.$found_rows.'";</script>';  
	
	$do="Update RepairHeader Set totalMaterial=nilaiDPP Where estimateID='$estimateNum' ";
	$rsl=mssql_query($do);
  }
  mssql_close($dbSQL);
?>