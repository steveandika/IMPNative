<?php
  $ccCode = '';
  $ccName = '';
  $ccDesc = '';
  
  if(isset($_GET['id'])) {
    include("../asset/libs/db.php");
	
	$keywrd=$_GET['id'];
	$sql="Select * From m_CostCenter Where ccIndex=$keywrd; ";
    $rsl=mssql_query($sql);
    if(mssql_num_rows($rsl) == 1) {
	  $colArr=mssql_fetch_array($rsl);
	  $ccCode=$colArr['ccCode'];
	  $ccName=$colArr['ccName'];
	  $ccDesc=$colArr['ccDescription'];
    }			
	mssql_close($dbSQL);
  }
?>

<div id="ccFormBlock" class="w3-modal">  
 <div class="w3-modal-content w3-round-large w3-animate-zoom">
  <div class="w3-container"> 
  
   <form id="fCostCenter" method="get">
    <input type="hidden" name="id" value="<?php echo $keywrd?>" />
	<input type="hidden" name="whatToDo" value="" />
    <div class="height-20"></div>
	<div class="w3-row-padding">
	  <div class="w3-half">
	   <label class="w3-text-grey">Cost Center Code</label>
	   <input type="text" class="style-input style-border" style="text-transform:uppercase" required name="kodeCostcenter" max="10" value="<?php echo $ccCode;?>" />
	  </div>
	  <div class="w3-half">
	   <label class="w3-text-grey">Cost Center Name</label>
	   <input type="text" class="style-input style-border" style="text-transform:uppercase" required name="namaCostcenter" max="20" value="<?php echo $ccName;?>" />	  
	  </div>
	</div>
	<div class="height-5"></div>
	
	<div class="w3-row-padding">
	 <div class="w3-half">
      <label class="w3-text-grey">Description</label>
	  <input type="text" class="style-input style-border" style="text-transform:uppercase" name="desc" max="100" value="<?php echo $ccDesc;?>" />
	 </div>
	 <div class="w3-half"></div>	 
	</div>
	<div class="height-10"></div>
	
	<div class="w3-row-padding">
	 <div class="w3-half">
	 <?php
	   if(isset($_GET['id'])) {
	     echo '<input type="submit" class="w3-button w3-blue w3-round-small" name="update_field" value="Update" />&nbsp;';
	   }	  
	   else {
		 echo '<input type="submit" class="w3-button w3-blue w3-round-small" name="save_view" onclick="this.form.whatToDo.value = this.value;" value="Save" />&nbsp;
	          <input type="submit" class="w3-button w3-blue w3-round-small" name="save_addnew" value="Save And Insert New" onclick="this.form.whatToDo.value = this.value;" />&nbsp;';		  
	   }	
      
      echo '<input type="button" onclick="discharge()" class="w3-button w3-black w3-round-small" name="batal" value="Discharge" />';	  
	 ?>
	 </div>
	 <div class="w3-half"></div>
	</div> 
	
	<div class="height-20"></div>
   </form>	
   
  </div>
 </div>
</div> 


<script type="text/javascript">
  $(document).ready(function(){
    $("#fCostCenter").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("iud-sql.php", formValues, function(data){ $("#progress").html(data); });
    });	  
  });

  function discharge() { $url="/e-imp/cc/?show=list";  location.replace($url); }     
</script>    