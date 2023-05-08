<?php
  session_start();
  $mssg="";
  
  if(isset($_POST['discharge'])) {  
    $priceCode = $_POST['repairCode'];
    $contType = $_POST['istype'];
    $unitSize = $_POST['unitsize']; 
    $unitHeight = strtoupper($_POST['unitHeight']); 
    $damloc = strtoupper(str_replace('%','',$_POST['damloc']));  
    $dampart = strtoupper(str_replace('%','',$_POST['dampart'])); 
    $repair = strtoupper(str_replace('%','',$_POST['act'])); 
    $qty = $_POST['qty'];  
    $L1 = $_POST['size1'];
    $L2 = $_POST['size2'];
    $isMulti = $_POST['ismulti'];
    $manHour = $_POST['mh'];
    $material = $_POST['material'];
    $desc = strtoupper(str_replace("+"," ",$_POST['desc']));  
  
    $damageLocPrev=$_POST['damageLocPrev'];
    $damagePartPrev=$_POST['damagePartPrev'];
    $actPrev=$_POST['actPrev'];
    $L1Prev=$_POST['L1Prev'];
    $L2Prev=$_POST['L2Prev'];
    $QtyPrev=$_POST['QtyPrev'];
    $MHPrev=$_POST['MHPrev'];
    $materialPrev=$_POST['materialPrev'];
    $descPrev=$_POST['descPrev'];
  
    if(strtoupper($_POST['discharge']) == 'CANCEL') { $mssg="Process has been canceled."; }
    else {	
	  include("../asset/libs/db.php");
      		  
	  $do="Update Top(1) m_RepairPriceList Set LocDamage='$damloc', PartDamage='$dampart', Act='$repair', cQty=$qty, cLength=$L1, cWidth=$L2, 
	       MH=$manHour, materialValue=$material, Description='$desc', isMulti=$isMulti 
		   Where priceCode='$priceCode' And isType='$contType' And unitSize='$unitSize' And unitHeight='$unitHeight'
		   And LocDamage='$damageLocPrev' And PartDamage='$damagePartPrev' And Act='$actPrev' And 
		   cQty=$QtyPrev And cLength=$L1Prev And cWidth=$L2Prev And MH=$MHPrev And materialValue=$materialPrev; ";
	  
	  $res=mssql_query($do);
	  if (strtoupper($_SESSION["uid"])=="ROOT") { echo $do."<br>"; }
	  
	  if($res) { $mssg="Selected record has been updated."; }
	  else { 
	    if (strtoupper($_SESSION["uid"])=="ROOT") { $mssg= $do; }
		else { $mssg=$_session["uid"]."Update was failed."; }
	  }
      mssql_close($dbSQL);	  
	  
//	  $url = "priceCode=".$priceCode."&isType=".$contType."&unitSize=".$unitSize."&damloc=".str_replace(' ','%',$damloc).
		     //"&dampart=".str_replace(' ','%',$dampart)."&act=".str_replace(' ','%',$repair)."&qty=".$qty."&size1=".$L1."&size2=".$L2."&saved=".$saved;	      			 

	  //echo "<script type='text/javascript'>location.replace('$url');</script>";
	}  
  }	  
  else {	    
    $priceCode = $_GET['priceCode'];
    $contType = $_GET['isType'];
    $unitSize = $_GET['unitSize']; 
    $unitHeight = $_GET['unitHeight']; 
    $damloc = str_replace('%',' ',$_GET['damloc']);  
    $dampart = str_replace('%',' ',$_GET['dampart']); 
    $repair = str_replace('%',' ',$_GET['act']); 
    $qty = $_GET['qty'];  
    $L1 = $_GET['size1'];
    $L2 = $_GET['size2'];
    $isMulti = $_GET['isMulti'];

    $manHour = $_GET['MH'];
    $material = $_GET['material'];
	$mtrl = str_replace(",", "", $material);
	$mtrl = str_replace(".00", "", $mtrl);
    $desc = str_replace("%"," ",$_GET['desc']);  
?>

<div class="margin-left-10 border-radius-3 padding-bottom-10"  style="border:1px solid #e5e7e9;margin-top:10px;width:1090px;">
  <div id="titlediv" class="border-bt-light-gray" style="width:100%">  
   <h6 style="padding:4px 0px 4px 10px!important;color:#34495E!important"><strong>Manage Detail</strong></h4> 
  </div> 
  <div class="height-20"></div>
  <form id="fCedexManage" method="post">   
    <input type="hidden" name="job" value="cm" />
    <input type="hidden" name="discharge" value="" />
	<input type="hidden" name="damageLocPrev" value="<?php echo $damloc;?>" />
	<input type="hidden" name="damagePartPrev" value="<?php echo $dampart;?>" />
	<input type="hidden" name="actPrev" value="<?php echo $repair;?>" />
	<input type="hidden" name="L1Prev" value="<?php echo $L1;?>" />
	<input type="hidden" name="L2Prev" value="<?php echo $L2;?>" />
	<input type="hidden" name="QtyPrev" value="<?php echo $qty;?>" />
	<input type="hidden" name="MHPrev" value="<?php echo $manHour;?>" />
	<input type="hidden" name="materialPrev" value="<?php echo $mtrl; ?>" />
	<input type="hidden" name="descPrev" value="<?php echo $desc;?>" />
	
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">Price Code</li>
	  <li class="flex-item"><input type="text" readonly name="repairCode" class="w3-input w3-border" value="<?php echo $priceCode?>" /></li>
	</ul>
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">ISO Size</li>
	  <li class="flex-item"><input type="text" readonly name="unitsize" class="w3-input w3-border" value="<?php echo $unitSize?>" /></li>
	  <li class="flex-item" style="width:120px">ISO Type</li>
	  <li class="flex-item"><input type="text" readonly name="istype" class="w3-input w3-border" value="<?php echo $contType?>" /></li>
	  <li class="flex-item" style="width:120px">ISO Height</li>
	  <li class="flex-item"><input type="text" readonly name="unitHeight" class="w3-input w3-border" value="<?php echo $unitHeight?>" /></li>
	</ul>
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">Able For Multiply</li>
	  <li class="flex-item">
	   <select name="ismulti" class="w3-select w3-border">
	    <?php
		  if($isMulti == 1) { echo '<option selected value=1>&nbsp;Yes</option>'; }
		  else { echo '<option value="1">&nbsp;Yes</option>'; }
		  if($isMulti == 0) { echo '<option selected value=0>&nbsp;No</option>'; }
		  else { echo '<option value="0">&nbsp;No</option>'; }
		  
		?>
	   </select>
	  </li>	  
	</ul>  
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">Dmg. Location</li>
	  <li class="flex-item">
	    <input type="text" style="text-transform:uppercase" maxlength="2" required class="w3-input w3-border" name="damloc" value="<?php echo $damloc?>" /> </li>
	  <li class="flex-item" style="width:120px">Dmg. Part</li>
	  <li class="flex-item">
	    <input type="text" style="text-transform:uppercase" maxlength="5" class="w3-input w3-border" name="dampart" value="<?php echo $dampart?>" /></li>
	  <li class="flex-item" style="width:120px">Repair/Action</li>		
	  <li class="flex-item">
	    <input type="text" style="text-transform:uppercase" maxlength="3" class="w3-input w3-border" name="act" value="<?php echo $repair?>" /></li>	  
    </ul>	
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">Quantity</li>
	  <li class="flex-item">
	    <input type="text" onkeypress="return isNumber(event)" style="text-align:right" required class="w3-input w3-border" name="qty" value="<?php echo $qty?>" /></li>
	  <li class="flex-item" style="width:120px">L1</li>	  
	  <li class="flex-item">
	    <input type="text" onkeypress="return isNumber(event)" style="text-align:right" required class="w3-input w3-border" name="size1" value="<?php echo $L1?>" /></li>	  
	  <li class="flex-item" style="width:120px">L2</li>	  
	  <li class="flex-item">
	    <input type="text" onkeypress="return isNumber(event)" style="text-align:right" required class="w3-input w3-border" name="size2" value="<?php echo $L2?>" /></li>	  
    </ul>
	<ul class="flex-container">	
	  <li class="flex-item" style="width:120px">Man Hour</li>	
	  <li class="flex-item">
	    <input type="text" onkeypress="return isNumber(event)" style="text-align:right" required class="w3-input w3-border" name="mh" value="<?php echo $manHour; ?>" /></li>
	  <li class="flex-item" style="width:120px">Material Value</li>	
	  <li class="flex-item">
	    <input type="text" onkeypress="return isNumber(event)" style="text-align:right" required class="w3-input w3-border" name="material" value="<?php echo $mtrl; ?>" /></li>
	  <li class="flex-item" style="width:120px">Description</li>	
	  <li class="flex-item">
	    <input type="text" class="w3-input w3-border" style="text-transform:uppercase" required name="desc" value="<?php echo $desc?>" /></li>
	</ul>
	
	<div class="height-10"></div>	 
    <ul class="flex-container">	
	 <li class="flex-item"><input type="submit" class="imp-button-grey-blue" onclick="this.form.discharge.value=this.value;" value="Update" /></li>
	 <li class="flex-item"><input type="submit" class="imp-button-grey-blue" onclick="this.form.discharge.value=this.value;" value="Cancel" /></li>
	</ul>    
   </form>
  
  <div class="height-10"></div>    
</div>

<?php
  }  
  
  if($mssg!="") {
?>	

<div class="margin-left-10" style="position:fixed !important;margin-top:10px;width:1090px;">
   <div style="text-align:center">
     <?php echo $mssg;?>
   </div> 
   <div class="height-20"></div>
 
   <div style="text-align:center">
     <form id="fconfirm" method="get">	 
      <input type="hidden" name="repairCode" value="<?php echo $priceCode?>" />
      <input type="hidden" name="isType" value="<?php echo $contType?>" /> 
	  <input type="hidden" name="unitsize" value="<?php echo $unitSize?>" /> 
	  <input type="hidden" name="damloc" value="<?php echo $damloc?>" /> 
	  <input type="hidden" name="dampart" value="<?php echo $dampart?>" /> 
	  <input type="hidden" name="act" value="<?php echo $repair?>" /> 
	  <input type="hidden" name="qty" value="<?php echo $qty?>" /> 
	  <input type="hidden" name="size1" value="<?php echo $L1?>" /> 
	  <input type="hidden" name="size2" value="<?php echo $L2?>" /> 
	  
      <button type="submit" class="imp-button-grey-blue">Confirm</button> 	  
	 </form>
   </div>
  <div class="height-20"></div>
</div>

<?php
  }
?>



<script type="text/javascript">  
  $(document).ready(function(){
    $("#fCedexManage").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.post("cedexManage.php", formValues, function(data){ $("#process").html(data); });
    });		

    $("#fconfirm").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("cedexQuery.php", formValues, function(data){ $("#process").html(data); });
    });		
	
  });

  function refreshList(urlVar) {
    $("#process").load(urlVar);	  
  }	    
</script>