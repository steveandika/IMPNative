<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS | Fixed-Up CC</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />
  <script src="asset/js/modernizr.custom.js"></script>  
  <script src="asset/js/jquery.min.2.1.1.js"></script>    
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

<body style="background-color: #fff">
  
  <div class="se-pre-con"></div>
  
<?php 
  include("asset/libs/db.php");
  
  $i=0;
  
  $sql="Select bookInID, NoContainer From view_Summary_Hamparan Where CCleaning ='' 
        And bookInID In (Select BookID As bookInID From cleaningHeader)
        And (gateIn > '2019-04-02' And gatein < '2019-07-07') Order By bookInID;";
  $res=mssql_query($sql);
  while ($row_fetch=mssql_fetch_array($res)) {
	$cleaning_date="";
	$kode_booking=$row_fetch["bookInID"];
	
	$subsql="Select cleaningDate From CleaningHeader Where BookID='$kode_booking'; ";
	$ressub=mssql_query($subsql);
	while ($row_fetch_sub=mssql_fetch_array($ressub)) {
	  $cleaning_date=$row_fetch_sub["cleaningDate"];
	}
	mssql_free_result($ressub);
	
	if ($cleaning_date != "") {
	  $i++;	 
	  $subsql="Update containerJournal Set CCleaning='$cleaning_date' Where bookInID='$kode_booking'; ";
	  $ressub=mssql_query($subsql);
	  
	  echo $i.". Kode Booking ".$kode_booking." updated "."<br>";
	}
	
  }	  
  
  mssql_close($dbSQL);
?>

</body>
</html>