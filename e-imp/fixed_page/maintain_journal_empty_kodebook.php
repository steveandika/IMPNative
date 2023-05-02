<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<div class="w3-container">
 <div id="progress" style="padding:10px 10px;font-family:"Open Sans", sans-serif;font-size:14px"></div>
</div> 


<?php
  include("../asset/libs/db.php");
  
  $i=0;
  $qry="SELECT noContainer, workshopID, Format(gateIn,'yyyy-MM-dd') As gateIn FROM containerJournal WHERE bookInID='' ORDER BY workshopID, gateIn";
  $rsl=mssql_query($qry);
  $rows=mssql_num_rows($rsl);
  
  while($arr=mssql_fetch_array($rsl)) {
	$i++;
    echo '<script language="javascript">document.getElementById("progress").innerHTML="on progress '.$i.' from '.$rows.'";</script>';	  
	
	$dateIn=$arr["gateIn"];
	$keywrd=$arr["noContainer"];
	$location=$arr["workshopID"];
	$fname=date("Y-m-d");
	
    $kodeBook=str_replace('-', '', $dateIn); 
	$kodeBook=$location.substr($kodeBook,0,1).substr($kodeBook,2,6);			  
	
	$do="Declare @bookInID VarChar(30), @LastIndex_ Int; 
	     If Not Exists(Select * From logKeyField Where keyFName Like '".$kodeBook.'%'."') Begin
	       Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
		   Set @bookInID = CONCAT('".$kodeBook."','1');			            
			       
		 End Else Begin  
		       Select @LastIndex_ = lastNumber +1 From logKeyField Where keyFName Like '".$kodeBook.'%'."';
               Update logKeyField Set lastNumber =lastNumber +1 Where keyFName Like '".$kodeBook.'%'."';                            
			   Set @bookInID = CONCAT('".$kodeBook."', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex_)))); 
			 End;	
                  
		Insert Into tabBookingHeader(bookID, bookType, blID, principle, consignee, operatorID, SLDFileName) 
		                      Values(@bookInID, 0, @bookInID, '', '', '', '$fname'); 
		Select bookID From tabBookingHeader Where bookID=@bookInID; "; 									  
	 $hasil=mssql_query($do);
	 if($hasil) { 
	   $col=mssql_fetch_array($hasil);
	   $kodeBook=$col['bookID'];
	   //echo $kodeBook."<br>";
	   mssql_free_result($hasil);			  
	 }
	 
	 $do="UPDATE containerJournal SET bookInID='$kodeBook' WHERE NoContainer='$keywrd' And bookInID='' And gateIn='$dateIn' ";
	 $hasil=mssql_query($do);
	 echo $do."<br>";
  }
  mssql_close($dbSQL);
?>