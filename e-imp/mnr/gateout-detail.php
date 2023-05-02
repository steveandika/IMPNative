<script language="php">
  include ("../asset/libs/db.php");
  
  if(isset($_POST["noCnt"])) {
    $keywrd = $_POST["noCnt"];
	$kodeBooking = '';
	$failed = 0;
	
	$query = "Select isPending, b.bookID From containerJournal a 
	          Inner Join tabBookingHeader b On b.bookID=a.bookInID
	          Where a.noContainer='$keywrd' And a.gateOut Is Null ";
	$result = mssql_query($query);
	if(mssql_num_rows($result) <= 0) { 
	  echo '<script>swal("Rejected","Said Container still Damage","error")</script>'; 
	  $faield++;
	}
	else {
	  $fetchArr = mssql_fetch_array($result);
	  $kodeBooking = $fetchArr[1];	 	  	  
	}
	mssql_free_result($result);
	  
	if($failed == 0) {  
      $query = "Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, c.Mnfr, c.Constr, a.workshopID, Format(a.gateIn, 'yyyy-MM-dd') As dateIn, a.JamIn 	          
                From containerJournal a 
	  	        Inner Join tabBookingHeader b On b.bookID=a.bookInID
		        Inner Join containerLog c On c.ContainerNo = a.NoContainer 			  
		        Where (a.NoContainer='$keywrd') And (a.bookInID='$kodeBooking') ";	  
	  $result = mssql_query($query);
	  if(mssql_num_rows($result) == 1) {
	    $fetchArr=mssql_fetch_array($result);
	    $contSize=$fetchArr[2];
	    $contType=$fetchArr[3];
	    $contHeight=$fetchArr[4];
	    $const=$fetchArr[6];
	    $mnfrYear=$fetchArr[5];
	    $dateOut=date("Y-m-d");	
	    $dtmin=$fetchArr[8]." ".$fetchArr[9]; 
	    $location=$fetchArr[7];
	  }
	  mssql_free_result($result);
      	  
</script>

<form id="fDetailOut" method="post" action="store-gateout">
  <script language="php">
    echo '<input type="hidden" name="nocontainer" value='.$keywrd.'>';
	echo '<input type="hidden" name="kodeBooking" value='.$kodeBooking.'>';
	echo '<input type="hidden" name="location" value='.$location.'>';
  </script>
 
  <div class="w3-row-padding">
    <div class="w3-third">
      <label>Container Size</label>
      <input type="text" name="contSize" class="style-input" readonly value="<?php echo $contSize?>" />	
    </div>
    <div class="w3-third">
      <label>Container Type</label>
	  <input type="text" name="contType" class="style-input" readonly value="<?php echo $contType?>" />	
    </div>
    <div class="w3-third">
      <label>Container Height</label>
	  <input type="text" name="contHeight" class="style-input" readonly value="<?php echo $contHeight?>" />		  
    </div>  
  </div>
  <div class="height-10"></div>

  <div class="w3-row-padding">
    <div class="w3-third">
      <label>Construction</label>
      <input type="text" name="construction" class="style-input" readonly value="<?php echo $const?>" />	    
    </div>
    <div class="w3-third">
      <label>Manufacture</label>
      <input type="text" name="mnfrYear" class="style-input" readonly value="<?php echo $mnfrYear?>" />	    	  
    </div>  
    <div class="w3-third">
      <label>Hamparan Date In</label>
      <input type="text" name="dtmin" class="style-input" readonly value="<?php echo $dtmin?>" />	    	  
    </div>  	
  </div>
  <div class="height-10"></div>
  
  <div class="w3-row-padding">
    <div class="w3-third">
      <label>Hamparan Date Out</label>
      <input class="style-input style-border" type="text" name="dtmout" id="fDate" required value=<?php echo date("Y-m-d");?> title="Year-Month-Date" onKeyUp="dateSeparator()" /> 
    </div>	
    <div class="w3-twothird">&nbsp;</div>    
  </div>  
  <div class="height-20"></div>
  <div class="w3-row-padding">
    <div class="w3-third">
     <button type="submit" class="w3-button w3-blue w3-round-small">Update Event</button>
	</div>
    <div class="w3-twothird"></div>	
  </div>
</form>

<script language="php">		
    }
  }
  mssql_close($dbSQL);
</script>

<script>
  function dateSeparator() {
    var str = document.getElementById("fDate").value;
	panjang = str.length;
	if (panjang==8) {
      var partYear = str.slice(0,4);
	  var partMonth = str.slice(4,6); 
	  var partDate = str.slice(6,8);
	  
	  result = partYear.concat('-', partMonth, '-', partDate);
	  document.getElementById("fDate").value = result;
	} 		 
  }
</script>