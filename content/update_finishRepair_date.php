<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-Cons</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />
  <script src="asset/js/modernizr.custom.js"></script>    

</head>

<body style="background-color: #fff;color: #000">

<?php
    include("asset/libs/db.php");
    include("asset/libs/common.php");
	$sql="Select bookID from repairHeader wit (NOLOCK) where FinishRepair Is Null and 
	      tanggalApprove is Not Null and 
	      (estimateDate between '2019-01-16' and '2019-02-01') order by estimateDate";
	
	/*$sql="Select bookInId, Format(CRDate, 'yyyy-MM-dd') CRDate From containerJournal 
	      Where gateIn > '2019-01-01' And  
		  CRDate is Not Null Order By gateIn;";	
    */
	$rsl=mssql_query($sql);
	while($row_arr=mssql_fetch_array($rsl)) {
	  $booknmbr=$row_arr["bookID"];
	  $sqlsub="Select format(CRDate, 'yyyy-MM-dd') completeRepair from containerJournal with (NOLOCK)
	           where bookInID='$booknmbr'and CRDate is not null";
	  $rslsub=mssql_query($sqlsub);
	  if (mssql_num_rows($rslsub) >0) {
        $rowsub=mssql_fetch_array($rslsub);
		$avRepair=$rowsub["completeRepair"];
	  }
      mssql_free_result($rslsub);
	   
	  $sqlsub="Update RepairHeader Set FinishRepair='$avRepair' Where bookID='$booknmbr';";
	  $rslexec=mssql_query($sqlsub);
	  echo $sqlsub."<br>"; 
	}
	
	mssql_free_result($rsl);
	mssql_close($dbSQL);
?>

</body>
</html>