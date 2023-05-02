<?php
  session_start();    
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");

  if(isset($_POST['noCnt'])) {
	$keywrd=strtoupper(trim($_POST['noCnt']));   
	$result=validUnitDigit($keywrd);
	
    if($result != 'OK') { echo '<div class="w3-container"><div class="w3-panel w3-red">
	                             <h3>Warning: </h3><p>'.$result.'</p>
	                            </div></div>
								<div class="height-5"></div>'; }  
  }	  
?>

<form id="fDetailIn" method="post">
  <?php echo '<input type="hidden" name="nocontainer" value='.$keywrd.'>';?>
  <div class="w3-container">
    <label>Workshop Location</label>
	
<?php
/*
  if($_SESSION["location"] == "ALL") {
	$query="Select * From m_Location Order By locationDesc ";
	$result=mssql_query($query);
  	echo '<select name="location" class="w3-select w3-border">';
	while($arr=mssql_fetch_array($result)) { 
	  echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
	mssql_free_result($result);
	echo '</select>'; }
			
  else { echo '<input class="w3-input w3-border w3-light-grey" type="text" name="location" readonly value='.$location.' >'; }*/
  
  $query="Select * From m_Location Order By locationDesc ";
  $result=mssql_query($query);
  echo '<select name="location" class="w3-select w3-border">';
  while($arr=mssql_fetch_array($result)) { 
	 echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>'; }
  mssql_free_result($result);
  echo '</select>'; 
  
?>
  </div>
  <div class="height-5"></div>
  <div class="w3-row-padding">
      <div class="w3-third">
        <label>* Container Size</label>
        <select name="contSize" class="w3-select w3-border">
          <option value="20">20&nbsp;</option>
	      <option value="40">40&nbsp;</option>
	      <option value="45">45&nbsp;</option>
        </select>
      </div>
      <div class="w3-third">
        <label>* Container Type</label>
        <select name="contType" class="w3-select w3-border">
          <option value="GP">GP&nbsp;</option>
	      <option value="OT">OT&nbsp;</option>
	      <option value="OS">OS&nbsp;</option>
	      <option value="FR">FR&nbsp;</option>
	      <option value="TW">TW&nbsp;</option>
	      <option value="RF">RF&nbsp;</option>
	      <option value="TK">TK&nbsp;</option>
	      <option value="VT">VT&nbsp;</option>
	      <option value="BK">BK&nbsp;</option>
	      <option value="OTH">OTH&nbsp;</option>
        </select>
      </div>
      <div class="w3-third">
        <label>* Container Height</label>
        <select name="contHeight" class="w3-select w3-border">
          <option value="STD">STD&nbsp;</option>
	      <option value="HC">HC&nbsp;</option>
	      <option value="OTH">OTH&nbsp;</option>
	    </select>
      </div>  
  </div>
  <div class="height-5"></div>

  <div class="w3-row-padding">
      <div class="w3-half">
        <label>* Construction</label>
        <select name="constr" class="w3-select w3-border">
	      <option value="STL">STL&nbsp;</option>
	      <option value="AL">AL&nbsp;</option>
	      <option value="FRP">FRP&nbsp;</option>
        </select>	
      </div>
      <div class="w3-half">
        <label>* Manufacture</label>
        <input class="w3-input w3-border" type="text" name="mnfrYear" maxlength="10" required>      	
      </div>  
    </div>
    <div class="height-5"></div>

    <div class="w3-row-padding">
      <div class="w3-half">
        <label>* Principle</label>
        <select name="mlo" class="w3-select w3-border">
         <?php $query="Select custRegID, completeName From m_Customer Where asMLO=1 Order By completeName ";
	           $result=mssql_query($query);
	           while($arr=mssql_fetch_array($result)) {
	             if($mlo == $arr[0]) { echo  '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>'; }
		         else { echo  '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>'; }
	           }	
	           mssql_free_result($result);
	     ?>
        </select>	
      </div>     
      <div class="w3-half">
        <label>* Ex. User</label>
        <select name="consignee" class="w3-select w3-border">
         <?php $query="Select custRegID, completeName From m_Customer Where asExp=1 Or asImp=1 Or asLogParty=1 Order By completeName ";
	           $result=mssql_query($query);
	           while($arr=mssql_fetch_array($result)) {
	             if($consignee == $arr[0]) { echo  '<option selected value="'.$arr[1].'">&nbsp;'.$arr[0].'&nbsp;</option>'; }
		         else { echo  '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>'; }
	           }	
	           mssql_free_result($result);
	     ?>
        </select>	
      </div>     
  </div>
  <div class="height-5"></div>

  <div class="w3-row-padding">
      <div class="w3-half">
        <label>* Ex. Vessel Voyage</label>
        <?php echo '<select name="vessel" class="w3-select w3-border">';  
              $query="Select vesselid, CONCAT(vesselName,' ',voyage) As vesselVoyage From m_vessel Where ETD <= CONVERT(VARCHAR(10), GETDATE(), 126) 
                      Order By ETD ";
              $result=mssql_query($query);
              while($arr=mssql_fetch_array($result)) { echo '<option value="'.$arr[1].'">&nbsp;'.$arr[1].'</option>'; }
              mssql_free_result($result); 
	          echo '</select>';   
       ?>
      </div>
      <div class="w3-half">
        <label>* Vessel A T A</label>
        <input class="w3-input w3-border" type="text" name="vesselATA" id="fDate" required
	          pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value="<?php echo date("Y-m-d")?>" 
		      title="Year-Month-Date" onKeyUp="dateSeparator()" />
      </div>
  </div> 
  <div class="height-5"></div>

  <div class="w3-row-padding">
      <div class="w3-half">
        <label>* Surveyor</label>
        <?php echo '<select name="surveyor" class="w3-select w3-border">'; 
	          $query="Select completeName From m_Employee a Inner Join m_EmployeeFunction b On b.functionID=a.currentFunction And b.Description Like '%SURVEYOR%'";
	          $result=mssql_query($query);
	          while($arr=mssql_fetch_array($result)) { echo '<option value="'.$arr[0].'">&nbsp;'.$arr[0].'&nbsp;</option>';  }
	          mssql_free_result($result);
	          echo '</select>';
        ?>
      </div>  
      <div class="w3-half"></div>
  </div>
  <div class="height-5"></div>

  <div class="w3-container">
      <label>Remark Survey</label>  
      <textarea rows="4" class="w3-input w3-border" name="remarkcond" style="text-transform:uppercase;"></textarea>
  </div>
  <div class="height-5"></div>

  <div class="w3-row-padding">
      <div class="w3-third">
        <label>* Date In</label>
        <input class="w3-input w3-border" type="text" name="dtmin" id="fDate" required
	       pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value="<?php echo date("Y-m-d")?> 
		   title="Year-Month-Date" onKeyUp="dateSeparator()" />     
      </div>	
      <div class="w3-twothird">&nbsp;</div>        
  </div>
  <div class="height-5"></div>

  <div class="w3-container">
     <input type="submit" class="w3-btn w3-blue" name="register" value="Register" />
  </div>
</form>

<script language="php">
  mssql_close($dbSQL);
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#fDetailIn").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();	 
      $.post("store-gatein.php", formValues, function(data){ $("#content").html(data); });
    });	 
  }); 
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
