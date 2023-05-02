<div id="progress" style="padding:10px 10px;font-family:sans-serif;font-size:14px;font-weight:500"></div>

<div style="padding:10px 10px;font-family:sans-serif;font-size:14px;font-weight:500">
<?php
  include("../asset/libs/db.php");  
  echo '<script language="javascript">document.getElementById("info").innerHTML="Start";</script>';	
  // And unitSize='20' And unitHeight='STD'
  $qry="Select * From m_RepairPriceList 
        Where priceCode = 'PELINDO'
        Order By Description, unitSize, unitHeight, LocDamage, PartDamage, Act, cLength, cWidth, cQty; ";
  $rsl=mssql_query($qry);
  $rows=mssql_num_rows($rsl);
  $i=1;
  $inserted=0;
  $do='';
  while($col=mssql_fetch_array($rsl)) {
	echo '<script language="javascript">document.getElementById("progress").innerHTML="on progress '.$i.' from '.$rows.'";</script>';	  
	
	$kodeRumus=$col['priceCode'];  
	$type=$col['isType'];
	$size=$col['unitSize'];
	$height=$col['unitHeight'];
	$Loc=$col['LocDamage'];
	$Part=$col['PartDamage'];
	$Repair=$col['Act'];
	$L1=$col['cLength'];
	$L2=$col['cWidth'];
	$qty=$col['cQty'];
	$multi=$col['isMulti'];
	$manhour=$col['MH'];
	$material=$col['materialValue'];
	$ktrg=$col['Description'];
	$iso=$col['IDISO'];

	$find="Select Count(*) As TotalRow From RepairPriceList_Baru 
	        Where priceCode='$kodeRumus' And isType='$type' And unitSize='$size' And unitHeight='$height'
		      And LocDamage='$Loc' And PartDamage='$Part' And Act='$Repair' 
		      And clength=$L1 And cWidth=$L2 And cQty=$qty And isMulti=$multi And MH=$manhour
		      And materialValue=$material; "; 
    $rslExec=mssql_query($find);
	$arr=mssql_fetch_array($rslExec); 
	$foundrow=$arr['TotalRow'];
	mssql_free_result($rslExec);
	
    if($foundrow <=0) {
      $do="Insert into RepairPriceList_Baru 
		  	   Values('$kodeRumus', '$type', '$size', '$height', '$Loc', '$Part', '$Repair', $L1, $L2, $qty,
			          '$multi', $manhour, '$material', '$ktrg', $iso); ";
	  $rslExec=mssql_query($do);
	  if($rslExec) {$inserted++;}
    }
/*	
	  $do=$do."If Not Exists(Select * From RepairPriceList 
	                     Where priceCode='$kodeRumus' And isType='$type' And unitSize='$size' And unitHeight='$height'
		  			       And LocDamage='$Loc' And PartDamage='$Part' And Act='$Repair' 
						   And clength=$L1 And cWidth=$L2 And cQty=$qty And isMulti=$multi And MH=$manhour
						   And materialValue=$material) Begin
                 Insert into RepairPriceList 
			     Values('$kodeRumus', '$type', '$size', '$height', '$Loc', '$Part', '$Repair', $L1, $L2, $qty,
			           '$multi', $manhour, '$material', '$ktrg', $iso);
               End;";
	  */
	
	$i++;
  }
  mssql_free_result($rsl);
/*  
  echo '<script language="javascript">document.getElementById("progress").innerHTML="Executing query.., please wait";</script>';	
  if($do != '') {
    $rslExec=mssql_query($do);
    if($rslExec) {
 	  $qry="Select Count(*) As totalRow From RepairPriceList Where priceCode='$kodeRumus'";
	  $rsl=mssql_query($qry);
	  $col=mssql_fetch_array($rsl);
	  $inserted=$col['totalRow'];
	  mssql_free_result($rsl);
    }
  } 
*/  
  echo '<script language="javascript">document.getElementById("progress").innerHTML="Total Inserted '.$inserted.' from '.$rows.'";</script>';	
  mssql_close($dbSQL);
?>
</div>