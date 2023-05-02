<div style="top:10px"></div>
<div style="font-family:sans-serif">
  <div id="info"></div>
<?php
  include("../asset/libs/db.php");
  
  $kodeBooking='';
  $kywrd='';
  $qry="Select a.bookInID, a.NoContainer, a.workshopID, b.principle, b.vessel, Format(b.vesselATA,'yyyy-MM-dd') As vesselATA, 
               b.consignee, b.voyageID, Format(b.ETA,'yyyy-MM-dd') As ETA, b.SLDFileName, Format(gateIn, 'yyyy-MM-dd') As gateIn 
        From containerJournal a 
		Inner Join tabBookingHeader b On b.BookID=a.bookInID
		Where (a.bookInID Like 'HMP%' Or a.bookInID Like 'SLD%') And (a.gateIn= '2018-01-05') Order By a.gateIn";
  $rsl=mssql_query($qry);
  $i=0;
  $rows=mssql_num_rows($rsl);
  while($col=mssql_fetch_array($rsl)) {
	$i++;
	
	$kodeBooking=$col['bookInID'];  
	$kywrd=$col['NoContainer'];
	$workshop=$col['workshopID'];
    $mlo=$col['principle'];
	$vesselName=$col['vessel'];
	$vesselATA=$col['vesselATA'];
	$consignee=$col['consignee'];
	$voyageNo=$col['voyageID'];
	$ETA=$col['ETA'];
	$dateIn=$col['gateIn'];
	$tglTrans=$dateIn;
	
    $bookIDNew=str_ireplace("-", "", $tglTrans);
	$bookIDNew=substr($bookIDNew,0,1).substr($bookIDNew,2,6);
	$bookIDNew=$workshop.$bookIDNew;
	echo '<script language="javascript">document.getElementById("info").innerHTML="on progress '.$i.' from '.$rows.'";</script>';	
	
    $do="Declare @KeyField VarChar(30); 
	     If Not Exists(Select * From logKeyField Where keyFName Like '$bookIDNew') Begin 
	       Set @KeyField=CONCAT('$bookIDNew','1');
	       Insert Into logKeyField Values('$bookIDNew', 1); 
	     End Else Begin
	           Declare @LastKey Int, @StrLastKey VarChar(6);
	              
  		       Select @LastKey=lastNumber +1 From logKeyField Where KeyFName Like '$bookIDNew'; 
	                  
			   Set @StrLastKey = LTRIM(RTRIM(CONVERT(VARCHAR(6),@LastKey))); 
			   Set @KeyField=CONCAT('$bookIDNew',@StrLastKey);
	           Update logKeyField Set lastNumber=lastNumber +1 Where KeyFName Like '$bookIDNew'; 
	         End;
	            
         Insert Into tabBookingHeader(bookID,bookType,blID,principle,vessel,vesselATA,consignee,operatorID,voyageID,ETA,SLDFileName)
		                       Values(@KeyField,0,'$kodeBooking','$mlo','$vesselName','$vesselATA','$consignee','','$voyageNo','$ETA','');
		 Update containerJournal Set bookInID=@KeyField Where bookInID='$kodeBooking' And NoContainer='$kywrd';
		 Update repairHeader Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd';
		 Update cleaningHeader Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd';
		 Update containerPhoto Set bookID=@KeyField Where bookID='$kodeBooking' And ContainerID='$kywrd'; 
		 Select bookInID From containerJournal Where bookInID=@KeyField And NoContainer='$kywrd'; ";	
	$rsl_exec=mssql_query($do); 
	if($rsl_exec) {
	  $col_exec=mssql_fetch_array($rsl_exec);
	  $bookIDNew=$col_exec['bookInID'];
	  mssql_free_result($rsl_exec);
	  //echo $kodeBooking.' -> '.$bookIDNew.'<br>';
	} else { echo $do.'<br>'; }		
  }
  echo '<script language="javascript">document.getElementById("info").innerHTML="Finished .. total: '.$rows.' ";</script>';	
  mssql_close($dbSQL);
?>

</div>