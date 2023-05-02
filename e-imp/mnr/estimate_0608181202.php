<script language="php">
  session_start();    
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");
  
  $kodeBooking="";
  $keywrd=""; /* instead Container Number */
  $to_do="";
  $noCnt="";
  $kodeEstimate="";
  $curr_lama="";
  
  if(isset($_GET['kodeBooking']))    { $kodeBooking=$_GET['kodeBooking']; } 
  if(isset($_GET['noCnt']))          { $keywrd=strtoupper($_GET['noCnt']); }  
  if(isset($_POST['kodeEstimate']))  { $kodeEstimate=$_POST['kodeEstimate']; }  
  if(isset($_POST['kodeBooking']))   { $kodeBooking=$_POST['kodeBooking']; } 
  if(isset($_POST['noCnt']))         { $keywrd=strtoupper($_POST['noCnt']); }  
  if(isset($_POST['laborDepo']))     { $laborDepo=$_POST['laborDepo']; }
  else { $laborDepo=12500; }  
  if(isset($_POST['priceCode']))     { $priceCode=$_POST['priceCode']; }
  if(isset($_POST['whatToDo']))      { $to_do=strtoupper($_POST["whatToDo"]); }

  $isType='DV';
  $cleaning_arr=array("WW","DW","CC","SC","SW");

  $query = "Select currency From m_RepairPriceList_Header Where priceCode='$priceCode'";  
  $resl = mssql_query($query);
  if(mssql_num_rows($resl) > 0) {
	$col = mssql_fetch_array($resl);
	$currency = $col['currency'];
  } else { $currency="IDR"; }	  
  mssql_free_result($resl);    
  
  // -- Start of: WhatToDo: CALCULATE/SET AS DRAFT/SUBMITTED -- //      
  if($to_do !="" && $keywrd !="") {    
    $totalBaris=0;	
	for($i=0; $i<count($_POST['loc']); $i++) {		
	  if(isset($_POST['loc'][$i])) {
        $temp=str_replace(" ","",$_POST['loc'][$i]);
        if($temp!="") { $totalBaris++; }  	
	  }	
    }	
	
	if($kodeEstimate!="") {
	  $qry="Select repairPriceCode From RepairHeader Where estimateID='$kodeEstimate' ";
      $rsl=mssql_query($qry);
	  if(mssql_num_rows($rsl)>0) { 
	    $col=mssql_fetch_array($rsl);
		$curr_lama=$col['repairPriceCode'];
	  } else { $curr_lama=$priceCode; }
      mssql_free_result($rsl);	  
	}
	
	for($i=0; $i<$totalBaris; $i++) {
	  $validateGrid="View Result";  
	  $chk[$i]=$_POST['chk'][$i];
	  $size=$_POST['size'];
      $tipe=$_POST['tipe'];		
	  $height=$_POST['height'];
	   
	  $isFalse=0; /* var for counter */
				  
	  $loc[$i]=strtoupper($_POST['loc'][$i]);  
	  $special_loc=array("LG","LH","RG","RH","FG","FH","DG","DH");
	  if(in_array(substr($loc[$i],0,2), $special_loc)) {
		$search_loc=substr($loc[$i],0,2);	
	  } else { 
	      $search_loc=substr($loc[$i],0,1); 
		}

	  $part[$i]=strtoupper(str_replace(' ','',$_POST['part'][$i]));
	  if($part[$i]=="") { $part_ = '%'; }
	  else { $part_ = $part[$i].'%'; }
		  
      $dmg[$i]=strtoupper($_POST['dmg'][$i]); 
	  $repair[$i]=strtoupper($_POST['repair'][$i]);
		  
	  $length[$i]=$_POST['length'][$i];
	  if($length[$i] =='') { $length[$i] = 0; }
		  
	  $Width[$i]=$_POST['Width'][$i];
	  if($Width[$i] =='') { $Width[$i] = 0; }
		  
	  $qty[$i]=$_POST['qty'][$i];
	  if($qty[$i] =='') { $qty[$i] = 0; }
	  
  	  $party[$i]=$_POST['party'][$i];      
	  $status[$i]=$_POST['status'][$i];	 

	  $part_lama="";
	  $loc_lama="";	  
	  $qty_lama=-1;	
	  $party_lama="";  
	  $act_lama="";
	  $dmg_lama="";
	  $length_lama=-1;
	  $witdh_lama=-1;	  

	  if($status[$i]=="REVIEWED" && $kodeEstimate !="") {		
	    $indexDetail=$i +1;
		$qry="Select isOwner, Quantity, repairID, lengthValue, widthValue, damageID, componentID, locationID 
		      From   RepairDetail   
		      Where  estimateID='$kodeEstimate' And idItem=$indexDetail ";
		$rsl=mssql_query($qry);
//		echo $qry;
		if(mssql_num_rows($rsl) > 0) {
		  $col=mssql_fetch_array($rsl);
		  $loc_lama=$col['locationID'];
		  $part_lama=$col['componentID'];		  
		  $dmg_lama=$col['damageID'];		
          $act_lama=$col['repairID'];				  
		  $length_lama=$col['lengthValue'];
		  $width_lama=$col['widthValue'];          
		  $qty_lama=$col['Quantity'];
		  if($col['isOwner']==0) { $party_lama="O"; }
		  if($col['isOwner']==1) { $party_lama="U"; }
		  if($col['isOwner']==2) { $party_lama="T"; }	
		}	
		mssql_free_result($rsl);
	  }

	  if(trim($loc_lama) !="" && $status[$i]=="REVIEWED") {
        if(trim($loc_lama) != $loc[$i]) { $status[$i]="NEED REVIEW"; }	          		 
	  }	
	  if(trim($part_lama) !="" && $status[$i]=="REVIEWED") {
        if(trim($part_lama) != $part[$i]) { $status[$i]="NEED REVIEW"; }	          		 
	  }		  
	  if(trim($dmg_lama) !="" && $status[$i]=="REVIEWED") {
        if(trim($dmg_lama) != $dmg[$i]) { $status[$i]="NEED REVIEW"; }	          		 
	  }		  
	  if(trim($act_lama) !="" && $status[$i]=="REVIEWED") {
        if(trim($act_lama) != $repair[$i]) { $status[$i]="NEED REVIEW"; }	          		 
	  }	  
 	  if($length_lama !=-1 && $status[$i]=="REVIEWED") {
	    if($length_lama != $length[$i]) { $status[$i]="NEED REVIEW"; }		   	  
	  }
 	  if($width_lama !=-1 && $status[$i]=="REVIEWED") {
	    if($width_lama != $Width[$i]) { $status[$i]="NEED REVIEW"; }		   	  
	  }	  
 	  if($qty_lama !=-1 && $status[$i]=="REVIEWED") {
	    if($qty_lama != $qty[$i]) { $status[$i]="NEED REVIEW"; }		   	  
	  }
	  if(trim($party_lama) !="" && $status[$i]=="REVIEWED") {	
        if(trim($party_lama) != $party[$i]) { $status[$i]="NEED REVIEW"; }		  
	  }	  
	  if(trim($curr_lama) !="" && $status[$i]=="REVIEWED") {
        if(trim($curr_lama) != $priceCode) { $status[$i]="NEED REVIEW"; }	   		
	  }	

		if($loc[$i] !="" && $repair[$i] !="" &&  $qty[$i] !="" && $status[$i]!="REVIEWED") {
		  $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".$size.'%'."' And 
		         (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		          And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].")"; 
		  $result=mssql_query($query);	
		  $rows=mssql_num_rows($result);			  
		  
		  if($rows != 1) {		    
		    mssql_free_result($result);
			$query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cWidth=".$length[$i]." and cLength=".$Width[$i].") ;";	   
		    $result=mssql_query($query);	
		    $rows = mssql_num_rows($result);		
		  }

		  if($rows != 1) {
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].") And cQty=".$qty[$i]."; ";
		    $result=mssql_query($query);	
		    $rows=mssql_num_rows($result);		
		  }
		  
			
		  if($rows != 1) {
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList Where priceCode='$priceCode' And isType Like '$isType' And unitSize Like '".$size.'%'."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cWidth=".$length[$i]." and cLength=".$Width[$i].") And cQty=".$qty[$i]."; ";
			$result=mssql_query($query);	
			$rows=mssql_num_rows($result);		
		  }	
//echo $query;
		  if($rows==1) {
		    $arr=mssql_fetch_array($result);
			$deskripsi[$i]=$arr["Description"];
			
		    if($arr["isMulti"]==0) { 
		  	  $mh[$i]=$arr["MH"];
			  if(in_array($repair[$i], $cleaning_arr) && $arr["materialValue"]>1000) {
			    if($size=="20") { 
				$labor[$i]=10000;	  				  
				}
			    else { 
				  $labor[$i]=15000;	  				  
				}				
			     $material[$i]=$arr["materialValue"]-$labor[$i];
				//$material[$i]=$arr["materialValue"];
			  }
              else {			  
                $labor[$i]=$mh[$i]*$laborDepo;			
			    $material[$i]=$arr["materialValue"];
			  }	
			  $total[$i]=$labor[$i]+ $material[$i];  
		    }

		    if($arr["isMulti"]==1) { 
			  $mh[$i]=$arr["MH"] *$qty[$i];
              $labor[$i]=$mh[$i]*$laborDepo;			
			  $material[$i]=$arr["materialValue"] *$qty[$i];
			  $total[$i]=$labor[$i] +$material[$i];  
		    }
		  } else {
		      $isFalse++;  
  	          $deskripsi[$i]="Tidak ditemukan data yang sesuai pada Master Repair";
		      $mh[$i]=0;
              $labor[$i]=0;			
		      $material[$i]=0;
		      $total[$i]=0;  
		    }
		  mssql_free_result($result);
	    }
 	    else {
		  $nat[$i]=$_POST['isNAT'][$i];	  
	      $mh[$i]=$_POST['mh'][$i];	  
		  $labor[$i]=$_POST['labor'][$i];	
	      $material[$i]=$_POST['mtrl'][$i];	
		  $total[$i]=$_POST['subTotal'][$i];			
          $deskripsi[$i]=$_POST['deskripsi'][$i];		
	    }	  
		
	 /* end of validation checkbox*/
    }	/* end of looping */		

    if($to_do=="DRAFT" && $isFalse==0) {
	  $do="If Not Exists(Select * From repairHeader Where bookID='$kodeBooking' And containerID='keywrd') Begin
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
	                           Values('".$_SESSION['uid']."', GETDATE(), CONCAT('DRAFT ESTIMATE > ','$keywrd','_','$kodeBooking'));									 
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
	      if($party[$i] =="O") { $partnum=0; }
	      if($party[$i] =="U") { $partnum=1; }	
	      if($party[$i] =="T") { $partnum=2; }			
			
	 	  $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                        widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                 Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                        $Width[$i], $qty[$i], '$party[$i]', $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', 0); ";				  
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
	
	if($to_do=="SUBMIT" && $isFalse==0) {   
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
	  if(substr($NoDraft,0,3) =="DRF") {	
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
	                           Values('".$_SESSION['uid']."', GETDATE(), CONCAT('EXPORT DRFAT TO ESTIMATE > ','$keywrd','_','$kodeBooking'));
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
	                           Values('".$_SESSION['uid']."', GETDATE() ,CONCAT('SUBMIT ESTIMATE > ','$keywrd','_','$kodeBooking')); 			 
			 End; ";				   			
      }			
	  //echo $do;
      $res_exec=mssql_query($do);	
	  
	  $query="Select estimateID From RepairHeader Where containerID='$keywrd' And bookID='$kodeBooking'";
	  $result=mssql_query($query);
	  if(mssql_num_rows($result)> 0) {
	    $col = mssql_fetch_array($result);
		$kodeEstimate = $col['estimateID'];
	  }
	  mssql_free_result($result);
      
	  if($kodeEstimate!="") {
		$index=1;
 	    $withCleaning=0;
	    $totalCleaning=0;
		$mtrlCleaning=0;
	    for($i=0; $i<$totalBaris; $i++) {
		  if($loc[$i] != "") {
	        if($party[$i] =="O") { $partnum=0; }
	        if($party[$i] =="U") { $partnum=1; }	
	        if($party[$i] =="T") { $partnum=2; }	

		    $do="Delete Top(1) From RepairDetail Where estimateID='$kodeEstimate' And idItem=$index";
   		    $res_exec=mssql_query($do);			
			
		    $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                          widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                   Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                          $Width[$i], $qty[$i],  $partnum, $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', 0); ";				  
		    $res_exec=mssql_query($do);  			
		    
			if(in_array($repair[$i], $cleaning_arr)) {
		      $withCleaning++;  
			  $totalCleaning=$totalCleaning +$total[$i];
			  $cleaning=$repair[$i];
			  $mtrlCleaning=$material[$i];
			  $remark=$deskripsi[$i];
            }			  
		  }
		  $index++;			
	    }  		
	  }  
      	  
      if($withCleaning ==1) {
	    $tglCleaning = date('Y-m-d');
   	    $postTgl = trim(str_ireplace("-","",$tglCleaning));
	    $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));		

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
			  
                 Insert Into CleaningHeader(cleaningID, containerID, cleaningDate, nilaiDPP, bookID, invoiceNumber, estimateID) 
                                     Values(@NewDraft, '$keywrd', '$tglCleaning', $totalCleaning, '$kodeBooking', '', '$kodeEstimate');			 
                 Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                     Values(@NewDraft, '', $mtrlCleaning, '$remark', '$cleaning');										
			   End Else Begin
				     Declare @IndexRec VarChar(30);
					 
				     Select @IndexRec = cleaningID From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking';
					 Update CleaningDetail Set repairID = '$cleaning', materialValue=$mtrlCleaning, Remarks = '$remark' Where cleaningID = @IndexRec;
					 Update CleaningHeader Set estimateID='$kodeEstimate', nilaiDPP=$totalCleaning Where cleaningID = @IndexRec;
				   End; 
				   
			   Update containerJournal Set cleaningType='$cleaning' Where bookInID='$kodeBooking' And NoContainer='$keywrd';";
	    $res_exec=mssql_query($do);		  
      }
	}		
	
	if($to_do=="RESUBMIT" && $isFalse==0) {
	  $do="Update RepairHeader Set laborRate=$laborDepo, currencyAs='$currency', repairPriceCode='$priceCode' Where estimateID='$kodeEstimate' ";	  
	  $rslExec=mssql_query($do);

	  $index=1;
	  $withCleaning=0;
	  $totalCleaning=0;
	  $mtrlCleaning=0;
	  for($i=0; $i<$totalBaris; $i++) {	
		if($loc[$i] != "") {
		  $index=$i+1;	
		  
	      if($party[$i] =="O") { $partnum=0; }
	      if($party[$i] =="U") { $partnum=1; }	
	      if($party[$i] =="T") { $partnum=2; }		  

          if($status[$i]!="REVIEWED") {			  
		    $do="Delete Top(1) From RepairDetail Where estimateID='$kodeEstimate' And idItem=$index";
			$res_exec=mssql_query($do);
			
  		    $do="Insert Into RepairDetail(estimateID, idItem, componentID, locationID, damageID, repairID, lengthValue,
                                          widthValue, Quantity, isOwner, hoursValue, laborValue, materialValue, totalValue, Remarks, isFlag) 
                                   Values('$kodeEstimate', $index, '$part[$i]', '$loc[$i]', '$dmg[$i]', '$repair[$i]', $length[$i],
                                          $Width[$i], $qty[$i], $partnum, $mh[$i], $labor[$i], $material[$i], $total[$i], '$deskripsi[$i]', 0); ";				  
		    $res_exec=mssql_query($do);  
			
			if($res_exec) {
			  $do="Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                               Values('".$_SESSION['uid']."', GETDATE() ,CONCAT('RESUBMIT ESTIMATE > ','$kodeEstimate')) "; 			 
	          $res_exec=mssql_query($do); 
			}

		    if(in_array($repair[$i], $cleaning_arr)) {
			  $withCleaning++;  
			  $totalCleaning=$totalCleaning +$total[$i];
			  $mtrlCleaning=$material[$i];
			  $cleaning=$repair[$i];
			  $remark=$deskripsi[$i];
            }			  
		  }	
		}		
	  }	
	  	  
      if($withCleaning ==1) {
	    $tglCleaning = date('Y-m-d');
   	    $postTgl = trim(str_ireplace("-","",$tglCleaning));
	    $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
		
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
			  
                 Insert Into CleaningHeader(cleaningID, containerID, cleaningDate, nilaiDPP, bookID, invoiceNumber, estimateID) 
                                     Values(@NewDraft, '$keywrd', '$tglEstimate', $totalCleaning, '$kodeBooking', '', '$kodeEstimate');			 
                 Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                     Values(@NewDraft, '', $mtrlCleaning, '$remark', '$cleaning');
										 
			   End Else Begin
				     Declare @IndexRec VarChar(30);
			 		 Select @IndexRec = cleaningID From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking';
					 Update CleaningDetail Set repairID = '$cleaning', materialValue=$mtrlCleaning,Remarks = '$remark' Where cleaningID = @IndexRec;					
					 Update CleaningHeader set estimateID='$kodeEstimate', nilaiDPP=$totalCleaning where containerID='$keywrd' And bookID='$kodeBooking';
				   End; 			
 			   
			   Update containerJournal Set cleaningType='$cleaning' Where bookInID='$kodeBooking' And NoContainer='$keywrd';";
 	    $res_exec = mssql_query($do);	
//echo $do;		
      }	  
    }	
  } 
  // -- end of: WhatToDo -- //

  /* if is Valid to Edit or Create EOR for current Container said in $_GET */
  $terms="";
  if($keywrd != '' && $kodeBooking != '') {	$terms="(a.NoContainer='$keywrd') And (a.bookInID='$kodeBooking') "; }
  if($kodeEstimate != '') { $terms="e.estimateID='$kodeEstimate' "; }
 
  if($terms!="") {	    
    $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID, 
	               Format(a.tanggalSurvey, 'yyyy-MM-dd') As surveyDate, a.Surveyor,
			       b.principle, CONCAT(b.vessel,', ',b.voyageID) As vessel, b.consignee, isNull(e.LaborRate,0) As laborRate, IsNull(e.repairPriceCode,'') As repairPriceCode, 
			       0 As currRepair, 
			       e.estimateID, Format(e.estimateDate, 'yyyy-MM-dd') As estimateDate, statusEstimate  
            From   containerJournal a 
		    Inner  Join tabBookingHeader b On b.bookID=a.bookInID
		    Inner  Join containerLog c On c.ContainerNo=a.NoContainer 
			INNER  JOIN m_Customer d On d.custRegID=b.principle 
			LEFT JOIN RepairHeader e On e.containerID=a.NoContainer And e.bookID=a.bookInID
			Where  ".$terms;
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
	if($to_do =="RESUBMIT" || $to_do =="SUBMIT")  {	
      $query="Update repairHeader Set nilaiDPP=$DPP, totalHour=$totalMH, totalLabor=$totalLabor, totalMaterial=$totalMaterial Where estimateID='$kodeEstimate'";
	  $result=mssql_query($query);
	  //echo $query;
	}  

    if($to_do != "CALCULATE" && $kodeEstimate!="") {  
	  $query="Select * From RepairDetail Where estimateID='$kodeEstimate' Order By idItem";	  
	  $result=mssql_query($query);
	  $totalRow=mssql_num_rows($result);
	  if($totalRow > 0 ) {
        $validateGrid="View Result";
	    $totalBaris=$totalRow;
  		
	    for($i=0; $i<$totalRow; $i++) {		
/*          if(mssql_result($result, $i, 'isFlag') == 0) { $chk[$i]="on"; }
          else { $chk[$i]=""; } */
          if(mssql_result($result, $i, 'isFlag') == 0) { $nat[$i]="N"; }
	      else { $nat[$i]="Y"; }
		 
		  $loc[$i]=mssql_result($result, $i, 'locationID');
		  $part[$i]=mssql_result($result, $i, 'componentID');
          $dmg[$i]=mssql_result($result, $i, 'damageID'); 
		  $repair[$i]=mssql_result($result, $i, 'repairID');
		  $length[$i]=mssql_result($result, $i, 'lengthValue');
		  $Width[$i]=mssql_result($result, $i, 'widthValue');
		  $qty[$i]=mssql_result($result, $i, 'Quantity');
		  $deskripsi[$i]=mssql_result($result, $i, 'Remarks');
    	  
		  if(SUBSTR($kodeEstimate,0,3)=="REP") { $status[$i]="REVIEWED"; }
  	      else { $status[$i]="NEED REVIEW"; }

		  $mh[$i]=mssql_result($result, $i, 'hoursValue');
          $labor[$i]=mssql_result($result, $i, 'laborValue');		
		  $material[$i]=mssql_result($result, $i, 'materialValue');
		  $total[$i]=mssql_result($result, $i, 'totalValue'); 
		  if(mssql_result($result, $i, 'isOwner') == 0) { $party[$i] = 'O'; }
		  if(mssql_result($result, $i, 'isOwner') == 1) { $party[$i] = 'U'; }
		  if(mssql_result($result, $i, 'isOwner') == 2) { $party[$i] = 'T'; }
	    }	    
	  }
	  mssql_free_result($result);
    }  
</script>	

<div class="form-main">
 <div class="form-header">&nbsp;&nbsp;Estimate of <strong>REPAIR</strong></div>
<div class="height-10"></div>
<div class="w3-container">
 <form id="fEOR" name="myForm" method="post">
  <input type="hidden" name="noCnt" value='<?php echo $keywrd;?>' />
  <input type="hidden" name="kodeBooking" value='<?php echo $kodeBooking;?>' />
  <input type="hidden" name="kodeEstimate" value='<?php echo $kodeEstimate;?>' />
  <input type="hidden" name="size" value='<?php echo $size;?>' />
  <input type="hidden" name="tipe" value='<?php echo $tipe;?>' />
  <input type="hidden" name="height" value='<?php echo $height;?>' />
<!--
  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">Container No.</label></div>
	<div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $keywrd;?>" /></div>
	<div class="w3-twoquarter" ></div>
  </div>	  
  <div class="height-5"></div>
-->  
  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">Size/Type/Height</label></div>
	<div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $sizeCode;?>" /></div>
	<div class="w3-quarter" ><label class="w3-text-grey">Hamparan/Workshop In</label></div>
	<div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $tglMasuk;?>" /></div>
  </div>	
  <div class="height-5"></div>
  <div class="w3-row-padding">
    <div class="w3-quarter" ><label class="w3-text-grey">Estimate No.</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $kodeEstimate;?>" /></div>
	<div class="w3-quarter" ><label class="w3-text-grey">Submitted Date</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $tanggalEOR;?>" /></div>
  </div>	
  <div class="height-5"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">Shipping Line/Principle</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $principle;?>" /></div>	
	<div class="w3-quarter" ><label class="w3-text-grey">User</label></div>
	<div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo substr($consignee,0,25).'..';?>" /></div>	
  </div>	
  <div class="height-5"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">Ex. Vessel Voyage</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $vessel;?>" /></div>
	<div class="w3-twoquarter" ></div>
  </div>	
  <div class="height-5"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter" ><label class="w3-text-grey">Survey Date</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $tglSurvey;?>" /></div>	
	<div class="w3-quarter" ><label class="w3-text-grey">Surveyor</label></div>
    <div class="w3-quarter" ><input type="text" class="style-input" readonly value="<?php echo $surveyor;?>" /></div>	
  </div>	
  <div class="height-5"></div>
  <?php
    if($statusEstimate =="SUBMIT" || $statusEstimate =="APPROVE") {	
  ?>
      <div class="w3-row-padding">   
       <div class="w3-quarter"><label class="w3-text-grey">Before Tax</label></div>
       <div class="w3-quarter"><input type="text" class="style-input" style="text-align:right" readonly value="<?php echo number_format($DPP,2,",",".");?>" /></div>	
	   <div class="w3-quarter"><label class="w3-text-grey">Total Hour</label></div>
       <div class="w3-quarter"><input type="text" class="style-input" style="text-align:right" readonly value="<?php echo number_format($totalMH,2,",",".");?>" /></div>	
      </div>	
	  <div class="height-5"></div>   
      <div class="w3-row-padding">   
       <div class="w3-quarter"><label class="w3-text-grey">Total Material</label></div>
       <div class="w3-quarter"><input type="text" class="style-input" style="text-align:right" readonly value="<?php echo number_format($totalMaterial,2,",",".");?>" /></div>	
	   <div class="w3-quarter"><label class="w3-text-grey">Total Labor</label></div>
       <div class="w3-quarter"><input type="text" class="style-input" style="text-align:right" readonly value="<?php echo number_format($totalLabor,2,",",".");?>" /></div>	
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
  <div class="height-5"></div>
  <div class="w3-row-padding">   
    <div class="w3-quarter"><label>Price Code</label></div>
	<div class="w3-quarter">
 	  <?php
	    if($statusEstimate =="APPROVE") {
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
	<div class="w3-quarter"><label>Labor Rate</label></div>
	<div class="w3-quarter">
	  <?php
	    if($statusEstimate =="APPROVE") {
	  ?>	  
	  <input type="text" class="style-input" readonly name="laborDepo" style="text-align:right" value="<?php echo $labour?>" />
	  <?php
	    } else {
	  ?>
	  <input type="text" class="style-input" name="laborDepo" style="text-align:right" required onkeypress="return isNumber(event)" value="<?php echo $labour?>" />  
      <?php
        }
      ?>		
	</div>	
  </div>

  <div class="height-20"></div>
  <input type="hidden" name="whatToDo" value="" />
  <table>
	   <tr>	    
		 <td style="border:0;">
  	      <?php
	        /* status APPROVE, not allowed for editing */
	        if($statusEstimate !="APPROVE"|| strtoupper($_SESSION["uid"])=="ROOT") {
		  ?>
  		       <input type="button" class="w3-button w3-border w3-light-grey" name="newRow" style="padding:3px 6px" onclick=addRow_mine("dataTable") value="New Row" />
		       <input type="button" class="w3-button w3-border w3-light-grey" name="delRow" style="padding:3px 6px" onclick=deleteRow_mine('dataTable') value="Delete Marked Row" />		  
		       <button type="submit" class="w3-button w3-border w3-light-grey" style="padding:3px 6px" value="CALCULATE" name="calculate" onclick='this.form.whatToDo.value = this.value;'>Review</button>
					 
          <?php if($statusEstimate =="SUBMIT" || $statusEstimate =="DRAFT" || $_SESSION["uid"]) { ?>
		       <button type="submit" class="w3-button w3-border w3-light-grey" style="padding:3px 6px" value="RESUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Update</button>			 
          <?php }	
                if($statusEstimate =="") { ?>		  
		       <button type="submit" class="w3-button w3-border w3-light-grey" style="padding:3px 6px" value="DRAFT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Set As Draft</button>
		  <?php }
			    if($statusEstimate =="DRAFT" || $statusEstimate =="") {	?>		  		  
		       <button type="submit" class="w3-button w3-border w3-light-grey" style="padding:3px 6px" value="SUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Submit As EOR</button>			 
						
          <?php }
		    }
			if($statusEstimate =="SUBMIT" || $statusEstimate =="APPROVE") {	
             echo '<div class="w3-dropdown-hover">
			        <button class="w3-button w3-border" style="padding:3px 6px"><i class="fa fa-print"></i> Print Preview</button>
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

	<div class="height-10"></div>
	<div class="w3-container">
	<table id="dataTable" class="w3-table w3-bordered">
      <thead>
	     <tr>
		   <th></th>
		   <th>*Loc</th>
		   <th>*Part</th>
		   <th>*Dmg</th>
		   <th>*Act</th>
		   <th>*Length</th>
		   <th>*Width</th>
		   <th>*Qty</th>
		   <th>*Party</th>
		   <th>Descrp.</th>
		   <th>M/H</th>
		   <th>Labor</th>
		   <th>Mtrl.</th>
		   <th>Total</th>
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
             <?php if($status[$i]=="REVIEWED") { echo '<input type="checkbox" name="chk[]" onclick="return false" style="margin-top:8px">'; }
		           else { echo '<input type="checkbox" name="chk[]" style="margin-top:8px">'; } 
			       /*if($chk[$i] == "on") { echo '<input type="checkbox" name="chk[]" value="'.$chk[$i].'" checked style="margin-top:8px">'; }
			   else { echo '<input type="checkbox" name="chk[]" value="'.$chk[$i].'" unchecked style="margin-top:8px">'; } */ ?>
			 <input type="hidden" name="status[]" value="<?php echo $status[$i]?>" />  
		   </td>
		   <td><input type="text" class="style-input " name="loc[]" style="text-transform:uppercase;" style="width:100px" value='<?php echo $loc[$i];?>' /></td>
		   <td><input type="text" class="style-input " name="part[]" value='<?php echo $part[$i];?>' style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input " name="dmg[]" value='<?php echo $dmg[$i];?>' style="text-transform:uppercase;" style="width:50px" /></td>
		   <td><input type="text" class="style-input " name="repair[]" value='<?php echo $repair[$i];?>' style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input " name="length[]" value='<?php echo $length[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="Width[]" value='<?php echo $Width[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="qty[]" value='<?php echo $qty[$i];?>' onkeypress="return isNumber(event)" style="width:30px;text-align:right" /></td>
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
           <td><input type="text" name="deskripsi[]" class="style-input " readonly value="<?php echo $deskripsi[$i];?>" /></td>
		   <td><input type="hidden" class="style-input " name="mh[]" value='<?php echo $mh[$i];?>' />
		       <input type="text" class="style-input " readonly value='<?php echo number_format($mh[$i],2,",",".");?>' style="width:40px;text-align:right" />
		   </td>				
		   <td><input type="hidden" class="style-input " name="labor[]" value='<?php echo $labor[$i];?>' />
		       <input type="text" class="style-input " readonly value='<?php echo number_format($labor[$i],2,",",".");?>' style="width:60px;text-align:right" />
		   </td>
		   <td><input type="hidden" class="style-input " name="mtrl[]" value='<?php echo $material[$i];?>' />
		       <input type="text" class="style-input " readonly value='<?php echo number_format($material[$i],2,",",".");?>' style="width:60px;text-align:right" />
		   </td>
		   <td><input type="hidden" class="style-input " name="subTotal[]" value='<?php echo $total[$i];?>' />
		       <input type="text" class="style-input " readonly value='<?php echo number_format($total[$i],2,",",".");?>' style="width:80px;text-align:right" />
		   </td>
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
		   <td><input type="checkbox" checked name="chk[]" style="margin-top:8px" />
		       <input type="hidden" name="status[]" value="NEED REVIEW" />
		   </td>
		   <td><input type="text" class="style-input " name="loc[]" style="text-transform:uppercase;" style="width:100px" /></td>
		   <td><input type="text" class="style-input " name="part[]" style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input " name="dmg[]" style="text-transform:uppercase;" style="width:50px" /></td>
		   <td><input type="text" class="style-input " name="repair[]" style="text-transform:uppercase;" style="width:40px" /></td>
		   <td><input type="text" class="style-input " name="length[]" onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="Width[]" onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="qty[]" onkeypress="return isNumber(event)" style="width:30px;text-align:right" /></td>
		   <td><select id="party" class="style-select" name="party[]" style="width:40px">
			     <option value="O">O</option>
			     <option value="O">U</option>
			     <option value="O">T</option>
			    </select></td>
		   <td><input type="text" name="deskripsi[]" class="style-input " readonly value="" /></td>		
		   <td><input type="text" class="style-input " readonly name="mh[]" style="width:40px;text-align:right" /></td>				
		   <td><input type="text" class="style-input " readonly name="labor[]" style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input " readonly name="mtrl[]" style="width:60px;text-align:right" /></td>
		   <td><input type="text" class="style-input " readonly name="subTotal[]" style="width:80px;text-align:right" /></td>
 		  </tr>
        
		<?php
		    }
		   }
		?>
		
	  </tbody>
	</table></div>

 </form>
</div>

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
      $.post("estimate.php", formValues, function(data){ 
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