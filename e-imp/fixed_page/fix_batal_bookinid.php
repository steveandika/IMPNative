<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>Root</title>
  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />

</head>

<body style="overflow:auto!important">   

 <div class="w3-container">
   <div id="progress" style="padding:10px 10px;"></div>
 </div> 

<?php
  include("../asset/libs/db.php");
  
  $sql="SELECT bookInID, NoContainer 
        FROM containerJournal 
		WhERE (gateIn BETWEEN '2018-05-01' AND '2018-06-28') AND cleaningType Is Not Null AND bookInID LIKE '%_BATAL' AND gateOut Is Null 
		ORDER BY noContainer";
  $source=mssql_query($sql);
  $total_row=mssql_num_rows($source);
  $i =1;
  while($arr_source = mssql_fetch_array($source)) {
	echo '<script language="javascript">document.getElementById("progress").innerHTML="Progress.. '.$i.'/'.$total_row.' rows";</script>';	 
	
	$nocont=$arr_source["NoContainer"];
	$bookID=$arr_source["bookInID"];

    $sql="SELECT COUNT(1) AS FoundRec
          FROM containerJournal
          WHERE NoContainer='$nocont' AND gateOut IS NULL AND bookInID Not Like '%BATAL'";
    $dest=mssql_query($sql);
    $arrfetch=mssql_fetch_array($dest);
	$j =0;
    if($arrfetch["FoundRec"] == 0) {
	 $stopped = 0;	
	 $tmp = "";
	  while($stopped == 0) {	
	    if($bookID[$j] != "_") { 
		  $tmp = $tmp.$bookID[$j]; 
		  $j++; 
		} else { 
		  mssql_free_result($dest);
		  $sql="UPDATE containerJournal SET bookInID='$tmp' WHERE bookInID='$bookID' And noContainer='$nocont'; ";
		  $result=mssql_query($sql);
		  
		  $stopped = 1; 
		}
      } 	  
	}	
	else { 
	  mssql_free_result($dest); 
	  
	  echo $nocont." SKIP <br>";
	}
	$i++;
  }
  
  mssql_close($dbSQL);
?>  

</body>
</html>