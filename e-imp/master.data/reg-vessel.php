<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  $keywrd='';
  $operatorFeeder='';
  $vesselName='';
  $voyage='';
  $POL='';
  $POD='';
  
  if(isset($_GET['id'])) {
    $keywrd=$_GET['id'];
	
	$query="Select *,FORMAT(ETD, 'yyyy-MM-dd') As ETD, FORMAT(ETA, 'yyyy-MM-dd') As ETA From m_vessel Where vesselid='$keywrd'";
	$result=mssql_query($query);
	while($arr=mssql_fetch_array($result)) {
	  $operatorFeeder=$arr[0];
      $vesselName=$arr[1];
      $voyage=$arr[2];
      $inOut=$arr[3];
      $POL=$arr[4];
      $POD=$arr[5];	  
	  $ETD=$arr[9];
	  $ETA=$arr[10];	}
	mssql_free_result($result); }	
  
  //Part: Update Field
  
  if(isset($_POST['vesselid'])) {
    $notValid = 0;
    if($_POST['POD'] == $_POST['POL']) { $notValid=1; }
    
	if($notValid == 0) {
      $keywrd=$_POST['vesselid'];
  
      $inOut = "I";
	  if(isset($_POST['POL'])) { $inOut="O"; }
      
	  $query="Update m_vessel Set vesselName='".strtoupper($_POST['vesselName'])."', voyage='"
           	.strtoupper($_POST['voyage'])."', IO='$inOut', portLoading='".$_POST['POL']."', portDischarge='"
			.$_POST['POD']."', ETD='".$_POST['etd'] ."', ETA='".$_POST['eta']."' Where vesselid='$keywrd';
			Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	        Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Update Vessel Detail: ','".$keywrd."')); ";
	  $result=mssql_query($query);
	  $keywrd=$_POST['vesselid'];
      echo '<script>$("#result").load("list-vessel.php?id=+'.$keywrd.'");</script>'; 
	  echo '<script>swal("Success","Record has been updated.");</script>'; }
	
	else { echo '<script>swal("Error","Failed to update. Recheck every entry field.", "error");</script>'; }	
  }

  //Part: Insert New
  if((isset($_POST['feederName'])) && (trim($_POST['vesselid']) == '')) {
	$notValid=0;
	$query="Select * From m_vessel Where operatorID='".$_POST['feederName']."' And vesselName='".strtoupper($_POST['vesselName'])."'
	          And voyage='".strtoupper($_POST['voyage'])."' And portLoading='".$_POST['POL']."' And portDischarge='".$_POST['POD']."'
			  And ETD='".$_POST['etd']."' And ETA='".$_POST['eta']."'";
    $result=mssql_query($query);	 
	if(mssql_num_rows($result) >= 1) { $notValid=1; }
	mssql_free_result($result); 
	
	if($notValid == 0) {
	  if($_POST['POD'] == $_POST['POL']) { $notValid=1; }
	}
	  
	if($notValid == 0) {  
      $keywrd=$_POST['vesselid'];
    
	  $inOut = "I";
	  if(isset($_POST['POL'])) { $inOut="O"; }
      
	  $query="Declare @LastIndex VarChar(10), @LastKey Int; 
	          If Not Exists(Select * From logKeyField Where keyFName Like '".$_POST['feederName'].'%'."') Begin
			    Insert Into logKeyField(keyFName, lastNumber) Values('".$_POST['feederName']."', 1);
				Set @LastKey = 1;
			  End Else Begin  
			        Select @LastKey=lastNumber+1 From logKeyField Where keyFName Like '".$_POST['feederName'].'%'."';
			      End;
			  Set @LastIndex = LTRIM(RTRIM(CONVERT(VARCHAR(6),@LastKey)));
	          Insert Into m_vessel(operatorID, vesselName, voyage, IO, portLoading, portDischarge, ETD, ETA, vesselid) 
			  Values('".$_POST['feederName']."', '".strtoupper($_POST['vesselName'])."', '".strtoupper($_POST['voyage'])."', '$inOut', 
			  '".$_POST['POL']."', '".$_POST['POD']."', '".$_POST['etd']."', '".$_POST['eta']."', @LastIndex); ";	  
	  $result=mssql_query($query); 	  
	  echo '<script>$("#result").load("list-vessel.php?id=+'.$_POST['feederName'].'");</script>'; }
    
    else { echo '<script>swal("Error","Failed to update. Recheck every entry field.", "error");</script>'; }	  
  }	  
