<script language="php">
  session_start();    
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  $kodeBooking = '';
  $keywrd = ''; /* instead Container Number */
  
  if(isset($_GET['kodeBooking']))    { $kodeBooking = Trim($_GET['kodeBooking']); } 
  if(isset($_GET['noCnt']))          { $keywrd = Trim($_GET['noCnt']); }  
  if(isset($_GET['laborDepo']))      { $labour = $_GET['laborDepo']; }
  if(isset($_GET['priceCode']))      { $priceCode = $_GET['priceCode']; }
  if(isset($_GET['whatToDo']))       { $to_do = strtoupper($_GET["whatToDo"]); }
  
  if(isset($_POST['noCntFilter']))   { $noCnt = $_POST['noCntFilter']; }
  if(isset($_POST['loc']))           { $locFilter = $_POST['loc']; }

  // -- Start of: WhatToDo: CALCULATE/SET AS DRAFT/SUBMITTED -- //  
  if(isset($_GET["whatToDo"]) && isset($_GET["noCnt"])) 
  {    
  	$groupRepair='';	
    $totalBaris=count($_GET['chk']);
	  	
	for($i=0; $i<$totalBaris; $i++) 
	{
	  $validateGrid="View Result";  
	  $chk[$i]=$_GET['chk'][$i];

      if($chk[$i] == "on") 
	  {
	    $size=trim($_GET['size']);
        $tipe=trim($_GET['tipe']);		
		$height=trim($_GET['height']);

		$laborDepo=$_GET['laborDepo'];
		if($tipe=='GP') { $isType='DV'; }
		   
		$isFalse=0; /* var for counter */
		
/*		if($_GET['loc'][$i] != '' && $_GET['part'][$i] != '' && $_GET['dmg'][$i] != '' && $_GET['repair'][$i] != '' &&
		   $_GET['length'][$i] != '') */
		if($_GET['loc'][$i] != '' && $_GET['repair'][$i] != '' &&  $_GET['qty'][$i] != '') 		   
		{
		  $loc[$i]=strtoupper($_GET['loc'][$i]);  
		  if(substr($loc[$i],0,2) == 'LT') { $loc[$i]=='LT'; }
		  if(substr($loc[$i],0,2) == 'RT') { $loc[$i]=='RT'; }
		  $part[$i]=strtoupper(str_replace(' ','',$_GET['part'][$i]));
		  if(trim($part[$i]) == '') { $part_ = '%'; }
		  else { $part_ = $part[$i].'%'; }
          $dmg[$i]=strtoupper($_GET['dmg'][$i]); 
		  $repair[$i]=strtoupper($_GET['repair'][$i]);
		  $length[$i]=$_GET['length'][$i];
		  if($length[$i] =='') { $length[$i] = 0; }
		  $Width[$i]=$_GET['Width'][$i];
		  if($Width[$i] =='') { $Width[$i] = 0; }
		  $qty[$i]=$_GET['qty'][$i];
		  if($qty[$i] =='') { $qty[$i] = 0; }
		  $party[$i]=$_GET['party'][$i];

		  $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".'%'.$size.'%'."' And 
		         (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".substr($loc[$i],0,1)."' And PartDamage Like '".$part_."' 
		          And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].")"; 
		  $result=mssql_query($query);	
		  $rows=mssql_num_rows($result);			  
		  
		  if($rows != 1) {		    
		    mssql_free_result($result);
			$query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".'%'.$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".substr($loc[$i],0,1)."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cWidth=".$length[$i]." and cLength=".$Width[$i].") ;";	   
		    $result=mssql_query($query);	
		    $rows = mssql_num_rows($result);		
		  }

		  if($rows != 1) {
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".'%'.$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".substr($loc[$i],0,1)."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].") And cQty=".$qty[$i]."; ";
		    $result=mssql_query($query);	
		    $rows=mssql_num_rows($result);		
		  }
			
		  if($rows != 1) {
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".'%'.$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".substr($loc[$i],0,1)."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cWidth=".$length[$i]." and cLength=".$Width[$i].") And cQty=".$qty[$i]."; ";
			$result=mssql_query($query);	
			$rows=mssql_num_rows($result);		
		  }	
          //echo $query.'<br>';		  
		  
		  if($rows == 1) {
		    $arr=mssql_fetch_array($result);
  	        $status[$i]="OK";
			$deskripsi[$i]=$arr["Description"];
			
		    if($arr["isMulti"]==0) { 
		  	  $mh[$i]=$arr["MH"];
              $labor[$i]=$mh[$i]*$laborDepo;			
			  $material[$i]=$arr["materialValue"];
			  //$total[$i]=($arr["MH"]*$laborDepo) * $arr["materialValue"];  
			  $total[$i]=$labor[$i]+ $material[$i];  
		    }
			
		    if($arr["isMulti"]==1) { 
			  $mh[$i]=$arr["MH"] *$qty[$i];
              $labor[$i]=$mh[$i]*$laborDepo;			
			  $material[$i]=$arr["materialValue"] *$qty[$i];
		      //$total[$i]=$arr[12]; }
			  $total[$i]=$labor[$i] +$material[$i];  
		    }
		  } else {
		      $isFalse++;  
  	          $status[$i]="FALSE";
		      $deskripsi[$i]='';
		      $mh[$i]=0;
              $labor[$i]=0;			
		      $material[$i]=0;
		      $total[$i]=0;  
		    }
		  mssql_free_result($result);
	    }  
	  } /* end of validation checkbox*/
    }	/* end of looping */		

    if($to_do == 'DRAFT' && $isFalse==0) {
	  $query = "Select currency From m_RepairPriceList_Header Where priceCode = '$priceCode'";
      $resl = mssql_query($query);
      if(mssql_num_rows($resl) > 0) {
	    $col = mssql_fetch_array($resl);
		$currency = $col['currency'];
      } else { 
	      $currency = 'IDR'; 
		}	  
	  mssql_free_result($resl);
	  
	  $do="If Not Exists(Select * From repairHeader Where bookID='$kodeBooking' And containerID='keywrd') begin
	         Declare @NewDraft VarChar(30),@LastIndex Int, @Periode VarChar(8), @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		     Set @ToDay_ = FORMAT(GETDATE(), 'yyyy-MM-dd'); 
                 				
		     Select @Periode=CONCAT('DRF', CONCAT(SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),1,1), SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),3,6))); 
		     Set @Keywrd=CONCAT(@Periode,'%');
				
		     If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
			   Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
               Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
			   Set @NewDraft=CONCAT(@Periode, '-', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
		     End Else Begin 
		          Insert Into logKeyField(keyFName, lastNumber) Values(@Periode, 1);
			      Set @NewDraft=CONCAT(@Periode, '-1');
			     End;
				
    	     Insert Into RepairHeader(estimateID, containerID, estimateDate, nilaiDPP, totalHour, totalLabor, totalMaterial, isValid, 
			                          totalOwner, totalUser, laborRate, bookID,isAVRepair,exchangeRate, currencyAs, groupRepair, statusEstimate, repairPriceCode) 
		                       Values(@NewDraft, '$keywrd', CONVERT(VARCHAR(10), GETDATE(), 126), 0,0,0,0,1,0,0, $laborDepo, '$kodeBooking', 0,0,'$currency', '', 
				  			         'DRAFT','$priceCode');
             Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Create EOR Draft ','$keywrd',' ','$kodeBooking'));									 
		   End; ";

	  $res_exec=mssql_query($do);
      
	  $query = "Select estimateID From RepairHeader Where containerID='$keywrd' And bookID='$kodeBooking'";
	  $result = mssql_query($query);
	  if(mssql_num_rows($result) > 0) {
	    $rows = mssql_fetch_array($result);
		$kodeEstimate = $rows['estimateID'];
		mssql_free_result($result);
  	    
		$index=1;

		$do = "Delete From repairDetail Where estimateID='$kodeEstimate'; ";
		$result = mssql_query($query);
		
	    for($i=0; $i<$totalBaris; $i++) {
	      if($party[$i] == 'O') { $partnum=0; }
	      if($party[$i] == 'U') { $partnum=1; }	
	      if($party[$i] == 'T') { $partnum=2; }			
		  if($chk[$i] == 'on')  {$rowFlag = 0;}
		  else {$rowFlag = 1;}		  
			
	 	  $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                        widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                 Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                        $Width[$i], $qty[$i], '$party[$i]', $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', $rowFlag); ";				  
		  $res_exec=mssql_query($do); 
		  $index++;		  
	    }
	  }		  
		
      $DPP=0;
      $totalMH=0;
      $totalLabor=0;
      $totalMaterial=0;	
	  if($totalBaris > 0) {
        $query="Select SUM(hoursValue) As TotalHours, SUM(laborValue) As TotalLabor, SUM(materialValue) As TotalMaterial, 
                SUM(totalValue) As TotalEOR From RepairDetail Where estimateID='$kodeEstimate'";
        $result=mssql_query($query);
        while($arr=mssql_fetch_array($result)) {
	      $DPP=$arr[3];
	 	  $totalMH=$arr[0];
		  $totalLabor=$arr[1];
		  $totalMaterial=$arr[2]; 
	    }			
	    mssql_free_result($result);
	    $do="Update Repairheader Set nilaiDPP=$DPP, totalHour=$totalMH, totalLabor=$totalLabor, totalMaterial=$totalMaterial 
		     Where estimateID='$kodeEstimate' ";
	    $res_exec=mssql_query($do);					
	  }			
	} /* end of whattodo: Set as Draft */	
	
	if($to_do == 'SUBMIT' && $isFalse==0) {
	  $query = "Select currency From m_RepairPriceList_Header Where priceCode = '$priceCode'";
      $resl = mssql_query($query);
      if(mssql_num_rows($resl) > 0) {
	    $col = mssql_fetch_array($resl);
		$currency = $col['currency'];
      } else { 
	      $currency = 'IDR'; 
		}	  
	  mssql_free_result($resl);
	        
	  $qry="Select estimateID From repairHeader Where bookID='$kodeBooking' And containerID='$keywrd';";
	  $rsl=mssql_query($qry);
	  if(mssql_num_rows($rsl) > 0) {
	    $col=mssql_fetch_array($rsl);
		$NoDraft=$col['estimateID'];
	  } else {
		  $NoDraft = '';
		}
	  mssql_free_result($rsl);
	  
	  //echo $NoDraft.'<br>';	  
	  if(substr($NoDraft,0,3) == 'DRF') {	
	    $do="If Exists(Select * From repairHeader Where bookID='$kodeBooking' And containerID='$keywrd' and estimateID Like 'DRF%') Begin
   		       Declare @NewEOR VarChar(30),@LastIndex Int, @Periode VarChar(8), @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		       Set @ToDay_ = FORMAT(GETDATE(), 'yyyy-MM-dd'); 
                  				
		       Select @Periode=CONCAT('REP', SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),1,1), SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),3,6)); 
		       Set @Keywrd=CONCAT(@Periode,'%');
				
		       If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
		         Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                 Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
			     Set @NewEOR=CONCAT(@Periode, '-', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
		  	   End Else Begin 
			         Insert Into logKeyField(keyFName, lastNumber) Values(@Periode, 1);
				     Set @NewEOR=CONCAT(@Periode, '-1');
				   End;
				  
			   Delete From RepairDetail Where estimateID='$NoDraft'; 
			   Update RepairHeader Set estimateID=@NewEOR, statusEstimate='SUBMIT',estimateDate=CONVERT(VARCHAR(10), GETDATE(), 126) Where estimateID='$NoDraft'; 
			   Update containerJournal Set isRepair=1, CCleaning=CONVERT(VARCHAR(10), GETDATE(), 126) Where noContainer='$keywrd' And bookInID='$kodeBooking' And isRepair=0; 
			   Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Draft to EOR ','$keywrd',' ','$kodeBooking'));
			 End; ";					 			   					
	  }
	  else {
	    $do="If Not Exists(Select * From repairHeader Where bookID='$kodeBooking' And containerID='$keywrd') Begin
		       Declare @NewEOR VarChar(30),@LastIndex Int, @Periode VarChar(8), @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		       Set @ToDay_ = FORMAT(GETDATE(), 'yyyy-MM-dd'); 
                  				
		       Select @Periode=CONCAT('REP', SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),1,1), SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),3,6)); 
		       Set @Keywrd=CONCAT(@Periode,'%');
				
		       If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
			     Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                 Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
			    Set @NewEOR=CONCAT(@Periode, '-', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
			   End Else Begin 
			         Insert Into logKeyField(keyFName, lastNumber) Values(@Periode, 1);
				     Set @NewEOR=CONCAT(@Periode, '-1');
				   End;
				  
			   Insert Into RepairHeader(estimateID, containerID, estimateDate, nilaiDPP, totalHour, totalLabor, totalMaterial, isValid, 
			                            totalOwner, totalUser, laborRate, bookID,isAVRepair,exchangeRate, currencyAs, groupRepair, statusEstimate, repairPriceCode) 
			                     Values(@NewEOR, '$keywrd', CONVERT(VARCHAR(10), GETDATE(), 126), 0,0,0,0,1,0,0, $laborDepo, '$kodeBooking', 0,0,'$currency', '', 
			   		                    'SUBMIT','$priceCode');
			   Update containerJournal Set isRepair=1, CCleaning=CONVERT(VARCHAR(10), GETDATE(), 126) Where noContainer='$keywrd' And bookInID='$kodeBooking' And isRepair=0; 
			   Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                           Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Create EOR ','$keywrd',' ','$kodeBooking')); 			 
			 End; ";				   			
      }			
	  //echo $do;
      $res_exec=mssql_query($do);	
	  
	  $query="Select estimateID From RepairHeader Where containerID='$keywrd' And bookID='$kodeBooking'";
	  $result=mssql_query($query);
	  if(mssql_num_rows($result)> 0) {
	    $col = mssql_fetch_array($result);
		$kodeEstimate = $col['estimateID'];
		
		$do="Delete From RepairDetail Where estimateID='$kodeEstimate'; ";
		$res_exec = mssql_query($do);

		$index=1;
 	    $withCleaning=0;
	    $totalCleaning=0;
	    for($i=0; $i<$totalBaris; $i++) {
		  if(trim($loc[$i]) != '' && $kodeEstimate != '') {
	        if($party[$i] == 'O') { $partnum=0; }
	        if($party[$i] == 'U') { $partnum=1; }	
	        if($party[$i] == 'T') { $partnum=2; }	
  		    if($chk[$i] == 'on') { $isFlag = 0; }
		    else {$isFlag = 1; }
			
		    $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                          widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                     Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                             $Width[$i], $qty[$i],  $partnum, $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', $isFlag); ";				  
		    $res_exec=mssql_query($do);  			
		  
		    if($repair[$i] == 'WW' || $repair[$i] == 'CC' || $repair[$i] == 'DW' || $repair[$i] == 'SC') {
		      $withCleaning++;  
			  $totalCleaning=$totalCleaning +$total[$i];
			  $cleaning=$repair[$i];
            }			  
		  }
		  $index++;			
	    }  
	  }  
      mssql_free_result($result);

      if($withCleaning > 0) {
	    $tglCleaning = date('Y-m-d');
   	    $postTgl = trim(str_ireplace("-","",$tglCleaning));
	    $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
 	    if($cleaning == "WW") { $remark = "LIGHT CLEANING"; }
	    if($cleaning == "DW") { $remark = "MEDIUM CLEANING"; }
	    if($cleaning == "CC") { $remark = "HEAVY CLEANING"; }
	    if($cleaning == "SC") { $remark = "SPECIAL CLEANING"; }

        $do = "If Not Exists(Select * From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking') Begin
                 Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		         Set @ToDay_ = '$tglCleaning'; 
		         Set @Keywrd=CONCAT('$postTgl','%');
				
		         If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
		           Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                   Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
			       Set @NewDraft=CONCAT('$postTgl', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
			     End Else Begin 
				       Insert Into logKeyField(keyFName, lastNumber) Values('$postTgl', 1);
				       Set @NewDraft=CONCAT('$postTgl', '.1');
				     End;
			  
                 Insert Into CleaningHeader(cleaningID, containerID, cleaningDate, nilaiDPP, bookID, invoiceNumber) 
                                     Values(@NewDraft, '$keywrd', '$tglCleaning', '$totalCleaning', '$kodeBooking', '');			 
                 Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                     Values(@NewDraft, '', 0, '$remark', '$cleaning');										
			   End Else Begin
				     Declare @IndexRec VarChar(30);
				     Select @IndexRec = cleaningID From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking';
					 Update CleaningDetail Set repairID = '$cleaning', Remarks = '$remark' Where cleaningID = @IndexRec;
					 Update CleaningHeader Set cleaningDate = '$tglCleaning' Where cleaningID = @IndexRec;
				   End; 
				   
			   Update containerJournal Set cleaningType='$cleaning', CCleaning='$tglCleaning' Where bookInID='$kodeBooking' And NoContainer='$keywrd';";
	    $res_exec=mssql_query($do);		  
      }
	}		
	
	if($to_do == 'RESUBMIT' && $isFalse==0) {
	  $query="Select estimateID, FORMAT(estimateDate,'yyyy-MM-dd') As estimateDate From RepairHeader Where containerID='$keywrd' And bookID='$kodeBooking'";
	  $result=mssql_query($query);
	  if(mssql_num_rows($result) > 0) {
		$col = mssql_fetch_array($result);
		$kodeEstimate = $col['estimateID'];
      }		  
      mssql_free_result($result);
	  
	  $do="Delete From repairDetail Where estimateID='$kodeEstimate'";
	  $res_exec=mssql_query($do);

	  $index=1;
	  $withCleaning=0;
	  $totalCleaning=0;
	  for($i=0; $i<$totalBaris; $i++) {
		if(trim($loc[$i]) != '' && $kodeEstimate != '') {
	      if($party[$i] == 'O') { $partnum=0; }
	      if($party[$i] == 'U') { $partnum=1; }	
	      if($party[$i] == 'T') { $partnum=2; }		  
		  if($_GET["chk"][$i] == 'on') { $isFlag = 0; }
		  else {$isFlag = 1; }
		  $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                           widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                    Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                           $Width[$i], $qty[$i], $partnum, $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', $isFlag); ";				  
		  $res_exec=mssql_query($do);  
		  
		  if($repair[$i] == 'WW' || $repair[$i] == 'CC' || $repair[$i] == 'DW' || $repair[$i] == 'SC') {
			$withCleaning++;  
			$totalCleaning=$totalCleaning +$total[$i];
			$cleaning=$repair[$i];
          }			  
		}		
		$index++;
	  }	
	  
      if($withCleaning > 0) {
	    $tglCleaning = date('Y-m-d');
   	    $postTgl = trim(str_ireplace("-","",$tglCleaning));
	    $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
 	    if($cleaning == "WW") { $remark = "LIGHT CLEANING"; }
	    if($cleaning == "DW") { $remark = "MEDIUM CLEANING"; }
	    if($cleaning == "CC") { $remark = "HEAVY CLEANING"; }
	    if($cleaning == "SC") { $remark = "SPECIAL CLEANING"; }
		
        $do = "If Not Exists(Select * From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking') Begin
     	         Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
		         Set @ToDay_ = '$tglCleaning'; 
		   	     Set @Keywrd=CONCAT('$postTgl','%');
				
		         If Exists(Select * From logKeyField Where keyFName Like @Keywrd) Begin 
				   Select @LastIndex= lastNumber+1 From logKeyField Where keyFName Like @Keywrd;
                   Update logKeyField Set lastNumber= lastNumber+1 Where keyFName Like @Keywrd;
				   Set @NewDraft=CONCAT('$postTgl', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastIndex))));
				  
			     End Else Begin 
				       Insert Into logKeyField(keyFName, lastNumber) Values('$postTgl', 1);
				       Set @NewDraft=CONCAT('$postTgl', '.1');
				     End;
			  
                 Insert Into CleaningHeader(cleaningID, containerID, nilaiDPP, bookID, invoiceNumber) 
                                     Values(@NewDraft, '$keywrd', '$totalCleaning', '$kodeBooking', '');			 
                 Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                     Values(@NewDraft, '', 0, '$remark', '$cleaning');
										 
			   End Else Begin
				     Declare @IndexRec VarChar(30);
			 		 Select @IndexRec = cleaningID From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking';
					 Update CleaningDetail Set repairID = '$cleaning', Remarks = '$remark' Where cleaningID = @IndexRec;					
				   End; 			
 			   
			   Update containerJournal Set cleaningType='$cleaning' Where bookInID='$kodeBooking' And NoContainer='$keywrd';";
 	    $res_exec = mssql_query($do);		  
      }	  
    }	
  } 
  // -- end of: WhatToDo -- //

  /* if is Valid to Edit or Create EOR for current Container said in $_GET */
  if(trim($keywrd != '') && trim($kodeBooking != '')) {	    
    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID, 
	        Format(a.tanggalSurvey, 'yyyy-MM-dd') As surveyDate, a.Surveyor,
			b.principle, b.vessel, b.consignee, isNull(e.LaborRate,0) As laborRate, IsNull(e.repairPriceCode,'') As repairPriceCode, 0 As currRepair, 
			e.estimateID, Format(e.estimateDate, 'yyyy-MM-dd') As estimateDate, statusEstimate  
            From containerJournal a 
		    Inner Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner Join containerLog c On c.ContainerNo=a.NoContainer 
			Left Join m_Customer d On d.custRegID=b.principle 
			Left Join RepairHeader e On e.containerID=a.NoContainer And e.bookID=a.bookInID
		    Where (a.NoContainer='$keywrd') And (a.bookInID='$kodeBooking')";
	$result=mssql_query($query);
	//echo $query;
	if(mssql_num_rows($result) == 1) {
	  $arr=mssql_fetch_array($result);	
      $sizeCode=$arr[2].'/'.$arr[3].'/'.$arr[4];
	  $size=$arr[2];
	  $tipe=$arr[3];
	  $height=$arr[4];
	  $tglMasuk=$arr["DateIn"];
	  $tglSurvey=$arr[7];
	  $surveyor=$arr[8];
	  $principle=$arr[9];
	  $vessel=strtoupper($arr[10]);
	  $consignee=$arr[11]; 
	  if($arr['laborRate'] > 0) { $labour=$arr['laborRate']; }
	  if(trim($arr['repairPriceCode']) != '') { $priceCode=$arr['repairPriceCode']; }
	  //$currency=$arr["currRepair"];
      $kodeEstimate=$arr["estimateID"];
      $tanggalEOR=$arr["estimateDate"];	 
      $statusEstimate=$arr["statusEstimate"];	  
	}	  
	mssql_free_result($result);
	
	$have_principle=haveCustomerName($principle);
	$have_consignee=haveCustomerName($consignee);
	$principle=$have_principle;
	$consignee=$have_consignee;

    $DPP=0;
    $totalMH=0;
    $totalLabor=0;
    $totalMaterial=0;	
    $query="Select SUM(hoursValue) As TotalHours, SUM(laborValue) As TotalLabor, SUM(materialValue) As TotalMaterial, 
            SUM(totalValue) As TotalEOR From RepairDetail Where estimateID='$kodeEstimate' And isFlag=0";
    $result=mssql_query($query);
    while($arr=mssql_fetch_array($result)) {
	  $DPP=$arr["TotalEOR"];
	  $totalMH=$arr["TotalHours"];
	  $totalLabor=$arr["TotalLabor"];
	  $totalMaterial=$arr["TotalMaterial"]; 
	}			
	mssql_free_result($result);	 
    
    //if(trim($kodeEstimate) != '' && substr($kodeEstimate,0,3) != 'DRF') 
	if($to_do == 'RESUBMIT' || $to_do == 'SUBMIT')  {	
      $query="Update repairHeader Set nilaiDPP=$DPP, totalHour=$totalMH, totalLabor=$totalLabor, totalMaterial=$totalMaterial Where estimateID='$kodeEstimate'";
	  $result=mssql_query($query);
	  //echo $query;
	}  
	
    if($validateGrid != "View Result" ) {		
	  $query="Select * From RepairDetail Where estimateID='$kodeEstimate'";
	  $result=mssql_query($query);
	  $totalRow=mssql_num_rows($result);
	  if($totalRow > 0 ) {
        $validateGrid="View Result";
	    $totalBaris=$totalRow;
  		
	    for($i=0; $i<$totalRow; $i++) {		
          if(mssql_result($result, $i, 'isFlag') == 0) { $chk[$i]="on"; }
		  else { $chk[$i]=""; }
		  $loc[$i]=mssql_result($result, $i, 'locationID');
		  $part[$i]=mssql_result($result, $i, 'componentID');
          $dmg[$i]=mssql_result($result, $i, 'damageID'); 
		  $repair[$i]=mssql_result($result, $i, 'repairID');
		  $length[$i]=mssql_result($result, $i, 'lengthValue');
		  $Width[$i]=mssql_result($result, $i, 'widthValue');
		  $qty[$i]=mssql_result($result, $i, 'Quantity');
		//$status[$i]=mssql_result($result, $i, 'Remarks');
	  	  $status[$i]='';
		  $mh[$i]=mssql_result($result, $i, 'hoursValue');
          $labor[$i]=mssql_result($result, $i, 'laborValue');		
		  $material[$i]=mssql_result($result, $i, 'materialValue');
		  $total[$i]=mssql_result($result, $i, 'totalValue'); 
		  if(mssql_result($result, $i, 'isOwner') == 0) { $party[$i] = 'O'; }
		  if(mssql_result($result, $i, 'isOwner') == 1) { $party[$i] = 'U'; }
		  if(mssql_result($result, $i, 'isOwner') == 2) { $party[$i] = 'T'; }
	    }
	    mssql_free_result($result);
	  }
	}  
</script>	

<h3 class="w3-lime" style="padding-bottom:4px">&nbsp;&nbsp;Estimate of Repair</h3>
<form id="fEOR" name="myForm" method="get">
  <input type="hidden" name="noCnt" value='<?php echo $keywrd;?>' />
  <input type="hidden" name="kodeBooking" value='<?php echo $kodeBooking;?>' />
  <input type="hidden" name="kodeEstimate" value='<?php echo $kodeEstimate;?>' />
  <input type="hidden" name="size" value='<?php echo $size;?>' />
  <input type="hidden" name="tipe" value='<?php echo $tipe;?>' />
  <input type="hidden" name="height" value='<?php echo $height;?>' />

  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">CONTAINER NUMBER</label></div>
	<div class="w3-quarter" ><?php echo $keywrd;?></div>
	<div class="w3-quarter" >&nbsp;</div>
	<div class="w3-quarter" >&nbsp;</div>
  </div>	  
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">SIZE/TYPE/HEIGHT</label></div>
	<div class="w3-quarter" ><?php echo $sizeCode;?></div>
	<div class="w3-quarter" ><label class="w3-text-grey">HAMPARAN IN EVENT</label></div>
	<div class="w3-quarter" ><?php echo $tglMasuk;?></div>
  </div>	
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">EOR NUMBER</label></div>
    <div class="w3-quarter" ><?php echo $kodeEstimate;?>&nbsp;</div>
	<div class="w3-quarter" ><label class="w3-text-grey">EOR DATE</label></div>
    <div class="w3-quarter" ><?php echo $tanggalEOR;?>&nbsp;</div>
  </div>	
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">SHIPPING LINE</label></div>
    <div class="w3-quarter" ><?php echo $principle;?>&nbsp;</div>	
	<div class="w3-quarter" ><label class="w3-text-grey">CONSIGNEE/USER</label></div>
	<div class="w3-quarter" ><?php echo substr($consignee,0,25).'..';?>&nbsp;</div>	
  </div>	
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">EX. VESSEL VOYAGE</label></div>
    <div class="w3-quarter" ><?php echo $vessel;?>&nbsp;</div>
	<div class="w3-twoquarter" ></div>
  </div>	
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">SURVEY DATE</label></div>
    <div class="w3-quarter" ><?php echo $tglSurvey;?>&nbsp;</div>	
	<div class="w3-quarter" ><label class="w3-text-grey">SURVEYOR NAME</label></div>
    <div class="w3-quarter" ><?php echo $surveyor;?>&nbsp;</div>	
  </div>	
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <?php
    if($statusEstimate == 'SUBMIT' || $statusEstimate == 'APPROVE') {	
  ?>
      <div class="w3-row-padding">   
       <div class="w3-quarter"><label class="w3-text-grey">DPP</label></div>
       <div class="w3-quarter" style="text-align:right"><?php echo number_format($DPP,2,",",".");?>&nbsp;</div>	
	   <div class="w3-quarter"><label class="w3-text-grey">TOTAL HOUR</label></div>
       <div class="w3-quarter" style="text-align:right"><?php echo number_format($totalMH,2,",",".");?>&nbsp;</div>	
      </div>	
	  <div class="height-5" style="border-top:1px dotted #ddd"></div>   
      <div class="w3-row-padding">   
       <div class="w3-quarter"><label class="w3-text-grey">TOTAL MATERIAL</label></div>
       <div class="w3-quarter" style="text-align:right"><?php echo number_format($totalMaterial,2,",",".");?>&nbsp;</div>	
	   <div class="w3-quarter"><label class="w3-text-grey">TOTAL LABOR</label></div>
       <div class="w3-quarter" style="text-align:right"><?php echo number_format($totalLabor,2,",",".");?>&nbsp;</div>	
      </div>	
   <?php
    }
   ?>
   
<!--	
      <label class="w3-text-teal">Repair Group</label>
	  <script language="php">
	    echo '<select name="groupRepair" class="w3-select w3-border" required>';  
		echo '<option value=" ">&nbsp;-- Repair Group --</option>';
		$query="Select Distinct groupID From m_GroupRepair Order By groupID";
		$result=mssql_query($query);		
		while($arr=mssql_fetch_array($result)) { 
		  if($arr[0]==$groupRepair) { echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[0].'</option>'; }
		  else { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[0].'</option>'; }
	    }		
		echo '</select>';
	  </script>
-->	  
  <div class="height-5" style="border-top:1px dotted #ddd"></div>
  <div class="w3-row-padding">   
    <div class="w3-half">
	  <label>PRICE CODE</label>
 	  <?php
	    if($statusEstimate == 'APPROVE') {
		  echo '<input type="text" class="style-input style-border" readonly name="priceCode" value="'.$priceCode.'" />';
		}
		else {
	  ?>
	      <select name="priceCode" required class="style-select" required>
	   <?php
	         $cmd = "Select DISTINCT a.priceCode From m_RepairPriceList a Inner Join m_RepairPriceList_Header b On b.priceCode=a.priceCode Order By priceCode";
		     $resl = mssql_query($cmd);
		     while($arrFetch = mssql_fetch_array($resl)) 
		    {		      
		      if($priceCode == $arrFetch[0]) { echo '<option selected value="'.$arrFetch[0].'">&nbsp;'.strtoupper($arrFetch[0]).'</option>'; }
		      else { echo '<option value="'.$arrFetch[0].'">&nbsp;'.strtoupper($arrFetch[0]).'</option>'; }
		    }	

            mssql_free_result($resl);		
	   ?>	  
	      </select>
	  <?php
	    }
	  ?>
	</div>
	<div class="w3-half">
      <label>LABOUR RATE</label>
	  <?php
	    if($statusEstimate == 'APPROVE') {
	  ?>	  
	  <input type="text" class="style-input" readonly name="laborDepo" style="text-align:right" value='<?php echo $labour?>' />
	  <?php
	    } else {
	  ?>
	  <input type="text" class="style-input style-border" name="laborDepo" style="text-align:right" required onkeypress="return isNumber(event)" value='<?php echo $labour?>' />  
      <?php
        }
      ?>		
	</div>	
  </div>
  <div class="height-20"></div>
	
    <fieldset style="border-color:#ddd">	
	  <input type="hidden" name="whatToDo" value="" />
	  <table>
	    <tr>	    
		 <td style="border:0;">
  	      <?php
	        /* status APPROVE, not allowed for editing */
	        if($statusEstimate != 'APPROVE') 
	        {
		  ?>
  		       <input type="button" class="w3-button w3-border" name="newRow" style="background:none" onclick=addRow_mine("dataTable") value="APPEND ROW" />
		       <input type="button" class="w3-button w3-border" name="delRow" style="background:none"onclick=deleteRow_mine('dataTable') value="DELETE MARKED ROW" />		  
		       <button type="submit" class="w3-button w3-border" style="background:none" 
			         value="CALCULATE" name="calculate" onclick='this.form.whatToDo.value = this.value;'>REVIEW</button>
					 
          <?php
  		      if($statusEstimate == 'SUBMIT' || $statusEstimate == 'DRAFT')
			  {
		  ?>
		        <button type="submit" class="w3-button w3-border" style="background:none"				
			            value="RESUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>UPDATE</button>			 

          <?php		  
              }	
              if($statusEstimate == '')
              {				  
          ?>		  
		       <button type="submit" class="w3-button w3-border" style="background:none"				  
			         value="DRAFT" name="draft" onclick='this.form.whatToDo.value = this.value;'>SET AS DRAFT</button>
		  <?php
		      }
			  if($statusEstimate == 'DRAFT' || $statusEstimate == '')
			  {	  
          ?>		  
		  
		       <button type="submit" class="w3-button w3-border" style="background:none"				 
			           value="SUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>SET AS EOR</button>			 
						
          <?php
		      }
		    }
			if($statusEstimate == 'SUBMIT' || $statusEstimate == 'APPROVE')
			{	

/*
<a class="w3-button w3-border w3-light-grey" 
			         href="print_eor.php?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'" target="_blank">Print EOR and Image</a>&nbsp;
*/		
             echo '<div class="w3-dropdown-hover">
			        <button class="w3-button w3-border" style="background:none">PREVIEW</button>
					<div class="w3-dropdown-content w3-bar-block w3-border">
					 <a class="w3-bar-item w3-button" href="print_eor?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'" target="_blank">Estimate (Complete)</a>
					 <a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'" target="_blank">Estimate Only</a>
					 <a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&party=0" target="_blank">Estimate (Owner)</a>
					 <a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&party=1" target="_blank">Estimate (User)</a>
					 <a class="w3-bar-item w3-button" href="viewPh?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=2" target="_blank">Image(Before Repair)</a>
					 <a class="w3-bar-item w3-button" href="viewPh?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=3" target="_blank">Image(After Repair)</a>
					 <a class="w3-bar-item w3-button" href="viewPh?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=1" target="_blank">Image(Complete)</a>
					</div>
			       </div>';
		    }
          ?>		  
         </td>
		</tr>
      </table>		  
	</fieldset>		

	<div class="height-10"></div>
	<table id="dataTable" class="w3-table-all" style="margin:0 auto">
      <thead>
	     <tr>
		   <th></th>
		   <th>LOC</th>
		   <th>PART</th>
		   <th>DMG</th>
		   <th>REPAIR</th>
		   <th>LENGTH</th>
		   <th>WIDTH</th>
		   <th>QTY</th>
		   <th>PARTY</th>
		   <th>MH</th>
		   <th>LABOR</th>
		   <th>MTRL</th>
		   <th>TOTAL</th>
		   <th>STATUS</th>
	     </tr>
	  </thead>

	  <tbody>
         <?php
		   if($validateGrid == "View Result") {
		     for($i=0; $i<$totalBaris; $i++) {
			   $filledRow=0;	 
			   if(trim($loc[$i])!= '') {			
                 $filledRow++;		   
         ?>
		 
		 <tr>		 
		   <td>
		     <?php
			   if($chk[$i] == "on") { echo '<input type="checkbox" name="chk[]" checked="checked" style="margin-top:8px">'; }
			   else { echo '<input type="checkbox" name="chk[]" checked="unchecked" style="margin-top:8px">'; }
			 ?>
		     </td>
		   <td><input type="text" class="style-input style-border" name="loc[]" style="text-transform:uppercase;" style="width:100px"
		       value='<?php echo $loc[$i];?>'></td>
		   <td><input type="text" class="style-input style-border" name="part[]"  
		       value='<?php echo $part[$i];?>' style="text-transform:uppercase;" style="width:40px"></td>
		   <td><input type="text" class="style-input style-border" name="dmg[]"  
		       value='<?php echo $dmg[$i];?>' style="text-transform:uppercase;" style="width:50px"></td>
		   <td><input type="text" class="style-input style-border" name="repair[]"  
		       value='<?php echo $repair[$i];?>' style="text-transform:uppercase;" style="width:40px"></td>
		   <td><input type="text" class="style-input style-border" name="length[]"  
		       value='<?php echo $length[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right"></td>
		   <td><input type="text" class="style-input style-border" name="Width[]"  
		       value='<?php echo $Width[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right"></td>
		   <td><input type="text" class="style-input style-border" name="qty[]"  
		       value='<?php echo $qty[$i];?>' onkeypress="return isNumber(event)" style="width:30px;text-align:right"></td>
		   <td><select id="party" class="style-select" name="party[]" style="width:40px">
		        <?php 
				  if($party[$i] == 'O') { echo '<option selected value="O">O</option>'; }
				  else { echo '<option value="O">O</option>'; }
				  if($party[$i] == 'U') { echo '<option selected value="U">U</option>'; }
				  else { echo '<option value="U">U</option>'; }
				  if($party[$i] == 'T') { echo '<option selected value="T">T</option>'; }
				  else { echo '<option value="T">T</option>'; }				  
				?>
			    </select></td>

		   <td><input type="text" class="style-input style-border" readonly name="mh[]" value='<?php echo number_format($mh[$i],2,",",".");?>' style="width:40px;text-align:right" /></td>				
		   <td><input type="text" class="style-input style-border" readonly name="labor[]" value='<?php echo number_format( $labor[$i],2,",",".");?>' style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="mtrl[]" value='<?php echo number_format( $material[$i],2,",",".");?>' style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="subTotal[]" value='<?php echo number_format( $total[$i],2,",",".");?>' style="width:80px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="status[]" value='<?php echo $status[$i];?>' style="width:60px" />
		       <input type="hidden" name="deskripsi[]" value='<?php echo $deskripsi[$i];?>'></td></tr> 			
		</tr>
		
        <?php
               }
			 }		 
		   } 
		   
	       /* status SUBMIT, not allowed for editing */
	       if($statusEstimate != 'APPROVE') {
			 for($i=$filledRow+1; $i<=9; $i++) {	 		   
        ?>
		
  	    <tr>
		   <td><input type="checkbox" checked="checked" name="chk[]" style="margin-top:8px" /></td>
		   <td><input type="text" class="style-input style-border" name="loc[]" style="text-transform:uppercase;" style="width:100px" /></td>
		   <td><input type="text" class="style-input style-border" name="part[]" style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input style-border" name="dmg[]" style="text-transform:uppercase;" style="width:50px" /></td>
		   <td><input type="text" class="style-input style-border" name="repair[]" style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input style-border" name="length[]" onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" name="Width[]" onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" name="qty[]" onkeypress="return isNumber(event)" style="width:30px;text-align:right" /></td>
		   <td><select id="party" class="style-select" name="party[]" style="width:40px">
			     <option value="O">O</option>
			     <option value="O">U</option>
			     <option value="O">T</option>
			    </select></td>
		   <td><input type="text" class="style-input style-border" readonly name="mh[]" style="width:40px;text-align:right" /></td>				
		   <td><input type="text" class="style-input style-border" readonly name="labor[]" style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="mtrl[]" style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="subTotal[]" style="width:80px;text-align:right" /></td>
		   <td><input type="text" class="style-input style-border" readonly name="status[]" style="width:60px" />
		       <input type="hidden" name="deskripsi[]" value='<?php echo $deskripsi[$i];?>'></td></tr> 			
		</tr>
        
		<?php
		    }
		   }
		?>
		
	  </tbody>
	</table>

</form>

<script language="php">	
  }
  mssql_close($dbSQL);
</script>

<script type="text/javascript">
  $(document).ready(function(){    
    $("#fEOR").submit(function(event){
      event.preventDefault();	
      $('#loader-icon').show();	  
	  var formValues = $(this).serialize();
      $.get("estimate.php", formValues, function(data){ 
	   $("#mnr_form").html(data); 
	   $('#loader-icon').hide();	
	  });
    });  
  });	

function doPrintEOR(kodeEstimate) {
    var w=window.open("print_eor.php?id="+kodeEstimate); 
 	$(w.document.body).html(response);  	
}  
</script>