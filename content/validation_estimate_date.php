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
  <style>
    body {transition: background-color .5s;}
  </style>

  <script>
    $(window).load(function() {
	 // Animate loader off screen
	  $(".se-pre-con").fadeOut("slow");;
    });
  </script>  
</head>

<body style="background-color: #fff;color: #000">

<?php
    include("asset/libs/db.php");
    include("asset/libs/common.php");
	
	$sql="Select bookID, gateIn  From RepairHeader a Inner Join containerJournal b On b.bookInID=a.bookID 
	      Where b.gateIn > a.estimateDate And estimateDate Is Not Null And gateIn >= '2012-12-01' Order By b.gateIn";
    $rsl=mssql_query($sql);
	while ($row = mssql_fetch_row($rsl)) {
		$gateIn = $row["gateIn"] +1;
		$booknmbr = $row["bookID"];
		
		$sqlsub="Update RepairHeader Set estimateDate='$gateIn' Where bookID='$booknmbr';";
		$rslexec=mssql_query($sqlsub);
	}
	
	mssql_free_result($rsl);
	mssql_close($dbSQL);
?>

</body>
</html>