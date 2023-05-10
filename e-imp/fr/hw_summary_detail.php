<script language="php">
  session_start();      
</script>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <meta name="author" content="Edmund" />
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>   
  <script src="../asset/js/jquery.min.2.1.1.js"></script>  
  <title>I-ConS | Report</title>
</head>

<body  style="overflow-y:auto!important"> 
  <div class="w3-container" >
  
    <script language="php">
	  include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php");
	  
      if (!isset($_SESSION["uid"])) {
        $url = "/"; 
	    echo "<script type='text/javascript'>location.replace('$url');</script>"; 
	  } 
	  else { 	  
        $filter1 = $_POST["dttm"];
	    $filter2 = $_POST["wh"];
    </script>
	
	<div class="height-20"></div>
	  <div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	    Daily Summary Hamparan - Detail
	  </div>
	  <div id="companyTitle" style="font-weight:600">
	    Container Depot Management System - PT. IMP
	  </div>
	  <div class="height-5"></div>
	  <div id="paramReport1"><strong>Hamparan Name</strong>&nbsp;<?php echo $filter2;?></div>
	  <div class="height-10"></div>	
      <div style="overflow-x:auto;height:500px">
	  
	   <table class="w3-striped">
        <tr>
		  <th>Index</th>			  
		  <th>Container #</th>			  
		  <th>Size</th>
		  <th>Shipping Line</th>
		  <th>Ex. User</th>
		  <th>Hamp. In</th>			  			   
		  <th>Survey Date</th>
		  <th>Estimate #</th>			  
		  <th>Cleaning</th>			  
		  <th>AV Repair</th>
		  <th>Hamp. Out</th>	
        </tr>	
      
      <script language="php">
	    $connDB = openDB();
		
		if ($connDB == "connected") {
		  $limitRow = 50;
		  
		  if (isset($_POST["page"])) {
			$page = $_POST["page"];			
		  } else {
			  $page = 1;  
		    }  
			  
		  $start_from = ($page -1) * $limitRow;
		  
          $query = "select 
		              NoContainer, Format(gateIn, 'yyyy-MM-dd') gateIn, Size, Type, Height, Format(gateOut, 'yyyy-MM-dd') gateOut, 
                      Format(tanggalSurvey, 'yyyy-MM-dd') surveyDate, Format(CRDate, 'yyyy-MM-dd') CRDate, 
		              Format(CCleaning, 'yyyy-MM-dd') CCleaning, Format(AVCond, 'yyyy-MM-dd') AVCond,
                      c.principle, c.consignee, a.bookInID, cleaningType, d.estimateID, 
                      Format(d.estimateDate,'yyyy-MM-dd') submittedDate
                    from containerJournal a 
		            INNER JOIN containerLog b ON b.ContainerNo = a.NoContainer
		            INNER JOIN tabBookingHeader c ON c.BookId = a.bookInID
		            LEFT JOIN RepairHeader d ON d.containerID = a.NoContainer And d.BookID = a.bookInID
			        where
			          gateIn = '".$filter1."' and workshopID = '".$filter2."' order by Size, NoContainer ";
          $result = mssql_query($query);
		  
		  $totalRows = mssql_num_rows($result);
		  $totalPage = ceil($totalRows/$limitRow);
	      
          mssql_free_result($result);
          $query = $query."OFFSET $start_from ROWS FETCH NEXT $limitRow ROWS ONLY ";
		  $result = mssql_query($query);		 
  
          if ($totalRows > 0) {
	  	    $dataIndex = 0;
		    if ($page > 1){			  
		      for ($i = 1; $i < $page; $i++){
			    $dateIndex = $dataIndex+25;	
			  }	
		    }			
		  		  
		    while ($arr = mssql_fetch_array($result)) {
		  	  $dataIndex++;  
              $liners = haveCustomerName($arr["principle"]);
		      $consignee = haveCustomerName($arr["consignee"]);			
	  </script>
       
	    <tr>
		  <td><?php echo $dataIndex; ?></td>
		  <td><?php echo $arr["NoContainer"]; ?></td>
		  <td><?php echo $arr["Size"]; ?></td>
		  <td><?php echo $liners; ?></td>
		  <td><?php echo $consignee; ?></td>
		  <td><?php echo $arr["gateIn"]; ?></td>
		  <td><?php echo $arr["surveyDate"]; ?></td>
		  <td><?php echo $arr["submittedDate"]; ?></td>
		  <td><?php echo $arr["cleaningType"]; ?></td>
		  <td><?php echo $arr["AVCond"]; ?></td>
		  <td><?php echo $arr["gateOut"]; ?></td>
		</tr>
		
      <script language="php">	  
	        }
          }		  
	    }	
        mssql_free_result($result);		
      </script>	  
		
	   </table>	 
      </div>
	
      <div class="height-10"></div>
 	  <div class="flex-container">
       <div class="flex-item">
	    <form method="post">
	      <input type="hidden" name="dttm" value="<?php echo $filter1 ?>" />
		  <input type="hidden" name="wh" value="<?php echo $filter2 ?>" />
			
          <select name="page" class="w3-select w3-border" style="width:60px">
		    <script language="php">
		      for ($i=1; $i<= $totalPage; $i++) {
  			     echo '<option value="'.$i.'">'.$i.'</option>'; 
 	 		  }	
		    </script>
          </select> 
		  <button type="submit" class="w3-button" style="background:#fff;border:2px solid #99a3a4!important">View Page</button>
	    </form>		  
	   </div>
       <div class="flex-item"> 		
		<form id="printXLS" method="get" Action="hw-summary-detail-xls">
	      <input type="hidden" name="dttm" value="<?php echo $filter1 ?>" />
		  <input type="hidden" name="wh" value="<?php echo $filter2 ?>" />
			
		  <button type="submit" class="w3-button" style="background:#fff;border:2px solid #99a3a4!important">Export XLS</button>
  	    </form>		
	   </div>
	  </div>
	
	<script language="php">
	  }
	</script>
	
  </div>	
</body>
</html>  