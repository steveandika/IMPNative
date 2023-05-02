<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  
  if(isset($_GET["whatToDo"])) {
    $totalRow=count($_GET["isApprove"]);
	$totalApproved=0;
	for($i=0; $i<$totalRow; $i++) {
	  if($_GET["isApprove"][$i] == "on") {
	    /* create job order automatically for each approved EOR */		
		
		$query="Declare @workOrderID VarChar(30), @LastWorkOrderIndex Int, @keywrd VarChar(30), @Periode VarChar(8); 
		        
				Select @Periode=CONCAT('WO', SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),1,1), SUBSTRING(FORMAT(GETDATE(), 'yyyyMMdd'),3,6));
				Set @keywrd=CONCAT(@Periode, '%');
				
		        If Exists(Select * From logKeyField Where keyFName Like @keywrd) Begin
				  Select @LastWorkOrderIndex= lastNumber+1 From logKeyField Where keyFName Like @keywrd;
				  Update logKeyField Set lastNumber = lastNumber+1 Where keyFName Like @keywrd;
				  Set @workOrderID = CONCAT(@Periode,'.".$_SESSION["location"]."', '.', RTRIM(LTRIM(CONVERT(VarChar(5), @LastWorkOrderIndex))));
				  
				End Else Begin
				      Insert Into logKeyField(keyFName, lastNumber) Values(@Periode, 1);
                      Set @workOrderID = CONCAT(@Periode, '".$_SESSION["location"]."', '.1');					  
				    End;
				
				Update RepairHeader Set isApproved=1, SPKRepairID=@workOrderID, SPKRepairDate=CONVERT(VARCHAR(10), GETDATE(), 126) 
				Where estimateID='".$_GET["estimateID"][$i]."'; 
				
	            Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	            Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Approving Estimate ','".$_GET["estimateID"][$i]."')); ";	
        $result=mssql_query($query);	
        $totalApproved++;		
	  }
	}
	
	if($totalApproved > 0){
      $query="Select a.estimateID, a.containerID, a.estimateDate, Format(a.SPKRepairDate, 'yyyy-MM-dd') As SPKDate, 
	          a.SPKRepairID, b.Size, b.Type, b.Height, b.Constr
	          From RepairHeader a 
			  Inner Join containerLog b On b.ContainerNo=a.ContainerID 
			  Where a.SPKRepairDate=CONVERT(VARCHAR(10), GETDATE(), 126) Order By estimateDate, estimateID";
      $result=mssql_query($query);
      
      echo '<table class="w3-table w3-striped w3-table-all" style="font-size:12px">
             <thead>
			   <tr>
			     <th colspan="5">Approved Estimate List With Auto Genereted Work Order</th>
			   </tr>
               <tr>
                 <th>Estimate Number</th>
                 <th>Container Number</th>
				 <th>Size/Type/Height</th>
				 <th>Const</th>
                 <th>Estimate Date</th></tr>
			 </thead>
             <tbody>  ';
	  while($arr=mssql_fetch_array($result)) {
	    $isoCode=$arr[5]."/".$arr[6]."/".$arr[7];
		echo '<tr>
		        <td colspan="5">Work Order: <strong>'.$arr[4].'</strong></td>
			  </tr>
			  <tr>
			    <td>'.$arr[0].'</td>
				<td>'.$arr[1].'</td>
				<td>'.$isoCode.'</td>
				<td>'.$arr[8].'</td>
				<td>'.$arr[2].'</td></tr>'; }	  
	  echo ' </tbody>
	        </table>';			
	  mssql_free_result($result);
	}
    echo '<div class="height-20"></div>';
	echo '<a href="?p=approval" style="cursor:pointer" class="w3-btn w3-border w3-light-grey w3-text-blue">Need Approval List</a>';
	
  }
  mssql_close($dbSQL);
</script>