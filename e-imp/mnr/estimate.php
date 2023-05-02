<?php
  session_start();    
  
  include("../asset/libs/common.php");
  openDB();
  
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
  else { $priceCode="IKPP_IDR"; }
  if(isset($_POST['whatToDo']))      { $to_do=strtoupper($_POST["whatToDo"]); }
  //if(strtoupper($_SESSION["uid"]) == "ROOT") { echo " var: ".$priceCode."<br>"; }	 
  
  $isType='DV';
  $cleaning_arr=array("WW","DW","CC","SC","SW");
  $to_do_arr=array("SUBMIT", "RESUBMIT");

  $query = "Select currency From m_RepairPriceList_Header Where priceCode='$priceCode'";  
  $resl = mssql_query($query);
  if(mssql_num_rows($resl) > 0) 
  {
	$col = mssql_fetch_array($resl);
	$currency = $col["currency"];
  } 
  else { $currency="IDR"; }	  
  mssql_free_result($resl);    
  
  // -- Start of: WhatToDo: CALCULATE/SET AS DRAFT/SUBMITTED -- //    
  //if (strtoupper($_SESSION["uid"])=="ROOT") { echo $to_do .": ".$keywrd."<br>"; }	
  
  if($to_do !="" && $keywrd !="") 
  {    
    $totalBaris=0;	
	
	for($i=0; $i<count($_POST['loc']); $i++) 
	{		
		if(isset($_POST['loc'][$i])) 
		{
			$temp=str_replace(" ","",$_POST['loc'][$i]);
			if($temp!="") { $totalBaris++; }  	
		}	
    }	
	
	if (strtoupper($_SESSION["uid"])=="ROOT") { echo 'total Baris: '.$totalBaris."<br>"; }
	
	if ($kodeEstimate!="") 
	{
		$qry="Select repairPriceCode From RepairHeader With (NOLOCK) Where estimateID='$kodeEstimate' ";
		$rsl=mssql_query($qry);
		if(mssql_num_rows($rsl)>0) 
		{ 
			$col=mssql_fetch_array($rsl);
			$curr_lama=$col['repairPriceCode'];
		} 
		else 
		{ 
			$curr_lama=$priceCode; 
		}
		mssql_free_result($rsl);	  
	}
	
	if (strtoupper($_SESSION["uid"])=="ROOT") 
	{ 
		echo $to_do."<br>"; 
		echo $kodeBooking."<br>";
		echo $keywrd."<br>";
		echo $kodeEstimate."<br>";		
		echo SUBSTR($kodeEstimate, 0,3)."<br>";
	}
		
	if ($to_do == "SUBMIT")
	{
	  $NoDraft = "";	
	  if ($kodeEstimate != "")
	  {
		if (SUBSTR($kodeEstimate, 0,3) == "DRF")
		{   
		  $NoDraft = $kodeEstimate;  
		  if (strtoupper($_SESSION["uid"])=="ROOT") { echo $NoDraft."<br>";}
		}  
      }		  
	  
	  if (SUBSTR($kodeEstimate, 0,3) == "DRF") 
	  {	
		$stmt = mssql_init("C_NewHeaderEORFromDRAFT");
        mssql_bind($stmt, "@estimateID", $kodeEstimate, SQLVARCHAR, true, false, 30);
        mssql_bind($stmt, "@BookID", $kodeBooking, SQLVARCHAR, false, false, 20);	  
	    mssql_bind($stmt, "@containerID", $keywrd, SQLVARCHAR, false, false, 150);
	    mssql_bind($stmt, "@NoDraft", $NoDraft, SQLVARCHAR, false, false, 30);
	    $result = mssql_execute($stmt);
	    mssql_free_statement($stmt);	  		
	  } 
	  else 
	  {
		$stmt = mssql_init("C_NewHeaderEOR");
        mssql_bind($stmt, "@estimateID", $kodeEstimate, SQLVARCHAR, true, false, 30);
        mssql_bind($stmt, "@BookID", $kodeBooking, SQLVARCHAR, false, false, 20);	  
		mssql_bind($stmt, "@containerID", $keywrd, SQLVARCHAR, false, false, 20);	  
	    mssql_bind($stmt, "@laborRate", $laborDepo, SQLFLT8, false, false);
	    mssql_bind($stmt, "@priceCode", $priceCode, SQLVARCHAR, false, false, 10);
		mssql_bind($stmt, "@currency", $currency, SQLVARCHAR, false, false, 5);
		mssql_bind($stmt, "@createBy", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);
	    $result = mssql_execute($stmt);
		  
		if (!$result)
		{
			echo 'Error on saving process :'.mssql_get_last_message();  
		}	  
	    mssql_free_statement($stmt);	  		  
	  }  
	}	
	
	for($i=0; $i<$totalBaris; $i++) 
	{
	  $validateGrid="View Result";  
	  $chk[$i]=$_POST['chk'][$i];
	  $size=$_POST['size'];
      $tipe=$_POST['tipe'];		
	  $height=$_POST['height'];
	   
	  $isFalse=0; /* var for counter */
				  
	  $loc[$i]=strtoupper($_POST['loc'][$i]);  
	  $special_loc=array("LG","LH","RG","RH","FG","FH","DG","DH");
	  
	  if(in_array(substr($loc[$i],0,2), $special_loc)) { $search_loc=substr($loc[$i],0,2);	} 
	  else { $search_loc=substr($loc[$i],0,1); }

	  $part[$i]=strtoupper(str_replace(' ','',$_POST['part'][$i]));
	  if($part[$i]=="") { $part_ = '%'; }
	  else { $part_ = $part[$i].'%'; }
	  
	  $nat[$i]=$_POST['isNAT'][$i];
	  //if (strtoupper($_SESSION["uid"])=="ROOT") { echo $_POST['isNat'][$i]." ".$nat[$i]."<br>"; }
		  
      $dmg[$i]=strtoupper($_POST['dmg'][$i]); 
	  $repair[$i]=str_replace(" ","",strtoupper($_POST['repair'][$i]));
		  
	  $length[$i]=$_POST['length'][$i];
	  if($length[$i] =='') { $length[$i] = 0; }
		  
	  $Width[$i]=$_POST['Width'][$i];
	  if($Width[$i] =='') { $Width[$i] = 0; }
		  
	  $qty[$i]=$_POST['qty'][$i];
	  if($qty[$i] =='') { $qty[$i] = 0; }
	  
  	  $party[$i]=$_POST['party'][$i];      
	  	 
	  $status[$i]=$_POST["status"][$i]; 

	  $part_lama="";
	  $loc_lama="";	  
	  $qty_lama=-1;	
	  $party_lama="";  
	  $act_lama="";
	  $dmg_lama="";
	  $isFlag=0;
	  $length_lama=-1;
	  $witdh_lama=-1;
	  
	  //if (strtoupper($_SESSION["uid"])=="ROOT") { echo $status[$i] .", NAT? ".$nat[$i].", ROW? ".$i."<br>"; }	
		
      if($status[$i]=="REVIEWED" && $kodeEstimate !="" && ($nat[$i]=="N" || $nat[$i]=="RECALL")) 
	  {	  
	    $indexDetail=$i +1;
		$qry="Select isOwner, Quantity, repairID, lengthValue, widthValue, damageID, 
		      componentID, locationID, isFlag From RepairDetail With (NOLOCK)  
		      Where estimateID='$kodeEstimate' And idItem=$indexDetail ";
		$rsl=mssql_query($qry);
		
		if (strtoupper($_SESSION["uid"])=="ROOT") { echo $qry."<br>"; };
		
		if(mssql_num_rows($rsl) > 0) 
		{
		  $col=mssql_fetch_array($rsl);
		  $loc_lama=$col["locationID"];
		  $part_lama=$col["componentID"];		  
		  $dmg_lama=$col["damageID"];		
          $act_lama=$col["repairID"];				  
		  $length_lama=$col["lengthValue"];
		  $width_lama=$col["widthValue"];          
		  $qty_lama=$col["Quantity"];
		  if($col["isOwner"]==0) { $party_lama="O"; }
		  if($col["isOwner"]==1) { $party_lama="U1"; }
		  if($col["isOwner"]==2) { $party_lama="T"; }	
		  if($col["isOwner"]==3) { $party_lama="U2"; }	
		  $isFlag=$col["isFlag"];
		}	
		mssql_free_result($rsl);
	  }
      
	  if ($status[$i]=="REVIEWED" && ($nat[$i]=="N" || $nat[$i]=="RECALL")) 
	  {
	    if (trim($loc_lama) !="") { if (trim($loc_lama) != $loc[$i]) { $status[$i]="NEED REVIEW"; }}	
	    if (trim($part_lama) !="") { if (trim($part_lama) != $part[$i]) { $status[$i]="NEED REVIEW"; }}		  
	    if (trim($dmg_lama) !="") { if (trim($dmg_lama) != $dmg[$i]) { $status[$i]="NEED REVIEW"; }}		  
	    if (trim($act_lama) !="") { if (trim($act_lama) != $repair[$i]) { $status[$i]="NEED REVIEW"; }}	  
 	    if ($length_lama !=-1) { if ($length_lama != $length[$i]) { $status[$i]="NEED REVIEW"; }}
 	    if ($width_lama !=-1) { if ($width_lama != $Width[$i]) { $status[$i]="NEED REVIEW"; }}	  
 	    if ($qty_lama !=-1) { if ($qty_lama != $qty[$i]) { $status[$i]="NEED REVIEW"; }}
	    if (trim($party_lama) !="") { if (trim($party_lama) != $party[$i]) { $status[$i]="NEED REVIEW"; }}	  
	    if (trim($curr_lama) !="") { if (trim($curr_lama) != $priceCode) { $status[$i]="NEED REVIEW"; }}
		if ($nat[$i]=="RECALL") { $status[$i]="NEED REVIEW"; }
	  }
	  
      if (($nat[$i]=="N" || $nat[$i]=="RECALL") && $isFlag==1) { $status[$i]="NEED REVIEW"; }	  
      if (strtoupper($_SESSION["uid"])=="ROOT") { echo $loc[$i]." ,".$status[$i] .", NAT? ".$nat[$i].", ROW? ".$i."<br>"; }	
	  if($loc[$i] !="" && $repair[$i] !="" &&  $qty[$i] !="" && $status[$i]!="REVIEWED") 
	  {
		$nat[$i]="N";		
		  
		$query="Select * From m_RepairPriceList With (NOLOCK) Where priceCode='$priceCode' And isType Like '$isType' And unitSize ='".$size."' And 
		         (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		          And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].")"; 
		$result=mssql_query($query);	
		$rows=mssql_num_rows($result);	
		  
		if($rows != 1) 
		{		    
			mssql_free_result($result);
			$query="Select * From m_RepairPriceList With (NOLOCK) Where priceCode='$priceCode' And isType Like '$isType' And unitSize = '".$size."' And 
		           unitHeight in ('$tipe', '$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And cWidth=".$length[$i]." and cLength=".$Width[$i]." ;";	   
		    $result=mssql_query($query);	
		    $rows = mssql_num_rows($result);				
		}

		if($rows != 1) 
		{
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList With (NOLOCK) Where priceCode='$priceCode' And isType Like '$isType' And unitSize ='".$size."' And 
		           (unitHeight='$tipe' or unitHeight='$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And (cLength=".$length[$i]." and cWidth=".$Width[$i].") And cQty=".$qty[$i]."; ";
		    $result=mssql_query($query);	
		    $rows=mssql_num_rows($result);					
		}
		  			
		if($rows != 1) 
		{
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList With (NOLOCK) Where priceCode='$priceCode' And isType Like '$isType' And unitSize ='".$size."' And 
		           unitHeight In ('$tipe', '$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And cWidth=".$length[$i]." and cLength=".$Width[$i]." And cQty=".$qty[$i]."; ";
			$result=mssql_query($query);	
			$rows=mssql_num_rows($result);					
		}	

		if($rows != 1) 
		{
            mssql_free_result($result);
		    $query="Select * From m_RepairPriceList With (NOLOCK) Where priceCode='$priceCode' And isType Like '$isType' And unitSize ='".$size."' And 
		           unitHeight In ('$tipe', '$height') And LocDamage = '".$search_loc."' And PartDamage Like '".$part_."' 
		            And Act='".$repair[$i]."' And cLength=".$length[$i]." and cWidth=".$Width[$i]." And cQty=".$qty[$i]."; ";
			$result=mssql_query($query);	
			$rows=mssql_num_rows($result);		
		}			  
          
        if (strtoupper($_SESSION["uid"])=="ROOT") { echo $query."<br>"; }		
		  			  
		if($rows==1) 
		{
		    $arr=mssql_fetch_array($result);
			$deskripsi[$i]=$arr["Description"];
			if (strtoupper($_SESSION["uid"])=="ROOT") { echo $arr["isMulti"]."AAAAA<br>"; }
			
		    if($arr["isMulti"]==0) 
			{ 
				$mh[$i]=$arr["MH"];
			  
				if(in_array($repair[$i], $cleaning_arr) && $arr["materialValue"]>1000) 
				{
					if($size=="20") { $labor[$i]=10000;	}
					else { $labor[$i]=15000; }				
					$material[$i]=$arr["materialValue"]-$labor[$i];				
				}
				else 
				{
					if(in_array($repair[$i], $cleaning_arr) && $arr["materialValue"]>1) 
					{
						if($size=="20") { $labor[$i]=0.83; }
						else { $labor[$i]=1.25; }				
						$material[$i]=$arr["materialValue"]-$labor[$i];								  
					}	  				
					else 
					{			  
						$labor[$i]=$mh[$i]*$laborDepo;			
						$material[$i]=$arr["materialValue"];
					}	
				}	
				$total[$i]=$labor[$i]+ $material[$i];  
		    }

		    if ($arr["isMulti"]==1) 
			{ 			 
				$mh[$i]=$arr["MH"] *$qty[$i];
				$labor[$i]=$mh[$i]*$laborDepo;			
				$material[$i]=$arr["materialValue"] *$qty[$i];
				$total[$i]=$labor[$i] +$material[$i];  
				if (strtoupper($_SESSION["uid"])=="ROOT") { echo $mh[$i]."*".$laborDepo."<br>"; }
		    }
						
		    /*if ($arr["isMulti"] !=1 && strtoupper($_SESSION["uid"]) =="ROOT") { 
			  $mh[$i]=$arr["MH"]; //*$qty[$i];
			  
              $labor[$i]=$mh[$i]*$laborDepo;			
			  $material[$i]=$arr["materialValue"] *$qty[$i];
			  $total[$i]=$labor[$i] +$material[$i];			
			}*/
		} 
		else 
		{
		    $isFalse++;  
  	        $deskripsi[$i]="ITEM NOT FOUND";
		    $mh[$i]=0;
            $labor[$i]=0;			
		    $material[$i]=0;
		    $total[$i]=0;  
		}
		mssql_free_result($result);
	  }
 	  else 
	  {
		$nat[$i]=$_POST['isNAT'][$i];	  
	    $mh[$i]=$_POST['mh'][$i];	  
		$labor[$i]=$_POST['labor'][$i];	
	    $material[$i]=$_POST['mtrl'][$i];	
		$total[$i]=$_POST['subTotal'][$i];			
        $deskripsi[$i]=$_POST['deskripsi'][$i];		
	  }	  
		
	 /* end of validation checkbox*/
    }	/* end of looping */		

    if ($to_do =="DRAFT" && $isFalse ==0) 
	{
		$stmt = mssql_init("C_NewHeaderEORDraft");
		mssql_bind($stmt, "@estimateID", $kodeEstimate, SQLVARCHAR, true, false, 30);
		mssql_bind($stmt, "@BookID", $kodeBooking, SQLVARCHAR, false, false, 20);	  
		mssql_bind($stmt, "@containerID", $keywrd, SQLVARCHAR, false, false, 20);	
		mssql_bind($stmt, "@laborRate", $laborDepo, SQLFLT8, false, false);				
		mssql_bind($stmt, "@priceCode", $priceCode, SQLVARCHAR, false, false, 10);
		mssql_bind($stmt, "@currency", $currency, SQLVARCHAR, false, false, 5);
		mssql_bind($stmt, "@createBy", strtoupper($_SESSION['uid']), SQLVARCHAR, false, false, 11);
		
		$result = mssql_execute($stmt);
		  
		if(!$result) 
		{ 
			echo 'Error on saving process :'.mssql_get_last_message(); 
		}	  	

		mssql_free_statement($stmt);
      
		$query = "Select estimateID From RepairHeader with (NOLOCK) Where containerID='$keywrd' And bookID='$kodeBooking'";
		$result = mssql_query($query);
		if(mssql_num_rows($result) > 0) 
		{
			$rows = mssql_fetch_array($result);
			$kodeEstimate = $rows['estimateID'];
			mssql_free_result($result);
  	    
			$index=1;

			$do = "Delete From repairDetail Where estimateID = '$kodeEstimate'; ";
			$result = mssql_query($query);
		
			for($i=0; $i<$totalBaris; $i++) 
			{
				if($party[$i] =="O")  { $partnum=0; }
				if($party[$i] =="U1") { $partnum=1; }	
				if($party[$i] =="T")  { $partnum=2; }		
				if($party[$i] =="U2") { $partnum=3; }				  

				$sql = "EXEC C_NewDetailEOR
						@estimateID = '$kodeEstimate',
						@index = $index,
						@component = '$part[$i]',
						@location = '$loc[$i]',
						@damage = '$dmg[$i]',
						@action = '$repair[$i]',
						@L1 = $length[$i],
						@L2 = $Width[$i],
						@qty = $qty[$i],
						@billParty = $partnum,
						@totalHour = $mh[$i],
						@totalLabor = $labor[$i],
						@rawMaterial = $material[$i],
						@subTotal = $total[$i],
						@remark = '$deskripsi[$i]',
						@BookID = '$kodeBooking';";	
				$spExec = mssql_query($sql);		  
				$index++;		  
			}
		}		  
		
		$DPP=0;
		$totalMH=0;
		$totalLabor=0;
		$totalMaterial=0;	
		if($totalBaris > 0) 
		{
			$query="Select SUM(hoursValue) As TotalHours, SUM(laborValue) As TotalLabor, SUM(materialValue) As TotalMaterial, 
					SUM(totalValue) As TotalEOR From RepairDetail with (NOLOCK) Where estimateID='$kodeEstimate'";
			$result=mssql_query($query);
			while($arr=mssql_fetch_array($result)) 
			{
				$DPP=$arr[3];
				$totalMH=$arr[0];
				$totalLabor=$arr[1];
				$totalMaterial=$arr[2]; 
			}			
			mssql_free_result($result);
			$PPN = 0.11;
		
			$do="Update Repairheader Set nilaiDPP=$DPP, totalHour=$totalMH, totalLabor=$totalLabor, totalMaterial=$totalMaterial, PPN=$PPN 
				Where estimateID='$kodeEstimate'; ";
			$res_exec=mssql_query($do);					
		}			
	} /* end of whattodo: Set as Draft */	
	
	if ($to_do =="SUBMIT" && $isFalse ==0)
	{   
  
	  //if (strtoupper($_SESSION["uid"] == "ROOT")) { echo $do."<br>"; }
	  if ($kodeEstimate!="") 
	  {
		$index=1;
 	    $withCleaning=0;
	    $totalCleaning=0;
		$mtrlCleaning=0;
		
	    for($i=0; $i<$totalBaris; $i++) 
		{
		  if ($loc[$i] != "") 
		  {
	        if ($party[$i] =="O")  { $partnum=0; }
	        if ($party[$i] =="U1") { $partnum=1; }	
	        if ($party[$i] =="T")  { $partnum=2; }	
			if ($party[$i] =="U2") { $partnum=3; }	
            
            $sql = "EXEC C_NewDetailEOR
            			 @estimateID = '$kodeEstimate',
                         @index = $index,
						 @component	= '$part[$i]',
						 @location	= '$loc[$i]',
						 @damage = '$dmg[$i]',
						 @action = '$repair[$i]',
						 @L1 = $length[$i],
						 @L2 = $Width[$i],
						 @qty = $qty[$i],
						 @billParty	= $partnum,
						 @totalHour	= $mh[$i],
						 @totalLabor = $labor[$i],
						 @rawMaterial = $material[$i],
						 @subTotal = $total[$i],
						 @remark = '$deskripsi[$i]',
						 @BookID = '$kodeBooking';";	
			$spExec = mssql_query($sql);
			
			$index++;				
		  }		
	    }  		
	  }          
	}
	
	if ($to_do == "RESUBMIT" && $isFalse ==0) 
	{
	  $sql = "EXEC C_UpdateHeaderEstimate
	               @estimateID = '$kodeEstimate',
                   @BookID = '$kodeBooking',
                   @laborRate = $laborDepo,
                   @currencyAs = '$currency',
                   @priceCode = '$priceCode';";
      $spExec = mssql_query($sql);
	  
	  for($i=0; $i<$totalBaris; $i++) 
	  {	
		if($loc[$i] != "") 
		{
		  $index=$i+1;	
		  
	      if($party[$i] =="O")  { $partnum=0; }
	      if($party[$i] =="U1") { $partnum=1; }	
	      if($party[$i] =="T")  { $partnum=2; }	
          if($party[$i] =="U2") { $partnum=3; }			  

          if ($status[$i]!="REVIEWED") 
		  {
            $sql = "EXEC C_NewDetailEOR
            			 @estimateID = '$kodeEstimate',
                         @index = $index,
						 @component	= '$part[$i]',
						 @location	= '$loc[$i]',
						 @damage = '$dmg[$i]',
						 @action = '$repair[$i]',
						 @L1 = $length[$i],
						 @L2 = $Width[$i],
						 @qty = $qty[$i],
						 @billParty	= $partnum,
						 @totalHour	= $mh[$i],
						 @totalLabor = $labor[$i],
						 @rawMaterial = $material[$i],
						 @subTotal = $total[$i],
						 @remark = '$deskripsi[$i]',
						 @BookID = '$kodeBooking';";	
			$spExec = mssql_query($sql);			  					
		  }	
		  else 
		  {
		    if($_POST['isNAT'][$i]=="Y") 
			{
			  $rem=$deskripsi[$i]." (NAT)";  
	
		      $do="UPDATE RepairDetail SET isFlag=1, totalValue=0, Remarks='$rem'
                    WHERE isFlag=0 AND estimateID='$kodeEstimate' AND componentID='$part[$i]' AND locationID='$loc[$i]' AND 
                          damageID='$dmg[$i]' AND repairID='$repair[$i]' AND lengthValue=$length[$i] AND
                          widthValue=$Width[$i] AND Quantity=$qty[$i] AND isOwner=$partnum ";
              $rsl=mssql_query($do);	

              if (in_array($repair[$i], $cleaning_arr)) 
			  {
	  		    $remarkBookID=$kodeBooking."*";
			    $do="Update CleaningHeader Set BookID='$remarkBookID' Where BookID='$kodeBooking' And ContainerID='$keywrd' ";
                $rsl=mssql_query($do);			  
			  
			    $do="Update containerJournal Set CCleaning=Null, cleaningType=Null, isCleaning=0 Where BookInID='$kodeBooking' And NoContainer='$keywrd' ";
			    $rsl=mssql_query($do);			  
              }				
            }				  
		  }		 
		}		
	  }	
    }	
  } 
  // -- end of: WhatToDo -- //	

  /* if is Valid to Edit or Create EOR for current Container said in $_GET */
  $terms="";
  if($keywrd != '' && $kodeBooking != '') {	$terms="(a.NoContainer='$keywrd') And (a.bookInID='$kodeBooking') "; }
  if($kodeEstimate != '') { $terms="e.estimateID='$kodeEstimate' "; }
 
  if($terms !="") 
  {	    
    $query="select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, CAST(a.gateIn AS DATE) As DateIn, a.workshopID, 
	               CAST(a.tanggalSurvey AS DATE) surveyDate, a.Surveyor,
			       b.principle, CONCAT(b.vessel,', ',b.voyageID) vessel, b.consignee, isNull(e.LaborRate,0) laborRate, IsNull(e.repairPriceCode,'') repairPriceCode, 
			       0 currRepair, e.estimateID, CAST(e.estimateDate AS DATE) estimateDate, statusEstimate, CAST(e.tanggalApprove AS DATE) tglApp,
                   ISNULL(e.totalHour,0) hourValue, ISNULL(e.totalLabor, 0) laborValue, 
				   ISNULL(e.totalMaterial, 0) materialValue, ISNULL(e.nilaiDPP, 0) totalEOR					   
            from containerJournal a inner join tabBookingHeader b On b.bookID=a.bookInID
		                            inner join containerLog c On c.ContainerNo=a.NoContainer 
			                        inner join m_Customer d On d.custRegID=b.principle 
			                        left join RepairHeader e On e.containerID=a.NoContainer And e.bookID=a.bookInID
			WHERE ".$terms;
	$result=mssql_query($query);

	if(mssql_num_rows($result) == 1) 
	{
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
	  $tglApp=$arr["tglApp"];
	  if($arr['laborRate'] > 0) { $labour=$arr['laborRate']; }
	  if(trim($arr['repairPriceCode']) != '') { $priceCode=$arr['repairPriceCode']; }

      $kodeEstimate=$arr["estimateID"];
      $tanggalEOR=$arr["estimateDate"];	 
      $statusEstimate=$arr["statusEstimate"];	  
	  
	  $DPP = $arr['totalEOR'];
	  $totalMH = $arr['hourValue'];
	  $totalMaterial = $arr['materialValue'];
	  $totalLabor = $arr['laborValue'];	  
	}	  
	mssql_free_result($result);
	
	$have_principle=haveCustomerName($principle);
	$have_consignee=haveCustomerName($consignee);
	$principle=$have_principle;
	$consignee=$have_consignee;
	
    if($to_do != "CALCULATE" && $kodeEstimate!="") 
	{  
	  $query="Select * From RepairDetail Where estimateID='$kodeEstimate' Order By idItem";	  
	  $result=mssql_query($query);
	  $totalRow=mssql_num_rows($result);
	  if($totalRow > 0 ) 
	  {
        $validateGrid="View Result";
	    $totalBaris=$totalRow;
  		
	    for($i=0; $i<$totalRow; $i++) 
		{		
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
		  
		  if (mssql_result($result, $i, 'isOwner') == 0) { $party[$i] = 'O'; }
		  if (mssql_result($result, $i, 'isOwner') == 1) { $party[$i] = 'U1'; }
		  if (mssql_result($result, $i, 'isOwner') == 2) { $party[$i] = 'T'; }
		  if (mssql_result($result, $i, 'isOwner') == 3) { $party[$i] = 'U2'; }
	    }	    
	  }
	  mssql_free_result($result);
    }  
?>

<div class="height-10"></div>
<div id="pageTitle">Estimate of Repair</div><div class="height-5"></div>
<form id="fEOR" name="myForm" method="post">
	<input type="hidden" name="noCnt" value="<?php echo $keywrd;?>" />
	<input type="hidden" name="kodeBooking" value="<?php echo $kodeBooking;?>" />
	<input type="hidden" name="kodeEstimate" value="<?php echo $kodeEstimate;?>" />
	<input type="hidden" name="size" value="<?php echo $size;?>" />
	<input type="hidden" name="tipe" value="<?php echo $tipe;?>" />
	<input type="hidden" name="height" value="<?php echo $height;?>" />
	<input type="hidden" name="whatToDo" value="" />

	<table class="w3-bordered">
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Size/Type/Height</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo $sizeCode;?></td>
		<td style="border-right:1px solid #ddd"><strong>Hamparan/Workshop In</strong></td>
		<td><?php echo $tglMasuk;?></td>
	  </tr>
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Survey</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo $tglSurvey;?></td>
		<td style="border-right:1px solid #ddd"><strong>Surveyor</strong></td>
		<td><?php echo $surveyor;?></td>
	  </tr>		  
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Estimate Number</strong></td>
		<td style="border-right:1px solid #ddd"><strong><?php echo $kodeEstimate;?></strong></td>
		<td style="border-right:1px solid #ddd"><strong>Submitted Estimate</strong></td>
		<td><?php echo $tanggalEOR." - ".$statusEstimate;?></td>
	  </tr>	  
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Approved Date</strong></td>
		<td style="border-right:1px solid #ddd"><strong><?php echo $tglApp;?></strong></td>
		<td colspan="2"></td>
	  </tr>	  
	  
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Shipping Line/Principle</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo $principle;?></td>
		<td style="border-right:1px solid #ddd"><strong>Ex. User</strong></td>
		<td><?php echo substr($consignee,0,25).'..';?></td>
	  </tr>	 	
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Ex. Vessel/Voyage</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo $vessel;?></td>
		<td style="border-right:1px solid #ddd" colspan="2"></td>
	  </tr>	  	  

	  <?php 
		if($statusEstimate =="SUBMIT" || $statusEstimate =="APPROVE") 
		{	
	  ?>
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Exclude Tax</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo number_format($DPP,2,",",".");?></td>
		<td style="border-right:1px solid #ddd"><strong>Total Man Hour</strong></td>
		<td><?php echo number_format($totalMH,2,",",".");?></td>
	  </tr>	 	
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Total Material</strong></td>
		<td style="border-right:1px solid #ddd"><?php echo number_format($totalMaterial,2,",",".");?></td>
		<td style="border-right:1px solid #ddd"><strong>Total Labor</strong></td>
		<td><?php echo number_format($totalLabor,2,",",".");?></td>
	  </tr>	    
	  <?php 
		} 
	  ?>
	  
	  <tr>
		<td style="border-right:1px solid #ddd"><strong>Price Code</strong></td>
		<td style="border-right:1px solid #ddd">
		<?php if($statusEstimate =="APPROVE") {  echo '<input type="text" class="style-input" readonly name="priceCode" style="text-align:right" value="'.$priceCode.'" />'; }
			  else {
				$cmd="SELECT DISTINCT a.priceCode FROM m_RepairPriceList a 
					  INNER JOIN m_RepairPriceList_Header b ON b.priceCode=a.priceCode 
					  ORDER BY priceCode";
				$resl=mssql_query($cmd);  
				$design="";
				$design=$design.'<select name="priceCode" required class="style-select">';
				while($arrFetch = mssql_fetch_array($resl)) 
				{
				  if($priceCode == $arrFetch[0]) {$design=$design.'<option selected value="'.$arrFetch[0].'">&nbsp;'.strtoupper($arrFetch[0]).'</option>'; }
				  else { $design=$design.'<option value="'.$arrFetch[0].'">&nbsp;'.strtoupper($arrFetch[0]).'</option>'; }	
				}	
				$design=$design.'</select>';
				
				mssql_free_result($resl);	
				echo $design;
			  }	  
		?>
		</td>
		<td style="border-right:1px solid #ddd"><strong>Labor Rate</strong></td>
		<td>
		<?php
			if($statusEstimate =="APPROVE") 
			{ 
				echo '<input type="text" class="style-input" readonly name="laborDepo" style="text-align:right" value="'.$labour.'" />'; 
			}
			else 
			{
				$design = '<select name="laborDepo" class="style-input">';

				if ($labour == 30000) { $design .= ' <option selected value=30000>30.000 IDR</option>'; }
				else { $design .= ' <option value=30000>30.000 IDR</option>'; }
				if ($labour == 20000) { $design .= ' <option selected value=20000>20.000 IDR</option>'; }
				else { $design .= ' <option value=20000>20.000 IDR</option>'; }	
				if ($labour == 19000) { $design .= ' <option selected value=19000>19.000 IDR</option>'; }
				else { $design .= ' <option value=19000>19.000 IDR</option>'; }	
				if ($labour == 18000) { $design .= ' <option selected value=18000>18.000 IDR</option>'; }
				else { $design .= ' <option value=18000>18.000 IDR</option>'; }								
				if ($labour == 15000) { $design .= ' <option selected value=15000>15.000 IDR</option>'; }
				else { $design .= ' <option value=15000>15.000 IDR</option>'; }								
                if ($labour == 14500) { $design .= ' <option selected value=14500>14.500 IDR</option>'; }
				else { $design .= ' <option value=14500>14.500 IDR</option>'; }				
				if ($labour == 12500) { $design .= ' <option selected value=12500>12.500 IDR</option>'; }
				else { $design .= ' <option value=12500>12.500 IDR</option>'; }
				
			    if ($labour == 1.04) { $design .= ' <option selected value=1.04>1.04 USD</option>'; }
				else { $design .= ' <option value=1.04>1.04 USD</option>'; }
				if ($labour == 1 || $labour == 1.2) { $design .= ' <option selected value=1.2>1.2 USD</option>'; }
				else { $design .= ' <option value=1.2>1.2 USD</option>'; }
				if ($labour == 1.12) { $design .= ' <option selected value=1.12>1.12 USD</option>'; }
				else { $design .= ' <option value=1.12>1.12 USD</option>'; }
				
                  
				$design .= '</select>';				
				
				echo $design;  				
			  }	  
		?>
		</td>
	  </tr>	  
	</table>
	<div class="height-10"></div>

	<ul class="flex-container">        
	<?php
	/* status APPROVE, not allowed for editing */
	if($statusEstimate != 'APPROVE' || strtoupper($_SESSION['uid'])=="ROOT") 
	{
	?>

	  <li class="flex-item"><input type="button"  class="w3-button w3-green" name="newRow" onclick=addRow_mine("dataTable") value="New Row" /></li>
	  <li class="flex-item"><input type="button" class="w3-button w3-red" name="delRow" onclick=deleteRow_mine('dataTable') value="Delete Marked Row" /></li>		  
	  <li class="flex-item"><button type="submit"  class="w3-button w3-purple" value="CALCULATE" name="calculate" onclick='this.form.whatToDo.value = this.value;'>Review Entry</button></li>
					 
	<?php      
		if (strtoupper($_SESSION["uid"])=="ROOT") { echo $statusEstimate; }
		if($statusEstimate == 'SUBMIT' || $statusEstimate == 'DRAFT'  || strtoupper($_SESSION['uid'])=="ROOT") 
		{
	?>

		  <li class="flex-item"><button type="submit"  class="w3-button w3-green" value="RESUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Update Detail</button></li>			 

	<?php		  
		}	
		if($statusEstimate == '' || $statusEstimate=='DRAFT') 
		{				  
	?>		  
		  <li class="flex-item"><button type="submit" class="w3-button w3-pale-blue" value="DRAFT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Save As Draft</button></li>
		  <li class="flex-item"><button type="submit" class="w3-button w3-blue" value="SUBMIT" name="draft" onclick='this.form.whatToDo.value = this.value;'>Submit As EOR</button></li>			 
						
	<?php
		}
	}

	if($statusEstimate == 'SUBMIT' || $statusEstimate == 'APPROVE') 
	{	
	  echo '<li class="flex-item"><div class="w3-dropdown-hover">
			  <button class="w3-button w3-gray">Print Preview</button>
			  <div class="w3-dropdown-content w3-bar-block w3-border">
				<a class="w3-bar-item w3-button" href="print_eor?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'" target="_blank">Estimate (Complete)</a>
				<a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'" target="_blank">Estimate Only</a>
				<a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&party=0" target="_blank">Estimate (Owner)</a>
				<a class="w3-bar-item w3-button" href="print_eoronly?id='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&party=1" target="_blank">Estimate (User)</a>
				<a class="w3-bar-item w3-button" href="viewPh?es='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=2" target="_blank">Image(Before Repair)</a>
				<a class="w3-bar-item w3-button" href="viewPh?es='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=3" target="_blank">Image(After Repair)</a>
				<a class="w3-bar-item w3-button" href="viewPh?es='.$kodeEstimate.'&cnt='.$keywrd.'&bookid='.$kodeBooking.'&id=1" target="_blank">Image(Complete)</a>
			  </div>
			</div></li>';
	}
	?>		  
	</ul>			  

	<div class="height-10"></div>

	 <table id="dataTable" class="w3-bordered">
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
			 <?php /*if($status[$i]=="REVIEWED") { echo '<input type="checkbox" name="chk[]" onclick="return false" style="margin-top:8px">'; }
				   else { echo '<input type="checkbox" name="chk[]" style="margin-top:8px">'; } */
				   $power_user=array("ROOT","JOK001");
				   
				   if(in_array(strtoupper($_SESSION["uid"]), $power_user)) {				   
					 if($status[$i]=="REVIEWED") { echo '<select class="style-select" name="isNAT[]">'; }
					 else { echo '<select class="style-select" name="isNAT[]" disabled>'; } 
					 if($nat[$i]=="Y") { echo '<option selected value="Y">NAT&nbsp;</option>'; }
					 else  { echo '<option value="Y">NAT&nbsp;</option>'; }
					 if($nat[$i]=="N") { echo '<option selected value="N">&nbsp;</option>'; }
					 else  { echo '<option value="RECALL">RECALL&nbsp;</option>'; }
				   
					 echo '</select>';
				   } else { echo '<input type="hidden" name="isNAT[]" value="N" />'; }
			 ?>
			 <input type="hidden" name="status[]" value="<?php echo $status[$i]?>" readonly />  
		   </td>
		   <td><input type="text" class="style-input " name="loc[]" style="text-transform:uppercase;width:100px" value='<?php echo $loc[$i];?>' /></td>
		   <td><input type="text" class="style-input " name="part[]" value='<?php echo $part[$i];?>' style="text-transform:uppercase;width:40px" /></td>
		   <td><input type="text" class="style-input " name="dmg[]" value='<?php echo $dmg[$i];?>' style="text-transform:uppercase;width:50px" /></td>
		   <td><input type="text" class="style-input " name="repair[]" value='<?php echo $repair[$i];?>' style="text-transform:uppercase;width:40px" /></td>
		   <td><input type="text" class="style-input " name="length[]" value='<?php echo $length[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="Width[]" value='<?php echo $Width[$i];?>' onkeypress="return isNumber(event)" style="width:40px;text-align:right" /></td>
		   <td><input type="text" class="style-input " name="qty[]" value='<?php echo $qty[$i];?>' onkeypress="return isNumber(event)" style="width:30px;text-align:right" /></td>
		   <td><select id="party" class="style-select" name="party[]" style="width:40px">
				<?php 
				  if($party[$i] == "O") { echo '<option selected value="O">O</option>'; }
				  else { echo '<option value="O">O</option>'; }
				  if($party[$i] == "U" || $party[$i] == "U1") { echo '<option selected value="U1">U1</option>'; }
				  else { echo '<option value="U1">U1</option>'; }
				  if($party[$i] == "U2") { echo '<option selected value="U2">U2</option>'; }
				  else { echo '<option value="U2">U2</option>'; }				  
				  if($party[$i] == "T") { echo '<option selected value="T">T</option>'; }
				  else { echo '<option value="T">T</option>'; }				  
				?>
				</select>
		   </td>
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
				 <option value="U1">U1</option>
				 <option value="U2">U2</option>
				 <option value="T">T</option>
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
	 </table>

</form>

<?php
	$sql = "select * from RepairMsgLog with (NOLOCK) where estimateID = '$kodeEstimate'; ";
	$rsl = mssql_query($sql);
	if ($rsl){
	  $htmlopen = '';
	  $htmlcontent ='';
	  $htmlclose ='';
	  
	  $htmlopen = '<textarea name="logEstimate" style="width:100%" rows="8" readonly>';
	  while ($arrfield = mssql_fetch_array($rsl)){
		$htmlcontent .= $arrfield['logDTTM'].": ".$arrfield['MessgText'] ."\n";
	  }	  
	  $htmlclose = '</textarea>';
	  
	  if ($htmlcontent <> '') {
		echo $htmlopen.$htmlcontent.$htmlclose;	  
	  }	
	}
	mssql_free_result($rsl);
	
  }
?>

<div class="height-10"></div>
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
