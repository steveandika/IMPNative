<script language="php">
  session_start();
  include("../asset/libs/db.php");
  
  if(isset($_GET['noCnt'])) {
	$NoContainer = strtoupper($_GET['noCnt']);
    if(strlen($NoContainer) == 11) {
	  $result = validUnitDigit($NoContainer);
	  $location = $_GET['location'];
	  $eventDate = $_GET['eventDate'];
	  
	  if($result != 'OK') {echo '<script>swal("Warning",'.$result.');</script>';}
	  
	  $query = "Select * From containerJournal Where NoContainer = '$NoContainer' And gateOut Is Null and gateIn Is Not Null; ";
	  $result = mssql_query($query);
	  $numRecord = mssql_num_rows($result);
		  
      if($numRecord > 0) {
	    while($arr = mssql_fetch_array($result)) {$location = $arr["workshopID"];}
      }		  
	  mssql_free_result($result);
	  
	  if($numRecord <= 0) {
        $sizeCont = $_GET['contSize'];
	    $typeCont = $_GET['contType'];
	    $heightCont = $_GET['contHeight'];
	    $MnfrYear = $_GET['mnfr'];
	    $Construction = $_GET['constr'];
	    $Vent = $_GET['vent'];
	    
	    $eventTime = date('h:i');
	    	
	    $cond = "DM";
  	    $pending = "Y";
	    $iscleaning = 0;
	    $isrepair = 0;
	  
	    $kodeBook = str_ireplace("-", "", $eventDate);
	    $kodeBook = substr($kodeBook,0,1).substr($kodeBook,2,4);
	    $kodeBook = "HMP".$kodeBook."/".$location;	
	
	    $query = "Declare @KodeBookIn VarChar(30);
	              If Not Exists(Select ContainerNo From containerLog Where ContainerNo = '$NoContainer') Begin
 		            Insert Into containerLog(ContainerNo, Ventilasi, Mnfr, grossWeight, Size, Type, Height, Constr)
					Values('$NoContainer', ".$Vent.", '$MnfrYear', 0, '$sizeCont', '$typeCont', '$heightCont', '$Construction');
			      End; 
			  
			      If Not Exists(Select NoContainer From containerJournal Where gateOut Is Null And NoContainer = '$NoContainer') Begin			  
                    Declare @LastIndex Int; 
	                  
					If Not Exists(Select * From logKeyField Where keyFName Like '".$kodeBook.'%'."') Begin
			          Insert Into logKeyField(keyFName, lastNumber) Values('".$kodeBook."',1);
					  Set @KodeBookIn=CONCAT('".$kodeBook."','.1');			            
			        End Else Begin  
			              Select @LastIndex=lastNumber+1 From logKeyField Where keyFName Like '".$kodeBook.'%'."';
                          Update logKeyField Set lastNumber=lastNumber+1 Where keyFName Like '".$kodeBook.'%'."';                            
						  
						  Set @KodeBookIn=CONCAT('".$kodeBook."','.', RTRIM(LTRIM(CONVERT(VARCHAR(15),@LastIndex)))); 
			            End;	
                      
                    Insert Into tabBookingHeader(bookID, bookType, blID, principle, vessel, consignee, operatorID, voyageID, SLDFileName) 
			                              Values(@KodeBookIn, 0, @KodeBookIn, '', '', '', '', '', '');
 					  
					Insert Into containerJournal(bookInID, NoContainer, gateIn, jamIn, Cond, isPending, Remarks, isCleaning, isRepair, workshopID)
					                      Values(@KodeBookIn, '$NoContainer', '$eventDate', '$eventTime', '$cond', '$pending', '', ".$iscleaning.", ".$isrepair.", '$location');
					  
			        Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                                Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 120), CONCAT('Hamparan Masuk: ', @KodeBookIn)); 	
                  End; ";  
        $result = mssql_query($query);
/*
				  If Exists(Select NoContainer From containerJournal Where gateIn Is Null And NoContainer = '$NoContainer') Begin
				    Select @KodeBookIn = bookInID From containerJournal Where NoContainer = '$NoContainer' And gateIn Is Null;
			    
				    Update containerJournal Set gateIn = '$eventDate', Cond = '$cond', isPending = '$pending', 
				                                isCleaning = ".$iscleaning.", isRepair = ".$isrepair.", workshopID = '$location' 
				    Where NoContainer = '$NoContainer' And gateIn Is Null;
				
			        Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 120), CONCAT('Hamparan Masuk: ', @KodeBookIn)); 				
			     End Else Begin	  
                        

*/		
		
		$url = "/e-imp/mnr/?do=hw_registry&success=1&lastLoc=".$location;
	  }
	  else {$url = "/e-imp/mnr/?do=hw_registry&success=0&lastLoc=".$location;}
		  
      mssql_close($dbSQL);	
	  echo "<script type='text/javascript'>location.replace('$url');</script>";   	  
    }
	else {
	  echo '<script>swal("Error","Length of Container Number must be 11 digit");</script>';	
	  $url="/e-imp/mnr/?do=hw_registry&success=1&lastLoc=".$location;
      echo "<script type='text/javascript'>location.replace('$url');</script>";  
	}
  }
