<script language="php">
  session_start();   
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>   
  <script src="../asset/js/sweetalert2.min.js"></script>
</head>

<body> 
<script language="php">  
  include("../asset/libs/db.php"); 
  if(isset($_GET['act'])) {	 
    $unit = strtoupper($_GET['unit']);
	$bookInID = strtoupper($_GET['transid']);
    $sizeCont = $_GET['contSize'];
	$typeCont = $_GET['contType'];
	$heightCont = $_GET['contHeight'];
	$MnfrYear = $_GET['mnfr'];
	$Construction = $_GET['constr'];
	$Vent = $_GET['vent'];
	$eventDate = $_GET['eventDate'];
	$eventTime = date('h:i');
	$vessel = $_GET['vessel'];
	$voyageNum = $_GET['voyage'];
	$location = $_GET['location'];	
	$principle = $_GET['mlo'];
	$consignee = $_GET['consignee'];
	
	$failed = 0;
	$query = "Select * From containerLog Where ContainerNo = '$unit'; ";
	$result = mssql_query($query);
	if(mssql_num_rows($result) <= 0) {$failed++;}
	mssql_free_result($result);
	$query = "Select * From tabBookingHeader Where bookID = '$bookInID'; ";
	$result = mssql_query($query);
	if(mssql_num_rows($result) <= 0) {$failed++;}
	mssql_free_result($result);
	$query = "Select * From containerJournal Where bookInID = '$bookInID' And NoContainer = '$unit' And gateOut Is Null; ";
	$result = mssql_query($query);
	if(mssql_num_rows($result) <= 0) {$failed++;}
	mssql_free_result($result);
	
	if($failed == 0) {	  	  
	  $do = "Update containerLog Set Ventilasi = ".$Vent.", Mnfr = '$MnfrYear', grossWeight = 0, 
	                                 Size = '$sizeCont', Type = '$typeCont', Height = '$heightCont', Constr = '$Construction'
			 Where ContainerNo = '$unit';             
			 Update containerJournal Set gateIn = '$eventDate', workshopID = '$location'
			 Where NoContainer = '$unit' And bookInId = '$bookInID';
			 Update tabBookingHeader Set vessel = '$vessel', voyageID = '$voyageNum', principle = '$principle', consignee = '$consignee' Where bookID = '$bookInID';
			 
			 Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	         Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 120), CONCAT('Update Profile ', '$unit', ' Book ID ', '$bookInID')); ";
      $result = mssql_query($do);			 
	  
	  $url = '/e-imp/mnr/?do=cont_list&noCnt='.$unit.'&location='.$location.'&query=Start+Query';
	  echo "<script type='text/javascript'>location.replace('$url');</script>";       
    }
	else {
	  echo '<script>swal("Error","Update was failed. '.$unit.'" not found in Container Log");</script>';
    }
  }
  
  if(isset($_GET['unit']) && isset($_GET['transid']) && !isset($_GET['act'])) {	
    $unit = $_GET['unit'];
    $dtmin = $_GET['dtmin'];
	$location = $_GET['wrkid'];
	$bookID = $_GET['transid'];

    $size = '';
    $tipe = '';
    $height = '';
    $mnfr = '';
    $constr = '';
    $query = "Select * From containerLog Where ContainerNo = '$unit'; ";
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
	
	$vessel = '';
	$voyage = '';
	$principle = '';
	$consignee = '';
	$query = "Select vessel, voyageID, principle, consignee From tabBookingHeader Where bookID = '$bookID' ";
	$result = mssql_query($query);
	while($arr = mssql_fetch_array($result)) {
      $vessel = $arr['vessel'];
	  $voyage = $arr['voyageID'];
	  $principle = $arr['principle'];
	  $consignee = $arr['consignee'];
	}
	mssql_free_result($result);
	
    echo '<fieldset style="padding-left:0 40px 0 40px; background-color:#f1f1f1"> 
            <legend style="background-color:#fff;font-size:13px" class="w3-text-grey">&nbsp;Container Detail&nbsp;</legend>
            <div class="height-10"></div>		  
	  	    <label class="w3-text-grey">Notes For Date Field:<br>
		     &nbsp;&nbsp;1.&nbsp;&nbsp;Enter a date without punctuation (i.e "-","/")<br>
		     &nbsp;&nbsp;2.&nbsp;&nbsp;Date format yyyyMMdd (i.e 20171101).</label>
		    <div class="height-20"></div>	
			
			<form id="fprofile" method="get" action="contprofile.php">
			 <input type="hidden" name="unit" value='.$unit.'> 
			 <input type="hidden" name="transid" value='.$bookID.'> 
			 <input type="hidden" name="act" value="uprofile">
			 
			 <div class="w3-row-padding">
			  <div class="w3-third">
			   <label>Workshop Location</label>
	           <select name="location" class="w3-select w3-border">';		   
			   
    $query = "Select * From m_Location Order By locationDesc ";
    $result = mssql_query($query);    
    while($arr = mssql_fetch_array($result)) { 
      if($location == $arr[0]) {echo '      <option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';}
	  else {echo '      <option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
    }			   
  			
    echo '     </select>
	          </div>
              <div class="w3-third">
			   <label>In Event</label>			  
			   <input class="w3-input w3-border" type="text" name="eventDate" id="fDate" maxlength="8" required value='.$dtmin.' 
				  title="Year-Month-Date" onKeyUp=dateSeparator("fDate") />						   
              </div>
			  <div class="w3-third">&nbsp;</div>
			 </div>
			 
             <div class="height-10"></div>			 
             <div class="w3-row-padding">			  
			  <div class="w3-third">
	           <label>Size</label>
               <select name="contSize" class="w3-select w3-border">';  
    
    if($size != '')   {echo '      <option selected value='.$size.'>&nbsp;'.$size.'&nbsp;</option>';}
	if($size != "20") {echo '      <option value="20">&nbsp;20&nbsp;</option>';}
	if($size != "40") {echo '      <option value="40">&nbsp;40&nbsp;</option>';}
	if($size != "45") {echo '      <option value="45">&nbsp;45&nbsp;</option>';}	  
	
    echo '     </select>			  
	          </div>
              <div class="w3-third">
  	           <label>Type</label>
               <select name="contType" class="w3-select w3-border">';			  
    if($tipe != '')    {echo '    <option selected value='.$tipe.'>&nbsp;'.$tipe.'&nbsp;</option>';}
	if($tipe != "GP")  {echo '    <option value="GP">&nbsp;GP&nbsp;</option>';}	
	if($tipe != "OT")  {echo '    <option value="OT">&nbsp;OT&nbsp;</option>';}
	if($tipe != "OS")  {echo '    <option value="OS">&nbsp;OS&nbsp;</option>';}
	if($tipe != "FR")  {echo '    <option value="FR">&nbsp;FR&nbsp;</option>';}
	if($tipe != "TW")  {echo '    <option value="TW">&nbsp;TW&nbsp;</option>';}
	if($tipe != "RF")  {echo '    <option value="RF">&nbsp;RF&nbsp;</option>';}
	if($tipe != "TK")  {echo '    <option value="TK">&nbsp;TK&nbsp;</option>';}
	if($tipe != "VT")  {echo '    <option value="VT">&nbsp;VT&nbsp;</option>';}
	if($tipe != "BK")  {echo '    <option value="BK">&nbsp;BK&nbsp;</option>';}
	if($tipe != "OTH") {echo '    <option value="OTH">&nbsp;OTH&nbsp;</option>';}	  
	
    echo '     </select>			
              </div> 	
              <div class="w3-third">			  
  	           <label>Height</label>
               <select name="contHeight" class="w3-select w3-border">';			 
    if($height != '')    {echo '    <option selected value='.$height.'>&nbsp;'.$height.'&nbsp;</option>';}
    if($height != "STD") {echo '    <option value="STD">&nbsp;STD&nbsp;</option>';}	  
    if($height != "HC")  {echo '    <option value="HC">&nbsp;HC&nbsp;</option>';}	  
    if($height != "OTH") {echo '    <option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  

    echo '     </select>	
              </div>	
			 </div> 
             <div class="height-10"></div>
			 <div class="w3-row-padding">
			  <div class="w3-third"> 
	  	       <label>Manufacture</label>
		       <input class="w3-input w3-border" type="text" name="mnfr" maxlength="10" value="'.$mnfr.'"  />  
			  </div>
			  <div class="w3-third">		     
               <label>Construction</label>
               <select name="constr" class="w3-select w3-border">';	
    if($constr != '')    {echo '    <option selected value='.$constr.'>&nbsp;'.$constr.'&nbsp;</option>';}
	if($constr != "STL") {echo '    <option value="STL">&nbsp;STL&nbsp;</option>';} 
	if($constr != "AL")  {echo '    <option value="AL">&nbsp;AL&nbsp;</option>';} 
	if($constr != "FRP") {echo '    <option value="FRP">&nbsp;FRP&nbsp;</option>';} 	  
	
    echo '     </select>			  
	          </div>
              <div class="w3-third">			  
               <label>Ventilation</label>
		       <input class="w3-input w3-border" type="text" name="vent" onkeypress="return isNumber(event)" maxlength="1" value="1" required />  
			  </div>
			 </div>	
			 
			 <div class="height-10"></div>
             <div class="w3-row-padding">
			  <div class="w3-half">
			   <label>Vessel</label>
			   <input class="w3-input w3-border" type="text" name="vessel" maxlength="61" style="text-transform:uppercase" value="'.$vessel.'" />  
			  </div>
			  <div class="w3-half">
			   <label>Voyage Number</label>
			   <input class="w3-input w3-border" type="text" name="voyage" maxlength="50" style="text-transform:uppercase" value="'.$voyage.'" />  
			  </div>
             </div>
			 
			 <div class="height-10"></div>
             <div class="w3-row-padding">
			  <div class="w3-half">
			   <label>Principle Name (MLO)</label>
               <select name="mlo" class="w3-select w3-border">';

    $query = "Select custRegID, completeName From m_Customer Where asMLO=1 Order By completeName ";
	$result = mssql_query($query);
	while($arr = mssql_fetch_array($result)) {
	  if($principle == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}	
	  else {echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}
	}	
	mssql_free_result($result);
	
    echo ' 	   </select>	
			  </div>
			  <div class="w3-half">
			   <label>User (Consignee)</label>
               <select name="consignee" class="w3-select w3-border">';

    $query = "Select custRegID, completeName From m_Customer Where asExp=1 Or asImp=1 Order By completeName ";
	$result = mssql_query($query);
	while($arr = mssql_fetch_array($result)) {
	  if($consignee == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}	
	  else {echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}
	}	
	mssql_free_result($result);
	
    echo ' 	   </select>	

			  </div>			 
             </div>            
			 
 			 <div class="height-10"></div>
			 <div class="w3-row-padding">
			   <div class="w3-half">
			    <label>Surveyor</label>
				<input type="text" class="w3-input w3-border" required maxlength="30" name="surveyor" style="text-transform:uppercase" value="'.$surveyor.'" />
			   </div>
               <div class="w3-half">
			    <label>Date of Survey</label>
			    <input class="w3-input w3-border" type="text" name="eventDate" id="fDate" maxlength="8" required 
				  title="Year-Month-Date" onKeyUp=dateSeparator("fDate")  value='.$dateSurvey.'>						   				
               </div>			   
			 </div>
			 
			 <div class="height-10"></div>
			 <div class="w3-container">
			  <div style="border-bottom:1px solid #ccc">Action To Do</div>
			 </div>  
			 <div class="height-10"></div>
			 <div class="w3-row-padding">
			  <div class="w3-half">';
    if($Cleaning == 1) {echo '<input class="w3-check" type="checkbox" name="iscleaning" checked />&nbsp;<label class="w3-text-dark-grey">Need Cleaning</label>'; }
	else {echo '<input class="w3-check" type="checkbox" name="iscleaning">&nbsp;<label class="w3-text-grey">Need Cleaning</label>'; }
			  
	echo '	  </div>
			  <div class="w3-half">';

    if($Repair == 1) {echo '<input class="w3-check" type="checkbox" name="isrepair" checked />&nbsp;<label class="w3-text-dark-grey">Need Repair</label>'; }
	else {echo '<input class="w3-check" type="checkbox" name="isrepair">&nbsp;<label class="w3-text-grey">Need Repair</label>'; }

   	echo '    </div>			  
			 </div>
			 
			 
		     <div class="height-20"></div>
			 <div class="w3-container">
		      <button type="submit" class="w3-btn w3-pink" >Update Log</button>
			 </div>
		     <div class="height-10"></div>
          </fieldset>';  
  }
  mssql_close($dbSQL);  
</script>
</body>
</html>  

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