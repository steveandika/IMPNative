<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<script language="php">
  include("../asset/libs/db.php");

  $qry = "Select NoContainer, isRepair, isCleaning, bookInID, estimateID, Format(estimateDate, 'yyyy-MM-dd') As estimateDate
          From containerJournal a
          Inner Join repairHeader b On b.containerID=a.NoCOntainer
          Where CCleaning is Null and isCleaning=1 Order By gateIn; ";
  $res = mssql_query($qry);
  while($cols = mssql_fetch_array($res))
  {	  
    $estimateNo = $cols['estimateID'];
	$estimateDate = $cols['estimateDate'];
	$NoUnit = $cols['NoContainer'];
	$Book = $cols['bookInID'];
	
/*
 	    if($cleaning == "WW") { $remark = "LIGHT CLEANING"; }
	    if($cleaning == "DW") { $remark = "MEDIUM CLEANING"; }
	    if($cleaning == "CC") { $remark = "HEAVY CLEANING"; }
	    if($cleaning == "SC") { $remark = "SPECIAL CLEANING"; }
*/
    $estimate = "Select repairID, materialValue
	             From repairDetail Where estimateID='$estimateNo' And (repairID Like 'WW%' Or repairID Like 'DW%' Or repairID Like 'CC%' Or repairID Like 'SC%')";
	$qry_res = mssql_query($estimate);
	if(mssql_num_rows($qry_res) > 0)
	{
	  $colEst = mssql_fetch_array($qry_res);
      $cleaning = $colEst['repairID'];
      $fee = $colEst['materialValue']; 	  
	  
	  $do = "Update containerJournal Set CCleaning='$estimateDate', cleaningType='$cleaning' Where bookInID='$Book' And NoContainer='$NoUnit' ";
	  $resExec = mssql_query($do);
	  echo $do.'<br>';
	  if($recExec) 
	  {
        echo 'updated ContainerJournal .. BookingID='.$Book.',  Container No='.$NoUnit.', Cleaning='.$cleaning.'<br>';		  
      }	
      
   	  $postTgl = trim(str_ireplace("-","",$estimateDate));
	  $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6));
 	  if($cleaning == "WW") { $remark = "LIGHT CLEANING"; }
	  if($cleaning == "DW") { $remark = "MEDIUM CLEANING"; }
	  if($cleaning == "CC") { $remark = "HEAVY CLEANING"; }
	  if($cleaning == "SC") { $remark = "SPECIAL CLEANING"; }

      $do = "If Not Exists(Select * From CleaningHeader Where containerID='$NoUnit' And bookID='$Book') Begin
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
                                   Values(@NewDraft, '$NoUnit', '$estimateDate', '$fee', '$Book', '');			 
               Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                                   Values(@NewDraft, '', $fee, '$remark', '$cleaning');										
			 End Else Begin
			      Declare @IndexRec VarChar(30);
				  Select @IndexRec = cleaningID From CleaningHeader Where containerID='$NoUnit' And bookID='$Book';
				  Update CleaningDetail Set repairID = '$cleaning', Remarks = '$remark' Where cleaningID = @IndexRec;
				  Update CleaningHeader Set cleaningDate = '$estimateDate' Where cleaningID = @IndexRec;
				 End; 
				   
			   Update containerJournal Set cleaningType='$cleaning', CCleaning='$estimateDate' Where bookInID='$Book' And NoContainer='$keywrd';";
	  $resExec = mssql_query($do);		  
	  if($recExec) 
	  {
        echo 'inserted Cleaning Log .. BookingID='.$Book.',  Container No='.$NoUnit.', Cleaning='.$cleaning.'<br>';
      }		

      $do = "Update containerJournal Set Cond='AV', isPending='N',
                    AVCond = Case When Format(CCleaning, 'yyyy-MM-dd') > Format(CRDate, 'yyyy-MM-dd') Then Format(CCleaning, 'yyyy-MM-dd') 
                         Else Format(CRDate, 'yyyy-MM-dd') End					
			 Where bookInID='$Book' And NoContainer='$keywrd' And Cond ='DM'; ";		 
	  $recExec = mssql_query($do);
	  if($recExec) 
	  {
        echo 'container Journal updated..<br>';
      }			
    }		
  }

  mssql_close($dbSQL);  
?>  