</script>
  
<div class="w3-container"> 
 <div style="border:1px solid #f7f9f9;background:#fff;-moz-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);-webkit-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);
             box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16)">
   <h3 style="padding:0 0 5px 0;border-bottom:1px solid #839192">&nbsp;&nbsp;Event In Container Into Hamparan</h3>  	
   <div style="padding:10px 10px 15px 15px;border:0"> 
    <label class="w3-text-grey" style="font-size:13px">Catatan:<br>
	  &nbsp;&nbsp;1.&nbsp;&nbsp;Masukan data tanggal tanpa tanda baca (contoh: "-","/")<br>
      &nbsp;&nbsp;2.&nbsp;&nbsp;Format Tanggal: yyyyMMdd (contoh: 20171101).</label>
   </div>
	
	<div style="padding:10px 30px 15px 30px">       
	               
	 <form id="fheaderIn" method="get">
	    <input type="hidden" name="do" value="hw_registry">
		   
        <div class="w3-row-padding">
		 <div class="w3-half">
		  <label>Hamparan Location</label>
		  <select name="location" class="w3-select w3-border">
		  
<script language="php">
  $query = "Select * From m_Location Order By locationDesc ";
  $result = mssql_query($query);
  while($arr=mssql_fetch_array($result)) { 
    if(isset($_GET['lastLoc'])) {
	  if($_GET['lastLoc'] == $arr[0]) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';}
    }		
	echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';
  }
  mssql_free_result($result);
</script>  
  
          </select>
         </div>
		 <div class="w3-half">
		  <label>Date In Hamparan</label>
		  <input class="w3-input w3-border" type="text" name="eventDate" id="fDate" required value="<?php echo date("Y-m-d")?>"
         	  title="Year-Month-Date" onKeyUp=dateSeparator("fDate") />			  
		 </div>
		</div>	
        <div class="height-5"></div>		
        
		<div class="w3-row-padding">  
		 <div class="w3-half">
           <label>Container Number</label>
           <input class="w3-input w3-border" type="text" name="noCnt" maxlength="11" style="text-transform:uppercase" id="noCnt" required />           
		 </div>
         <div class="w3-half">&nbsp;</div>		 
		</div>    
		<div class="height-5"></div>                        
        
        <div class="w3-container">
		  <label>Container Size, Type, Height</label>
        </div>		
        <div class="w3-row-padding">
		 <div class="w3-third">
           <select name="contSize" class="w3-select w3-border">

<script language="php">		   
  $query = "Select * From containerLog Where ContainerNo = '$keywrd'; ";
  $result = mssql_query($query);    
  if(mssql_num_rows($result) > 0) {
    while($arr = mssql_fetch_array($result)) {
      $size = $arr['Size'];
      $tipe = $arr['Type']; 
	  $height = $arr['Height'];
	  $mnfr = $arr['Mnfr'];
	  $constr = $arr['Constr'];
    }
  }
  mssql_free_result($result);
 
  if($size != '') {
	if($size == "20") {echo '<option selected value="20">&nbsp;20&nbsp;</option>';}
	else {echo '<option value="20">&nbsp;20&nbsp;</option>';}
	if($size == "40") {echo '<option selected value="20">&nbsp;20&nbsp;</option>';}
	else {echo '<option value="20">&nbsp;20&nbsp;</option>';}
	if($size == "45") {echo '<option selected value="20">&nbsp;20&nbsp;</option>';}
	else {echo '<option value="20">&nbsp;20&nbsp;</option>';}	  
  }
  else {
    echo '<option value="20">&nbsp;20&nbsp;</option>
	      <option value="40">&nbsp;40&nbsp;</option>
	      <option value="45">&nbsp;45&nbsp;</option>';
  }
</script>
	
           </select>
 		   <div class="height-5"></div> 
	 	  </div> 
		  <div class="w3-third">
           <select name="contType" class="w3-select w3-border">
			  
