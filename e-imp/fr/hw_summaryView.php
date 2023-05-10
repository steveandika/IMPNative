<script language="php">
  if (isset($_POST["HampInDTTM"])) {
	$HamparanIn = $_POST["HampInDTTM"];
        
    if (isset($_POST["HampName"])) {$IDWorkshop = $_POST["HampName"];}
    if ($IDWorkshop == "") {$IDWorkshop = "ALL";}
	
	$defHTML="//icons.pt-imp.com/e-imp/fr/";
</script>

     <div style="overflow-x:auto;max-height:50vh"> 
	  <table class="w3-striped">
	    <tr style="background:#000!important;color:#fff">
		  <th></th>
		  <th>Hamparan In DTTM</th>
		  <th>Hamparan ID</th>
		  <th>Cont. 20" (IN)</th>
		  <th>Cont. 40" (IN)</th>
		  <th>Cont. 45" (IN)</th>
		  <th>Have Survey</th>
		  <th>Have Estimate</th>
		  <th>Event</th>
		  <th>Actual Boxes</th>
		</tr>
		
      <script language="php">    
	    include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php");
		
	    $connDB = openDB();
		
		if ($connDB == "connected") {
          $queryMain = "select SUM(Size20) Size20, SUM(Size40) Size40, SUM(Size45) Size45, Format(gateIn, 'yyyy-MM-dd') dateIn, workshopID 
                        from C_HamparanIn with (NOLOCK) where gateIn = '".$HamparanIn."' ";
		  
		  if ($IDWorkshop != "ALL") {
			$queryMain = $queryMain."and workshopID = '".$IDWorkshop."' ";
		  }	  		  
		  $queryMain = $queryMain."group by workshopID, gateIn order by 5,1,2,3";  			  
		  
	      $result = mssql_query($queryMain);
	      if (mssql_num_rows($result) > 0) {
			$indexView = 0;
			
		    while($arr = mssql_fetch_array($result)) {	
              $indexView++;			  
			  
			  $haveSurvey = 0;
			  $haveEstimate = 0;
			  $actualBoxes = 0;
			  
			  $querySub = "select gateIn, HavingSurvey, workshopID from C_HaveSurveyActivity with (NOLOCK) 
			               where gateIn='".$HamparanIn."' and workshopID='".$arr["workshopID"]."';";
			  $resultSub = mssql_query($querySub);
			  
			  if (mssql_num_rows($resultSub) > 0) {
			    $arrSub = mssql_fetch_array($resultSub);
                $haveSurvey = $arrSub["HavingSurvey"];				
			  }
              mssql_free_result($resultSub);
			  
			  $querySub = "select gateIn, HavingEOR, workshopID from C_HaveEOR with (NOLOCK)
			               where gateIn='".$HamparanIn."' and workshopID='".$arr["workshopID"]."';";
			  $resultSub = mssql_query($querySub);
			  
			  if (mssql_num_rows($resultSub) > 0) {
			    $arrSub = mssql_fetch_array($resultSub);
                $haveEstimate = $arrSub["HavingEOR"];				
			  }
              mssql_free_result($resultSub);
			  
			  $querySub = "select gateIn, workshopID, Boxes from C_HamparanBoxes with (NOLOCK)
			               where gateIn='".$HamparanIn."' and workshopID='".$arr["workshopID"]."';";
			  $resultSub = mssql_query($querySub);
			  
			  if (mssql_num_rows($resultSub) > 0) {
			    $arrSub = mssql_fetch_array($resultSub);
                $actualBoxes = $arrSub["Boxes"];				
			  }
              mssql_free_result($resultSub);				  
	  </script>
     
	    <tr>
		  <td><a href=<?php echo $defHTML."hamparanSumm?dttm=".base64_encode($arr['dateIn'])."&wh=".base64_encode($arr["workshopID"]) ?> target="wDetail">View</a>
		  </td>
		  <td><?php echo $arr["dateIn"] ?></td>
		  <td><?php echo $arr["workshopID"] ?></td>
		  <td><?php echo $arr["Size20"] ?></td>
		  <td><?php echo $arr["Size40"] ?></td>
		  <td><?php echo $arr["Size45"] ?></td>
		  <td><?php echo $haveSurvey ?></td>
		  <td><?php echo $haveEstimate ?></td>
		  <td><?php echo $arr["Size20"]+$arr["Size40"]+$arr["Size45"]; ?></td>
		  <td><?php echo $actualBoxes; ?></td>		  
		</tr>
		
    <script language="php">		
	        }
		  }
		  mssql_free_result($result);
		}  
    </script>
	  
	  </table>
	 </div>
	
<script language="php">
  }	  
</script>