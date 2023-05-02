<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<div class="w3-container">
 <div id="progress" style="padding:10px 10px;font-family:"Open Sans", sans-serif;font-size:14px"></div>
</div> 

<?php
  include("../asset/libs/db.php");
  
  $i=0;
  ///*Where (repairID Like 'WW%' Or repairID Like 'DW%' Or repairID Like 'CC%' Or repairID Like 'SC%') ";
  //$do="Update CleaningHeader Set estimateID=Null";
  //$rslExec=mssql_query($do);	  
  //And (d.materialValue=0 Or c.estimateID Is Null)
  
  /*$qry="Select 
          a.estimateID, a.BookID, a.ContainerID, b.repairID, FOrmat(a.estimateDate,'yyyy-MM-dd') As estimateDate, c.cleaningID, b.materialValue, b.totalValue 
        From       repairHeader a
		Inner Join repairDetail b On b.estimateID=a.estimateID And b.Remarks Like '%CLEANING%'
        Inner Join CleaningHeader c On c.BookID=a.BookID And c.containerID=a.containerID 
        Inner Join CleaningDetail d On d.CleaningID=c.CleaningID 
        Where (a.estimateDate Between '2018-01-01' And '2018-03-31') 
		Order By   a.estimateDate, a.estimateID";	*/
  $qry="Select a.nilaiDPP, a.cleaningID
        From CleaningHeader a 
		Inner Join CleaningDetail b On b.cleaningID=a.cleaningID
		Inner Join tabBookingHeader c On c.BookID=a.BookID
		Where b.materialValue Is Null Or b.materialValue=0 And a.nilaiDPP>0
		Order By a.cleaningID";
            
  $rsl=mssql_query($qry);
  //echo $qry;
  $rows=mssql_num_rows($rsl);  
  while($arr=mssql_fetch_array($rsl)) {
	$i++;  
	echo '<script language="javascript">document.getElementById("progress").innerHTML="on progress '.$i.' from '.$rows.'";</script>';
	/*
    $do="Update CleaningHeader Set estimateID='".$arr["estimateID"]."', nilaiDPP=".$arr["totalValue"]." Where cleaningID='".$arr['cleaningID']."'; 
         Update CleaningDetail Set materialValue=".$arr["materialValue"]." Where cleaningID='".$arr['cleaningID']."'; ";
	*/
	$do="Update CleaningDetail Set materialValue=".$arr["nilaiDPP"]." Where cleaningID='".$arr["cleaningID"]."' And materialValue = 0";
	
	//echo $do."<br>";	 
    $rslExec=mssql_query($do);	  
  }
  mssql_free_result($rsl);
  
  $qry="Select Count(CleaningID) As FoundRows From CleaningHeader Where estimateID='' Or estimateID=Null ";
  $rsl=mssql_query($qry);
  $rows=mssql_num_rows($rsl);
  if($rows==1) {
	$col=mssql_fetch_array($rsl);  
    echo '<script language="javascript">document.getElementById("progress").innerHTML="Unlink Cleaning Activity with Repair :  '.$col[0].' records";</script>';	  	  
  }
  mssql_free_result($rsl);  
  mssql_close($dbSQL);  
?>  