<script language="php">				  
  if($tipe != '') {
	if($tipe == "GP") {echo '<option selected value="GP">&nbsp;GP&nbsp;</option>';}
	else {echo '<option value="GP">&nbsp;GP&nbsp;</option>';}
	if($tipe == "OT") {echo '<option selected value="OT">&nbsp;OT&nbsp;</option>';}
	else {echo '<option value="OT">&nbsp;OT&nbsp;</option>';}
	if($tipe == "OS") {echo '<option selected value="OS">&nbsp;OS&nbsp;</option>';}
	else {echo '<option value="OS">&nbsp;OS&nbsp;</option>';}
	if($tipe == "FR") {echo '<option selected value="FR">&nbsp;FR&nbsp;</option>';}
	else {echo '<option value="FR">&nbsp;FR&nbsp;</option>';}
	if($tipe == "TW") {echo '<option selected value="TW">&nbsp;TW&nbsp;</option>';}
	else {echo '<option value="TW">&nbsp;TW&nbsp;</option>';}
	if($tipe == "RF") {echo '<option selected value="RF">&nbsp;RF&nbsp;</option>';}
	else {echo '<option value="RF">&nbsp;RF&nbsp;</option>';}
	if($tipe == "TK") {echo '<option selected value="TK">&nbsp;TK&nbsp;</option>';}
	else {echo '<option value="TK">&nbsp;TK&nbsp;</option>';}
	if($tipe == "VT") {echo '<option selected value="VT">&nbsp;VT&nbsp;</option>';}
	else {echo '<option value="VT">&nbsp;VT&nbsp;</option>';}
	if($tipe == "BK") {echo '<option selected value="BK">&nbsp;BK&nbsp;</option>';}
	else {echo '<option value="BK">&nbsp;BK&nbsp;</option>';}
	if($tipe == "OTH") {echo '<option selected value="OTH">&nbsp;OTW&nbsp;</option>';}
	else {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  
  }
  else {
    echo '<option value="GP">&nbsp;GP&nbsp;</option>
	      <option value="OT">&nbsp;OT&nbsp;</option>
	      <option value="OS">&nbsp;OS&nbsp;</option>
	      <option value="FR">&nbsp;FR&nbsp;</option>
	      <option value="TW">&nbsp;TW&nbsp;</option>
	      <option value="RF">&nbsp;RF&nbsp;</option>
	      <option value="TK">&nbsp;TK&nbsp;</option>
	      <option value="VT">&nbsp;VT&nbsp;</option>
	      <option value="BK">&nbsp;BK&nbsp;</option>
	      <option value="OTH">&nbsp;OTH&nbsp;</option>';
  }
</script>
	
           </select>
		   <div class="height-5"></div>
		  </div> 
		  <div class="w3-third">
            <select name="contHeight" class="w3-select w3-border">
			
<script language="php">			  
  if($height != '') {
    if($height == "STD") {echo '<option selected value="STD">&nbsp;STD&nbsp;</option>';}
    else {echo '<option value="STD">&nbsp;STD&nbsp;</option>';}	  
    if($height == "HC") {echo '<option selected value="HC">&nbsp;HC&nbsp;</option>';}
    else {echo '<option value="HC">&nbsp;HC&nbsp;</option>';}	  
    if($height == "OTH") {echo '<option selected value="OTH">&nbsp;OTH&nbsp;</option>';}
    else {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  
  }
  else {
    echo '<option value="STD">&nbsp;STD&nbsp;</option>
	      <option value="HC">&nbsp;HC&nbsp;</option>
	      <option value="OTH">&nbsp;OTH&nbsp;</option>';
  }				
</script>
	
           </select>			  
		 </div> 	 
	    </div>			
		<div class="height-5"></div>

        <div class="w3-container">
		  <label>(Optional) Container Mnfr Year, Construction, Ventilation</label>
        </div>		
		
		<div class="w3-row-padding">
		 <div class="w3-third">
		   <input class="w3-input w3-border" type="text" name="mnfr" maxlength="10" value="<?php echo $mnfr?>" />  
		   <div class="height-5"></div>
		 </div> 
		 <div class="w3-third">
           <select name="constr" class="w3-select w3-border">

<script language="php">		   
  if($constr != '') {
	if($constr == "STL") {echo '<option selected value="STL">&nbsp;STL&nbsp;</option>';}
	else {echo '<option value="STL">&nbsp;STL&nbsp;</option>';} 
	if($constr == "AL") {echo '<option selected value="AL">&nbsp;AL&nbsp;</option>';}
	else {echo '<option value="AL">&nbsp;AL&nbsp;</option>';} 
	if($constr == "FRP") {echo '<option selected value="FRP">&nbsp;FRP&nbsp;</option>';}
	else {echo '<option value="FRP">&nbsp;FRP&nbsp;</option>';} 	  
  }
  else {	
   echo '<option value="STL">&nbsp;STL&nbsp;</option>
	     <option value="AL">&nbsp;AL&nbsp;</option>
	     <option value="FRP">&nbsp;FRP&nbsp;</option>';
  }
  mssql_close($dbSQL);  

 if(isset($_GET['success'])) {
   if($_GET['success'] == 0) {echo '<script>swal("Duplicated","Said Container number still listed on your field/Hamparan.");</script>';}
   if($_GET['success'] == 1) {echo '<script>swal("Success","Data has been inserted into Container Journal");</script>';}   
 }     
</script>
	
           </select>
           <div class="height-5"></div>		   
		 </div> 			
		 <div class="w3-third">
		   <input class="w3-input w3-border" type="text" name="vent" onkeypress="return isNumber(event)" maxlength="1" value="1" required />  
		 </div> 			
		</div>			
			
		<div class="height-20"></div>	
        <div class="w3-container">
		  <button type="submit" class="w3-button w3-blue w3-round-small">Save</button>
        </div>			
	 </form>

   </div>
 </div>
</div>

<script>
  function dateSeparator(varID) {
    var str = document.getElementById(varID).value;
	panjang = str.length;
	if (panjang==8) {
      var partYear = str.slice(0,4);
	  var partMonth = str.slice(4,6); 
	  var partDate = str.slice(6,8);
	  
	  result = partYear.concat('-', partMonth, '-', partDate);
	  document.getElementById(varID).value = result;
	} 		 
  }
</script>