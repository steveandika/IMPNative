<script language="php">
  session_start();
  include ("../asset/libs/db.php");
</script>

<style>
  table { border: 0;width: 100%;margin:0;padding:0;border-collapse: collapse;border-spacing: 0; }
  table tr { border: 0;padding: 5px; }
  table th, table td { padding: 10px;text-align: center; border-bottom: 1px solid #ccc;}
  table th { text-transform: uppercase;font-size: 14px;letter-spacing: 1px; background-color: #607d8b; }

  @media screen and (max-width: 600px) {
    table { border: 0; }
    table thead { display: none; }
    table tr { margin-bottom: 10px; display: block; border-bottom: 2px solid #ddd; }
    table td { display: block; text-align: right!important; font-size: 13px; border-bottom: 1px dotted #ccc; }
    table td:last-child { border-bottom: 0; }
    table td:before { content: attr(data-label); float: left; text-transform: uppercase; font-weight: bold; }
  }
</style>  

<script language="php">  
  if(isset($_POST['nocontainer'])) {
    $keywrd=strtoupper(trim($_POST['nocontainer']));
	
	$dtmin=date('Y-m-d', strtotime($_POST['dtmin']));
	$timeIn=date("h:i");
	$truckin="";
	$nopol="";
	$exvessel=strtoupper($_POST['vessel']);
	$vesselATA=$_POST['vesselATA'];
	$principle=strtoupper($_POST['mlo']);
	$consignee=strtoupper($_POST['consignee']);
	$constr=$_POST['constr'];
	$height=trim($_POST['contHeight']);
	$type=trim($_POST['contType']);
	$size=trim($_POST['contSize']);
	$mnfr=trim($_POST['mnfrYear']);
	$remark=strtoupper($_POST['remarkcond']);
	
	$kodeBook=str_ireplace("-", "", date('Y-m-d', strtotime($_POST['dtmin'])));
	$kodeBook=substr($kodeBook,0,1).substr($kodeBook,2,6);
	$loc=$_POST['location']; 
	$kodeBook=$kodeBook.".".$loc;
	
	$surveyor=$_POST['surveyor'];
	
	$cond="AV";
	$pending="N";
/*	
	if(!isset($_POST['iscleaning'])) { $iscleaning = 0; }
	else { if($_POST['iscleaning']=="on") { $iscleaning = 1; }
	       else { $iscleaning = 0; } 
		 }
	
	if(!isset($_POST['isrepair'])) { $isrepair = 0; }
	else { if($_POST['isrepair']=="on") { $isrepair = 1; }
	       else { $isrepair = 0; } 
		 }
*/	
    //if($isrepair == 1 or $iscleaning==1) { $cond="DM"; $pending="Y"; }
    //else { $cond="AV"; $pending="N"; }
	
	$remarks="HAMPARAN IN > ".$keywrd;
	$query="If Not Exists(Select * From containerJournal Where NoContainer='$keywrd' And gateIn Is Not NULL) Begin 
	          Declare @KodeBookIn VarChar(30), @LastIndex Int; 
	          If Not Exists(Select * From logKeyField Where keyFName Like '".$kodeBook.'%'."') Begin
			    Set @KodeBookIn=CONCAT('".$kodeBook."','.1');
			    Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
			  End Else Begin  
			        Select @LastIndex=lastNumber+1 From logKeyField Where keyFName Like '".$kodeBook.'%'."';
                    Update logKeyField Set lastNumber=lastNumber+1 Where keyFName Like '".$kodeBook.'%'."';
                    Set @KodeBookIn=CONCAT('".$kodeBook."','.', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex)))); 
			      End;
			  Insert Into containerJournal(bookInID, NoContainer, gateIn, jamIn, TruckingIn, VehicleInNumber, Cond, isPending, tanggalSurvey, surveyor, remarkCond, pendingRemark) 
              Values(@KodeBookIn, '$keywrd', '$dtmin', '$timeIn', '$truckin', '$nopol', '$cond', '$pending', '$dtmin', '$surveyor', '', '$remark'); 
			
			  Insert Into tabBookingHeader(bookID, bookType, blID, principle, vessel, vesselATA, locationID, consignee) 
			  Values(@KodeBookIn, 0, '$kodeBook', '$principle', '$exvessel', '$vesselATA', '$loc', '$consignee'); 
			
			  If Not Exists(Select * From containerLog Where ContainerNo='$keywrd') Begin 
			    Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr) 
			    Values('$keywrd', 1, '$mnfr', 0, '$size', '$type', '$height', '$constr'); 
			  End;
              
			  Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', GETDATE(), CONCAT('$remarks', ' > ', @KodeBookIn)); 
			End; ";	
	$result=mssql_query($query);		
	
    $query="Select bookInID, NoContainer, gateIn, jamIn, TruckingIn, VehicleInNumber, Cond, remarkCond 
	        From containerJournal Where NoContainer='$keywrd' And gateIn='$dtmin'";
    $result=mssql_query($query);
    $design='<fieldset style="border-radius:4px">
	           <legend>&nbsp;Summary Result&nbsp;</legend>
	           <table> 
                 <thead><tr>
                   <th>TransactionID</th>
                   <th>Container Number</th>
                   <th>Date In</th>
                   <th>Time Log</th>
                   <th>Hold</th>
                   <th>Cleaning</th>
                   <th>Repair</th>
                   <th>Vessel Voyage</th>
				   <th>Survey Remark</th>
                  </tr></thead><tbody>';
    while($arr=mssql_fetch_array($result)) {
	  $strcleaning="N";
      $strrepair="N";	  
	  if($iscleaning==1) { $strcleaning="Y"; }
	  if($isrepair==1) { $strrepair="Y"; }
	  $design=$design.'<tr>
	                     <td data-label="Trans ID">'.$arr[0].'</td>
						 <td data-label="Container #">'.$arr[1].'</td>
						 <td data-label="DTM In">'.$arr[2].'</td>
						 <td data-label="Log Time">'.$arr[3].'</td>
						 <td data-label="Hold">'.$pending.'</td>
						 <td data-label="Cleaing">'.$strcleaning.'</td>
						 <td data-label="Repair">'.$strrepair.'</td>
						 <td data-label="Vessel">'.$exvessel.'</td>
						 <td data-label="Remark">'.$arr[7].'</td></tr>';  }
	$design=$design.'  </tbody></table>
	                  </div>
					 </fieldset>';
    echo $design; 	
    mssql_close($dbSQL); }
</script>