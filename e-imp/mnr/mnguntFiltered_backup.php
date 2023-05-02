<?php
  if(isset($_POST['location']) && trim($_POST['location']) != '') {$workshopID = $_POST['location'];}
  if(isset($_POST['noCnt']) && trim($_POST['noCnt']) != '') {$noCnt = $_POST['noCnt'];}  

  if(isset($_GET['location']) && trim($_GET['location']) != '') {$workshopID = $_GET['location'];}
  if(isset($_GET['noCnt']) && trim($_GET['noCnt']) != '') {$noCnt = $_GET['noCnt'];}    
?>

   <div class="w3-responsive">       
      <table class="w3-table-all">
        <thead>
	      <tr>
            <th>Container Number</th>
	        <th>Size/Type/Height</th>
	        <th>Mnfr Year</th>
	        <th>Const.</th>
            <th>Date In</th>
			<th>Survey</th>
            <th>CR</th>
	        <th>Cleaning</th>
			<th>Cleaning Type</th>
	        <th>Cond</th>	 
            <th>Estimate No.</th>			
	        <th>Shipping Line</th>
	        <th>User</th>
			<th>EOR File</th>
			<th>Log Photo</th>
           </tr></thead><tbody>	
		   
<?php
  include ("../asset/libs/db.php");
  include ("../asset/libs/common.php");
  
  if(trim($workshopID) != '') {$term = " workshopID='$workshopID' ";}
  if(trim($noCnt) != '') {$term = " a.NoContainer='$noCnt' ";}
  if(trim($workshopID) == '' && trim($noCnt) == '') {$term = " workshopID Like '%' ";}
  if(trim($workshopID) != '' && trim($noCnt) != '') {$term = " workshopID='$workshopID' And a.NoContainer='$noCnt' ";}
 
	  
  $query = "Select a.NoContainer, Format(gateIn,'yyyy-MM-dd') As DTMIn, JamIn, TruckingIn, VehicleInNumber, isPending, 
	            b.principle, b.consignee, b.vessel, a.isCleaning, a.isRepair,
	            c.Mnfr, c.Size, c.Type, c.Height, c.Constr, a.workshopID, d.locationDesc,  DATEDIFF(day, a.gateIn, GETDATE()) AS dueDate, a.Cond,
                CASE WHEN CRDate Is Null THEN ''
				     WHEN Format(CRDate, 'yyyy-MM-dd') = '1900-01-01' THEN ''
                     ELSE Format(CRDate, 'yyyy-MM-dd') END As CRDate, 
			    CASE WHEN CCleaning Is Null THEN ''
				     WHEN Format(CCleaning, 'yyyy-MM-dd') = '1900-01-01' THEN ''				
				     ELSE Format(CCleaning, 'yyyy-MM-dd') END As CCleaning, cleaningType, estimateID,
                CASE WHEN tanggalSurvey Is Null THEN ''
                     WHEN Format(tanggalSurvey, 'yyyy-MM-dd') = '1900-01-01' THEN ''				
				     ELSE Format(tanggalSurvey, 'yyyy-MM-dd') END As tanggalSurvey,
				dirname, a.bookInID
                From containerJournal a 
	            Inner Join tabBookingHeader b On b.bookID = a.bookInID
		        Left Join containerLog c On c.ContainerNo = a.NoContainer
		        Left Join m_Location d On d.locationID = a.workshopID
				Left Join RepairHeader e On e.containerID = a.NoContainer And a.bookInID = e.bookID
		        Where gateOut Is Null And ".$term." 
				Order By a.workshopID Asc, dueDate Desc";		
  $result = mssql_query($query);
  
  $index = 0;
  while($arr = mssql_fetch_array($result)) 
  {
	$index++;

	$sizeTypeHeight=$arr[12].'/'.$arr[13].'/'.$arr[14];  
    $principle = haveCustomerName($arr[6]);
    $consignee = haveCustomerName($arr[7]);	
		
	if(trim($principle) != '') { $principle = substr($principle,0,7).'..'; }
	if(trim($consignee) != '') { $consignee = substr($consignee,0,7).'..'; }
		
	$cleaningType = '';
	if($arr["cleaningType"] == 'WW') {$cleaningType = "LIGHT";}
	if($arr["cleaningType"] == 'LC') {$cleaningType = "LIGHT";}
	if($arr["cleaningType"] == 'DW') {$cleaningType = "MEDIUM";}
	if($arr["cleaningType"] == 'CC') {$cleaningType = "HEAVY";}
	if($arr["cleaningType"] == 'HC') {$cleaningType = "HEAVY";}
	if($arr["cleaningType"] == 'SC') {$cleaningType = "SPECIAL";}		
	
	echo '<tr>';
?>				
	            
            <td>
			 <a class="w3-text-blue" style="text-decoration:none;font-weight:600;cursor:pointer" onclick="detailmnr('<?php echo $arr["NoContainer"]?>&loc=<?php echo $workshopID?>')">
			 <?php echo $arr["NoContainer"]?></a>
			</td>
					
<?php
    $photoLog = "Select * From containerPhoto Where containerID='".$arr["NoContainer"]."' And BookID='".$arr["bookInID"]."'";
	$reslog = mssql_query($photoLog);
	$havePhoto = mssql_num_rows($reslog);
	mssql_free_result($reslog);
		
    echo '	<td>'.$sizeTypeHeight.'</td>
		    <td>'.$arr["Mnfr"].'</td>
		    <td>'.$arr["Constr"].'</td>
		    <td>'.$arr["DTMIn"].'</td>
			<td>'.$arr["tanggalSurvey"].'</td>
		    <td>'.$arr["CRDate"].'</td>
		    <td>'.$arr["CCleaning"].'</td>
			<td>'.$cleaningType.'</td>
            <td>'.$arr["Cond"].'</td> 
			<td>'.$arr["estimateID"].'</td>
            <td>'.$principle.'</td>
            <td>'.$consignee.'</td>';

    if(trim($arr["dirname"]) != '' && isset($arr["dirname"]))
    {
      echo '<td>'.urlencode($arr["dirname"]).'</td>';
    }
    else
    {
	  echo '<td>&nbsp;</td>';	
    }		
	
	if($havePhoto > 0)
	{
	  echo '<td>View Photo</td>';	
    }
    else
    {
      echo '<td>&nbsp;</td>';	 
    }			
				
	echo '</tr>';
  }
  mssql_close($dbSQL);		  	  
?>
	
        </tbody>
	  </table>
	</div>


<script language="text/JavaScript">   
  function detailmnr(urlVar) { $("#content").load("overview.php?noCnt="+urlVar); }
    
  function openPopup(varData,fname)
  {
    document.getElementById(fname).target = 'popUpWind';
	alert(fname);
    window.open('','','width=640,height=480');
  }
</script>