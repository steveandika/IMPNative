<script language="php">
  include("../asset/libs/db.php");
  
  if(isset($_POST['noCnt'])) {
    $keywrd=$_POST['noCnt'];
	
    $query="Select estimateID, containerID From RepairHeader Where isAVRepair=0 And containerID='$keywrd'";
	$result=mssql_query($query);	  
	$rowResult=mssql_num_rows($result);
	
	if($rowResult > 0) {
	  $arr=mssql_fetch_array($result);
	  $kodeEstimate=$arr[0];
	}
	mssql_free_result($result);
	
	if($rowResult > 0) {
	  echo '<label>Estimate Number</label>';
	  echo '<input class="w3-input w3-light-grey" type="text" name="kodeEstimate" readonly value='.$kodeEstimate.'>'; 
	  echo '<div class="height-10"></div>';
	  
	  echo '<a onclick=viewlog("'.$kodeEstimate.'") class="w3-btn w3-blue-grey">View Photo</a>'; }
	else {
	  echo '<label>Estimate Number</label>';
	  echo '<input class="w3-input w3-light-grey" type="text" name="kodeEstimate" readonly value='.$kodeEstimate.'>';
	  echo '<div class="height-20"></div>';
      echo '<div class="w3-container w3-red">
	          <h1>Error:</h1><p>Invalid input found. Either Container not said in active Estimate Repair or not in Repair System.</p>
            </div>';	  
	}
  }
</script>

<script type="text/javascript">	 
  function viewlog(urlVariable) { $("#album").load("get-album.php?id="+urlVariable); }	
</script>`