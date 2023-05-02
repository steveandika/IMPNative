<?php
  if(isset($_POST["noCnt"]) && trim($_POST["noCnt"]) != '') {
    include("../asset/libs/db.php");  	  
	include("../asset/libs/common.php");  	  

    $noCnt = strtoupper($_POST["noCnt"]);
	$kodeBooking = strtoupper($_POST['bookID']);
	$workshopID = '';
	
    $query = "Select Format(a.gateIn, 'yyyy-MM-dd') As gateIn, b.principle, b.consignee, a.bookInID, a.workshopID, b.vessel, b.voyageID,
	          Format(a.GIPort, 'yyyy-MM-dd') As GIPort, c.*       
	          From containerJournal a 
			  Inner Join tabBookingHeader b On b.bookID = a.bookInID
			  Left Join containerLog c On c.containerNo=a.NoContainer
			  Where a.NoContainer = '$noCnt' And a.bookInID='$kodeBooking'; ";
    $result = mssql_query($query);	
	if(mssql_num_rows($result) > 0) {
	  $arrfetch = mssql_fetch_array($result);
	  $dtmin = $arrfetch["gateIn"];
	  $dtminW = $arrfetch["GIPort"];
	  $principle = $arrfetch["principle"];
	  $consignee = $arrfetch["consignee"];
	  $kodeBook = $arrfetch["bookInID"];
	  $workshopID = $arrfetch["workshopID"];  
	  $vesselName = $arrfetch['vessel'];
	  $voyageNo = $arrfetch['voyageID'];
      $size = $arrfetch['Size'];
      $tipe = $arrfetch['Type']; 
      $height = $arrfetch['Height'];
	  $mnfr = $arrfetch['Mnfr'];
	  $constr = $arrfetch['Constr'];
	  $workshopID = $arrfetch['workshopID'];	  
?>
<div class="w3-round-small" style="border:1px solid #d5d8dc; background:#f8f9f9">
 <div class="height-5"></div>
 <div class="w3-container">
  <label style="font: 600 15px/35px Rajdhani, Helvetica, sans-serif;">Captured Event</label>
  <div class="height-10"></div>
  <form id="fcontEvent" method="post">
   <input type="hidden" name="have" value="overview" />  
   <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
   <input type="hidden" name="formID" value="fcontEvent" />   
   <input type="hidden" name="BookID" value="<?php echo $kodeBook?>" />
   <input type="hidden" name="discharge" value="" />

   <label class="w3-text-grey">Port In (format entry: yyyyMMdd)</label>
   <input class="style-input" type="text" name="dateInPort" id="fDate1" value="<?php echo $dtminW?>" title="Year-Month-Date" onKeyUp=dateSeparator("fDate1") />						   	   
   <div class="height-5"></div>
   
   <label class="w3-text-grey">Hamparan In (format entry: yyyyMMdd)</label>
   <input class="style-input" type="text" name="eventDate" id="fDate2" required value="<?php echo $dtmin?>" title="Year-Month-Date" onKeyUp=dateSeparator("fDate2") />						   	   
   <div class="height-5"></div>
     
   <div class="w3-row-padding">  
 	 <div class="w3-third">
	   <label class="w3-text-grey">ISO Size</label>
	   <select name="contSize" class="style-select">
           
	    <?php
          if($size != '') 
	 	  {
	        if($size == "20") {echo '<option selected value="20">&nbsp;20&nbsp;</option>';}
	        else {echo '<option value="20">&nbsp;20&nbsp;</option>';}
	        if($size == "40") {echo '<option selected value="40">&nbsp;40&nbsp;</option>';}
	        else {echo '<option value="40">&nbsp;40&nbsp;</option>';}
	        if($size == "45") {echo '<option selected value="45">&nbsp;45&nbsp;</option>';}
	        else {echo '<option value="45">&nbsp;45&nbsp;</option>';}	  
          }
          else 
	     {
            echo '<option value="20">&nbsp;20&nbsp;</option>
	              <option value="40">&nbsp;40&nbsp;</option>
	              <option value="45">&nbsp;45&nbsp;</option>';
         }
        ?>
		  
	   </select>
	 </div>
	 <div class="w3-third">
	   <label class="w3-text-grey">ISO Type</label>
       <select name="contType" class="style-select">
			   
        <?php
          if($tipe != "") 
		  {
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
          else 
    	  {
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
        ?>
	
       </select>		
	 </div>
	 <div class="w3-third">
	  <label class="w3-text-grey">ISO Height</label>		
	  <select name="contHeight" class="style-select">

	   <?php
         if($height != '') 
	     {
            if($height == "STD") {echo '<option selected value="STD">&nbsp;STD&nbsp;</option>';}
            else {echo '<option value="STD">&nbsp;STD&nbsp;</option>';}	  
            if($height == "HC") {echo '<option selected value="HC">&nbsp;HC&nbsp;</option>';}
            else {echo '<option value="HC">&nbsp;HC&nbsp;</option>';}	  
            if($height == "OTH") {echo '<option selected value="OTH">&nbsp;OTH&nbsp;</option>';}
            else {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  
         }
         else 
		 {
            echo '<option value="STD">&nbsp;STD&nbsp;</option>
	              <option value="HC">&nbsp;HC&nbsp;</option>
	              <option value="OTH">&nbsp;OTH&nbsp;</option>';
         }				
       ?>	
		  
	  </select>
	 </div>
   </div>	
   <div class="height-5"></div>		
	  
   <div class="w3-row-padding">
     <div class="w3-third">
 	  <label class="w3-text-grey">(Optional) Mnfr.</label>
	  <input class="style-input" type="text" name="mnfr" maxlength="10" value="<?php echo $mnfr?>" />  		  
	 </div> 
	 <div class="w3-third">
	  <label class="w3-text-grey">(Optional) Constr.</label>
      <select name="constr" class="style-select">

       <?php
	       if($constr == "STL") {echo '<option selected value="STL">STL&nbsp;</option>';}
	       else {echo '<option value="STL">STL&nbsp;</option>';} 
	       if($constr == "AL") {echo '<option selected value="AL">AL&nbsp;</option>';}
	       else {echo '<option value="AL">AL&nbsp;</option>';} 
	       if($constr == "FRP") {echo '<option selected value="FRP">FRP&nbsp;</option>';}
	       else {echo '<option value="FRP">FRP&nbsp;</option>';} 	  
       ?>
	
      </select>
  	 </div> 			
	 <div class="w3-third">
	  <label class="w3-text-grey">(Optional) Vent.</label>
	  <input class="style-input" type="text" name="vent" onkeypress="return isNumber(event)" maxlength="1" value="1" required />  
	 </div> 			
   </div>			
   <div class="height-5"></div>		   
      
   <div class="w3-row-padding">
     <div class="w3-half">
	   <label class="w3-text-grey">Shipping Line/Principle (MLO)</label>
	   <select name="mlo" class="style-select">
	    <?php
          $subquery = "Select custRegID, completeName, shortName From m_Customer Where asMLO=1 Order By completeName ";
	      $hasilquery = mssql_query($subquery);
	      while($arr = mssql_fetch_array($hasilquery)) {
	        if($principle == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">'.$arr[2].'&nbsp;</option>';}	
	        else {echo '<option value="'.$arr["custRegID"].'">'.$arr[2].'&nbsp;</option>';}
	      }	
	      mssql_free_result($hasilquery);
	    ?>
	   </select>
	 </div>
	 <div class="w3-half">
	   <label class="w3-text-grey">User (Optional)</label>
	   <select name="consignee" class="style-select">
	   <option value="">&nbsp;</option>
	    <?php
          $subquery = "Select custRegID, completeName, shortName From m_Customer Where asExp=1 Or asImp=1 Order By completeName ";
	      $hasilquery = mssql_query($subquery);
	      while($arr = mssql_fetch_array($hasilquery)) 
		  {
	        if($consignee == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[2].'&nbsp;</option>';}	
	        else {echo '<option value="'.$arr[0].'">&nbsp;'.$arr[2].'&nbsp;</option>';}
	      }	
	      mssql_free_result($hasilquery);	   
	    ?>
	   </select>
	 </div>
   </div>
   <div class="height-5"></div>
   
   <div class="w3-row-padding">
     <div class="w3-half">
	  <label class="w3-text-grey">Hamparan Name</label>
      <select name="location" class="style-select">
		  
        <?php
          $query = "Select * From m_Location Order By locationDesc ";
          $result = mssql_query($query);
          while($arr=mssql_fetch_array($result)) 
		  { 
  			if($workshopID == $arr[0]) { echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
			else { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
          }
          mssql_free_result($result);
       ?>
  
       </select>	
	 </div>  
	 <div class="w3-half"></div>
   </div>
   <div class="height-5"></div>
   
   <div class="w3-container">   
     <label class="w3-text-grey">Ex. Vessel</label>   
     <input type="text" class="style-input" name="vesselName" maxlength="61" value="<?php echo $vesselName?>" style="text-transform:uppercase" />
     <div class="height-5"></div>   
   
     <label class="w3-text-grey">Voyage Number</label>  
     <input type="text" class="style-input" name="voyageNo" maxlength="50" value="<?php echo $voyageNo?>" style="text-transform:uppercase" />   
   </div>	 
   <div class="height-10"></div>
        
   <div class="w3-container">
     <button type="submit" style="padding:2px 15px;font-weight:500;outline:none">Update Event</button>&nbsp;
     <input type="submit" style="padding:2px 15px;font-weight:500;outline:none" onclick="this.form.discharge.value=this.value;" value="Cancel" />
   </div>	 
  </form>

<script language="php">
    }
    else 
	{ 
       echo '<label style="color:red">Related record has not found in Database or have already Out Event or Condition available already.")</label>'; 
	}		
    mssql_close($dbSQL);	
  }
</script>
  <div class="height-10"></div>
</div>
</div>

<script type="text/javascript">  
  $(document).ready(function(){
    $("#fcontEvent").submit(function(event){
      event.preventDefault();
	  $('#loader-icon').show();
      var formValues = $(this).serialize();
      $.post("overview.php", formValues, function(data){ 
	    $('#loader-icon').hide();
	    $("#mnr_form").html(data); 
	  });
    });
  });
</script>	