</script>

<div class="w3-container"><h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;color:#3498db">Vessel Registration</h2>

<div class="height-20"></div>
  <form id="fRegVessel" method="post">
	  <label class="w3-text-teal">Feeder Operator Name</label>
	  <script language="php">
	    echo '<input type="hidden" name="vesselid" value='.$keywrd.'>';
	    if(isset($_GET['id'])) { 
		  
 	      $query="Select custRegID, completeName From m_Customer Where custRegID='$operatorFeeder'";
		  $result=mssql_query($query);		
		  if(mssql_num_rows($result) > 0) {
		    $arr=mssql_fetch_array($result);
		    echo '<input type="text" readonly class="w3-input w3-border w3-light-grey" name="feederName" value="'.$arr[1].'" >'; 
			mssql_free_result($result); }
			
		  else { echo '<input type="text" readonly class="w3-input w3-border w3-light-grey" name="feederName" value='.$operatorFeeder.' >'; }		  
		}
		else { 
 	      $query="Select custRegID, completeName From m_Customer Where asFeed=1 Order By completeName";
		  $result=mssql_query($query);		
		  echo '<select name="feederName" class="w3-select w3-border">';
		  while($arr=mssql_fetch_array($result)) { echo "<option value=".$arr[0].">&nbsp;".$arr[1]."&nbsp;</option>"; }
		  echo '</select>';
		  mssql_free_result($result); }
	  </script>
      <div class="height-5"></div>	  

	  <label class="w3-text-teal">Vessel Name</label>
	  <input class="w3-input w3-border" type="text" name="vesselName" maxlength="50" style="text-transform:uppercase;" required value='<?php echo $vesselName;?>'>
      <div class="height-10"></div>	  
	  
	  <label class="w3-text-teal">Voyage Number</label>
	  <input class="w3-input w3-border" type="text" name="voyage" maxlength="10" style="text-transform:uppercase;" required value='<?php echo $voyage;?>'>      
	  <div class="height-10"></div>	  
	  
	  <label class="w3-text-teal">Port Of Loading (POL)</label>
	  <select name="POL" class="w3-select w3-border">
	  <script language="php">
	    $query="Select portID, portCode, portDescription From m_harbour Order By portCode";
		$result=mssql_query($query);
		while($arr=mssql_fetch_array($result)) {
		  if($arr[1] == $POL) { echo "<option selected value='".$arr[1]."'>&nbsp;".$arr[2]."&nbsp;</option>"; }
		  else { echo "<option value='".$arr[1]."'>&nbsp;".$arr[2]."&nbsp;</option>"; }
		}
		mssql_free_result($result);
	  </script>
	  </select>
      <div class="height-10"></div>	  
	  
	  <label class="w3-text-teal">Port Of Discharge (POD)</label>
	  <select name="POD" class="w3-select w3-border">
	  <script language="php">
	    $query="Select portID, portCode, portDescription From m_harbour Order By portCode";
		$result=mssql_query($query);
		while($arr=mssql_fetch_array($result)) {
		  if($arr[1] == $POD) { echo "<option selected value='".$arr[1]."'>&nbsp;".$arr[2]."&nbsp;</option>"; }
		  else { echo "<option value'".$arr[1]."'>&nbsp;".$arr[2]."&nbsp;</option>"; }
		}
		mssql_close($dbSQL);
	  </script>	  
	  </select>      
	  <div class="height-10"></div>	  
	  
	  <label class="w3-text-teal">E T D POL</label>
	  <input class="w3-input w3-border" type="date" name="etd" required pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" 
	  title="Year-Month-Date" value='<?php echo $ETD; ?>'>
      <div class="height-10"></div>	  
	  
	  <label class="w3-text-teal">E T A POD</label>
	  <input class="w3-input w3-border" type="date" name="eta" required
	    pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" title="Year-Month-Date" value='<?php echo $ETA;?>'>
      <div class="height-20"></div>	  	  
	  <script language="php">
	    if(isset($_GET['id'])) { echo '<input type="submit" class="w3-btn w3-pink" name="update_field" value="Update" />'; }
        else { echo '<input type="submit" class="w3-btn w3-blue" name="register" value="Register" />'; }
	  </script>
  </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
<script type="text/javascript">
  $(document).ready(function(){
    $("#fRegVessel").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("reg-vessel.php", formValues, function(data){ $("#result").html(data); });
    });	  
  });  
</script> 