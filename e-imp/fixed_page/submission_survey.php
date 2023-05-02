<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>I-ConS | Root</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />
</head>

<body>
 <div class="w3-container">
   <div id="progress" style="padding:10px 10px;font-family:sans-serif;font-size:14px;font-weight:500"></div>
 </div> 
  
 <?php include("../asset/libs/db.php");
       
	   if($failed_reach_db == 0) {
		 $i = 0;  
         $sql="SELECT bookInID AS BookID, CAST(gateIn AS VARCHAR(10)) As gateIn, NoContainer
	           FROM containerJournal 
			   WHERE gateIn BETWEEN '2018-05-01' AND '2018-06-01' AND tanggalSurvey Is NULL AND bookInID NOT LIKE '%BATAL'
			   ORDER BY gateIn, noContainer";	     
		 $parent_result=mssql_query($sql);
		 
		 echo '<script language="javascript">document.getElementById("progress").innerHTML="Calculating..";</script>';
		 $rows=mssql_num_rows($parent_result);
		 
		 while($arr_parent=mssql_fetch_array($parent_result)) {
		   $i++;
		   
		   echo '<script language="javascript">document.getElementById("progress").innerHTML="on progress '.$i.' from '.$rows.'";</script>';	 
		   
		   $sql="SELECT COUNT(1) AS FoundRow FROM containerPhoto WHERE bookID='".$arr_parent["BookID"]."' ";
		   $child_result=mssql_query($sql);
		   $fetch_arr=mssql_fetch_array($child_result);
		   		   
		   if($fetch_arr["FoundRow"] <= 0) {
			 mssql_free_result($child_result);  
		     $sql="UPDATE containerJournal SET bookInID=CONCAT(bookInID,'_BATAL') WHERE bookInID='".$arr_parent["BookID"]."' AND NoContainer='".$arr_parent["NoContainer"]."'";
			 $exec_res=mssql_query($sql);
			 if(!$exec_res) { echo "Failed to Execute ..".$arr_parent["BookID"]."<br>"; }
		   } else { mssql_free_result($child_result); }	   
		 }
		 
         mssql_close($dbSQL);
	   }	 
 ?>
</body>
</html>