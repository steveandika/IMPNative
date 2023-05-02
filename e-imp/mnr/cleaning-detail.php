<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  if(isset($_POST['keywrd_hidden'])) {
	if(strtotime($_POST["tglIn"]) <= strtotime($_POST["datecleaning"])) {
      $keywrd=$_POST["keywrd_hidden"];
	  $kodeBooking=$_POST["bookID"];
	  $cleaning=$_POST["cleaningType"];
	  
	  $tglCleaning=$_POST["datecleaning"];
	  $postTgl=trim(str_ireplace("-","",$tglCleaning));
	  $postTgl="CLG".trim(substr($postTgl, 0, 1).substr($postTgl, 2,6)).".".$_SESSION["location"];
	
 	  if($cleaning=="WW") { $remark="LIGHT CLEANING"; }
	  if($cleaning=="DW") { $remark="MEDIUM CLEANING"; }
	  if($cleaning=="CC") { $remark="HEAVY CLEANING"; }
	  if($cleaning=="SC") { $remark="SPECIAL CLEANING"; }
	
	  $location="I";
	
	  $query="Select * From CleaningHeader Where containerID='$keywrd' And bookID='$kodeBooking' ";
	  $result=mssql_query($query);
	  $numRows=mssql_num_rows($result);
	
	  if($numRows <= 0) {
	    mssql_free_result($result);
	  
	    $query="Declare @NewDraft VarChar(30),@LastIndex Int, @Keywrd VarChar(11), @ToDay_ VarChar(10);  
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
                Values(@NewDraft, '$keywrd_hidden', @Today_, 0, '$kodeBooking', '');			 
                Insert Into CleaningDetail(cleaningID, locationID, materialValue, Remarks, repairID) 
                Values(@NewDraft, '$location', 0, '$remark', '$cleaning');";
	    $result=mssql_query($query);
	    mssql_close($dbSQL);
	    echo '<script>swal("Success", "Record has been saved into database")</script>';		  
	  }
	  else { echo '<script>swal("Duplicate record found", "Said Container Number has been saved into Cleaning Log.", "error")</script>'; }
	
	  mssql_close($dbSQL);
	}
	else { echo '<script>swal("Invalid input found", "Cleaning Date should not less than Gate In Event.", "error")</script>'; }
	
  }
  
  if(isset($_POST["noCnt"])) {
	$keywrd=$_POST["noCnt"];
    $query="Select CONCAT(b.Size,'/', b.Type, '/', b.Height) As IsoCode, a.bookInID, Format(a.gateIn,'yyyy-MM-dd') As DTMin 
	        From containerJournal a
			Inner Join containerLog b On b.ContainerNo=a.NoContainer
			Inner Join tabBookingHeader c On c.BookID=a.bookInID
			Where a.gateOut IS NULL And a.NoContainer='$keywrd' And c.isCleaning=1 ";
	if($_SESSION["location"] != "ALL") { $query=$query."And locationID='".$_SESSION["location"]."'"; }
	
	$result=mssql_query($query);
	$rowCount=mssql_num_rows($result);
	if($rowCount > 0){
	  $arr=mssql_fetch_array($result);
	  $isocode=$arr[0];
	  $kodeBooking=$arr[1];
	  $tglIn=$arr[2];
	}
	else {
	  $isocode='';
	  echo '<script>swal("Error", "Container was not found in active stock", "error")</script>';
	}		
    mssql_close($dbSQL);
</script> 

<form id="fcleaning" method="post">
  <div class="w3-container">
    <input type="hidden" readonly name="keywrd_hidden" value='<?php echo $keywrd;?>'> 
	<input type="hidden" readonly name="bookID" value='<?php echo $kodeBooking;?>'> 
	<input type="hidden" readonly name="tglIn" value='<?php echo $tglIn;?>'>
	
    <label class="w3-text-teal">Size/Type/Height</label>
    <input type="text" readonly class="w3-input" name="isocode" value=<?php echo $isocode;?>> 
  </div>
  <div class="height-10"></div>
  <div class="w3-row-padding">
    <div class="w3-half">
      <label class="w3-text-teal">Cleaning Type</label>
      <select name="cleaningType" class="w3-select w3-border">
        <option value="WW">&nbsp;LIGHT&nbsp;</option>
        <option value="DW">&nbsp;MEDIUM&nbsp;</option>
        <option value="CC">&nbsp;HEAVY&nbsp;</option>
        <option value="SC">&nbsp;SPECIAL&nbsp;</option>
      </select>
    </div>
    <div class="w3-half">
      <label class="w3-text-teal">Activity Date</label>
      <input class="w3-input w3-border" type="date" name="datecleaning" required
	         pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value='<?php echo $tglIn;?>' title="Year-Month-Date" />
    </div>  
  </div>
  <div class="height-20"></div>

  <div class="w3-container">
    <input type="submit" class="w3-btn w3-border w3-light-grey w3-text-blue" name="register" value="Register" />
  </div>
</form>

<script language="php">
  }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#fcleaning").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();	 
      $.post("cleaning-detail.php", formValues, function(data){ $("#content").html(data); });
    });	 
  }); 
</script>