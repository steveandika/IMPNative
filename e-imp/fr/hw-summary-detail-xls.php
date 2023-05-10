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
  <title>I-ConS | Export Data</title>
  <style>
    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
      border: 1px solid #ddd;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }  
  </style>
</head>

<body>
  
    <script language="php">
      if (!isset($_SESSION["uid"])) {
        $url = "/"; 
	    echo "<script type='text/javascript'>location.replace('$url');</script>"; 
	  } 
	  else { 
        $filter1 = $_GET["dttm"];
	    $filter2 = $_GET["wh"];
		
		$namaFile="Summary_Per_Hamparan_".$filter1."_".$filter2;  
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=".$namaFile.".xls");  	
		
		include ("../asset/libs/common.php");
    </script>
	
	<div id="reportTitle" style="font:15px;font-weight:600;text-decoration:underline">
	  Daily Summary Hamparan - Detail
	</div>
	<div id="companyTitle" style="font-weight:600">
	  Container Depot Management System - PT. IMP
	</div>
	<div style="height:10px"></div>
	  
	<table>
      <tr>
	    <th>Index</th>
        <th>Hamparan</th>  		
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
			          gateIn = '".$filter1."' and workshopID = '".$filter2."' order by Size, NoContainer ;";
		  $result = mssql_query($query);		 
  
          if (mssql_num_rows($result) > 0) {
	  	    $dataIndex = 0;
		  
		    while ($arr = mssql_fetch_array($result)) {
		  	  $dataIndex++;  
			  
              $liners = haveCustomerName($arr["principle"]);
		      $consignee = haveCustomerName($arr["consignee"]);			
	  </script>
       
	    <tr>
		  <td><?php echo $dataIndex; ?></td>
		  <td><?php echo $filter2; ?></td>
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

	
	<script language="php">
	  }
	</script>
	
</body>
</html>  