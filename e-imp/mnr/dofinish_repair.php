<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  
  if(isset($_GET["whatToDo"])) {
    $totalRow=count($_GET["isApprove"]);
	$totalFinished=0;
	for($i=0; $i<$totalRow; $i++) {
	  if($_GET["isApprove"][$i] == "on") { $totalFinished++; }
	}
    
    if($totalFinished == 0) { echo '<script>swal("Error", "There was Picked Estimate found.","error");</script>'; }
	else {
</script>
    <form id="fset_dateav" method="get">

<script language="php">
	for($i=0; $i<$totalRow; $i++) {
	  if($_GET["isApprove"][$i] == "on") { echo '<input type="hidden" name="estimateNum[]" value="'.$_GET['estimateID'][$i].'">'; }
</script>	
      <label class="w3-text-teal">* AV Repair Date</label>
      <input class="w3-input w3-border" type="date" name="dtm_av" required
	           pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value=<?php echo date("Y-m-d")?> title="Year-Month-Date" /> 
  	  <div class="height-20"></div>
	  <input type="hidden" name="doUpdate" value="" />
	  <button type="submit" class="w3-btn w3-border w3-light-grey w3-text-blue" 
			value="SUBMIT" name="approved" onclick='this.form.whatToDo.value = this.value;'>Set Finsihed</button>			   
    </form>
<script language="php">	    
      }		
    }
  }	
  
  if(isset($_GET['doUpdate'])) {
    $totalRow=count($_GET['estimateNum']);
    for($i=0; $i<$totalRow; $i++) {
	  $query="Declare @NoContainer VarChar(11), @BookID VarChar(30); 
	          Select @NoContainer=containerID, @BookID=bookID From RepairHeader Where estimateID='".$_GET['estimateNum'][$i]."'; 
	            
	  		  Update containerJournal Set Cond='AV', isPending='N' Where NoContainer=@NoContainer And bookInID=@BookID; 			  
	          Update RepairHeader Set isAVRepair=1, tanggalAV='".$_GET["dtm_av"]."' Where estimateID='".$_GET['estimateNum'][$i]."'; 
                
			  Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	          Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Set Finished Repair ','".$_GET["estimateNum"][$i]."')); ";	
	  $result=mssql_query($query);
	  echo $query;
	}
    echo '<script>swal("Success", "Picked Container Number has been set up to AV. "'.$totalRow.'" );</script>';
	echo '<div class="height-20"></div>';
	echo '<a href="?p=approval" style="cursor:pointer" class="w3-btn w3-border w3-light-grey w3-text-blue">Refresh</a>';	  
  }
  mssql_close($dbSQL);
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
<script type="text/javascript">
  $(document).ready(function(){
    $("#fset_dateav").submit(function(event){
      event.preventDefault();
      var formValues = $(this).serialize();
      $.get("dofinish_repair.php", formValues, function(data){
        $("#content").html(data);
      });
    });
  });	
</script>