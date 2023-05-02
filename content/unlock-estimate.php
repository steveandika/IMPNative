<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <title>I-ConS | Unlock Estimate</title>
 
  <link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />  
  <link rel="stylesheet" type="text/css" href="asset/css/master.css" />
  <script src="asset/js/modernizr.custom.js"></script>  
  <script src="asset/js/jquery.min.2.1.1.js"></script>    
  <style>
    body {transition: background-color .5s;}
  </style>
</head>

<body>

<?php if (!isset($_SESSION["uid"])) {
        $url = "/"; 
	    echo "<script type='text/javascript'>location.replace('$url');</script>"; 
      } 
      else { 
        include("asset/libs/db.php");
        include("asset/libs/common.php");	  
	
        $valid_11=0;
        $valid_12=0;
        $valid_15=0;
        $valid_18=0;
        $valid_32=0;
		  
        $valid_11=validMenuAccess("11");
        $valid_12=validMenuAccess("12");
        $valid_15=validMenuAccess("15");
        $valid_18=validMenuAccess("18");
        $valid_32=validMenuAccess("32");
	  
		include("asset/libs/fixed-header.php");
		
	    if (isset($_GET["enbr"])) {
		  $total_row_found=-1;		  
	  	  $nbr_estimate=strtoupper($_GET["enbr"]);  
		  $requester_name=strtoupper($_GET["user"]);
		  $reason=$_GET["desc"];
		  
		  $sql="Select Count(1) As queryResult From RepairHeader Where estimateID='$nbr_estimate'; ";		 
		  $rsl=mssql_query($sql);
		  if (mssql_num_rows($rsl) > 0) {
			$fetch_col=mssql_fetch_array($rsl);
            $total_row_found=$fetch_col["queryResult"];			
		  }	  
		  mssql_free_result($rsl);
	    }		
		
		mssql_close($dbSQL);
?>  

  <div class="wrapper" style="overflow-y:auto;-webkit-overflow-scrolling: touch;" >
    <div class="page-title">Unlock Estimate of Repair</div>
	<div class="height-20"></div>
	
	<div style="position: absolute;left: 20px;width: 425px">
	  <div style="width: 98%;background:#3498db;padding:5px 5px;color: #fff;text-align: center">	  
	    <strong>Search and Execute Form</strong>
	  </div>
	  <div style="width: 98%;border: 2px solid #3498db;padding:20px 10px;background: #fbfcfc;">	
		<form name="inquiry_unload_estimate" method="GET" action="unlock-estimate">
		  <label><strong>Estimate Number: </strong></label>
		  <input type="text" name="enbr" required class="w3-input w3-border" style="text-transform: UpperCase" value="<?php echo $nbr_estimate;?>" />
		  <div class="height-10"></div>
		  <label><strong>Requester: </strong></label>
		  <input type="text" name="user" required class="w3-input w3-border" style="text-transform: UpperCase" value="<?php echo $requester_name;?>" />
		  <div class="height-10"></div>
		  <label><strong>Reason: </strong></label>
		  <select name="desc" class="w3-select w3-border">
<?php      if ($reason=="PENGHAPUSAN_DETAIL") { echo "<option selected value=PENGHAPUSAN_DETAIL>ADA DETAIL YANG HARUS DIHAPUS</option>"; }
           else { echo "<option value=PENGHAPUSAN_DETAIL>ADA DETAIL YANG HARUS DIHAPUS</option>"; } 
	       if ($reason=="NAT") { echo "<option selected value=NAT>ADA DETAIL YANG HARUS DI-NAT</option>"; }
           else { echo "<option value=NAT>ADA DETAIL YANG HARUS DI-NAT</option>"; }  	   
	       if ($reason=="UPDATE_APP_DATE") { echo "<option selected value=UPDATE_APP_DATE>PENGUBAHAN TANGGAL APPROVAL</option>"; }
           else { echo "<option value=UPDATE_APP_DATE>PENGUBAHAN TANGGAL APPROVAL</option>"; }  	   		   
?>		    
		  </select>
		  <div class="height-20"></div>
		  
          <button type="submit" class="button-blue">Submit</button>
		</form>									
	  </div>	
	
<?php
        if ($total_row_found == 1) {
		  $valid=0;
          
          $sql="Select Count(1) As queryResult From RepairHeader Where estimateID='$nbr_estimate' And statusEstimate='SUBMIT'; ";
 		  $rsl=mssql_query($sql);
		  if (mssql_num_rows($rsl) > 0) {
			$fetch_col=mssql_fetch_array($rsl);
            $valid=$fetch_col["queryResult"];			
		  }
		  mssql_free_result($rsl);
		  
		  if ($valid==1) {
			$msg="Related record is still unlocked ".$nbr_estimate;  
		  }
          else 		  
          if ($valid==0) {		  
            $sql="Select containerID, tanggalApprove From RepairHeader Where estimateID='$nbr_estimate' ";
		    $rsl=mssql_query($sql);
		    if (mssql_num_rows($rsl) > 0) {
		  	  $fetch_col=mssql_fetch_array($rsl);
              $nbr_container=$fetch_col["containerID"];
              $app_date=$fetch_col["tanggalApprove"];			
		    }	  
		    mssql_free_result($rsl);		   
		  
		    $sql="Update RepairHeader Set statusEstimate='SUBMIT', tanggalApprove=NULL Where estimateID='$nbr_estimate'; ";
		    $rsl=mssql_query($sql);
		  
		    $date_request=Date("Y-m-d");
		    $sql="Insert Into LOG_UNLOCK_EST(ESTIMATEID, CONTAINERID, APP_DATE, REQUEST_DATE, REQUEST_BY, REASON) 
		          Values('$nbr_estimate', '$nbr_container', '$app_date', '$date_request', '$requester_name', '$reason'); ";
		    $rsl=mssql_query($sql);
		  
		    $msg="Related record has been unlock as per request ".$nbr_estimate."/".$nbr_container;
		  }	
		}  
        else if ($total_row_found == 0 || $total_row_found > 1) {
		       $msg="Requested estimate number :".$nbr_estimate." failed to execute. Found > 1 Rows related Estimate Number.";
		     }	
		
		if ($total_row_found > -1) {
			echo '<div style="width: 98%;border: 2px solid #d35400;background: #e67e22;padding:5px 10px">'.$msg.'</div>';
		}	
?>

	</div>	
	
	<div id="current_log" style="position: absolute;left: 500px;height:79vh;width: 60%;overflow-y: auto;">
	  <table class="w3-table-all w3-bordered">
	    <tr>
		 <th style="vertical-align: middle">ESTIMATE ID</th>
		 <th style="vertical-align: middle">CONTAINER NBR.</th>
		 <th style="vertical-align: middle">REQUEST BY</th>
		 <th style="vertical-align: middle">REASON</th>
		</tr> 
		
<?php   $dateNow=Date("Y-m-d");
        $dsgn_row="";
		
        $sql="Select ESTIMATEID, CONTAINERID, REQUEST_BY, REASON From LOG_UNLOCK_EST Where FORMAT(REQUEST_DATE,'yyyy-MM-dd')='$dateNow'; ";
		$rsl=mssql_query($sql);
		while ($arr=mssql_fetch_array($rsl)) {
		  $dsgn_row=$dsgn_row.'<tr>
		                        <td>'.$arr["ESTIMATEID"].'</td>
		                        <td>'.$arr["CONTAINERID"].'</td>
		                        <td>'.$arr["REQUEST_BY"].'</td>
		                        <td>'.$arr["REASON"].'</td>								
                               </tr>';		  
		}
        mssql_free_result($rsl);
        
        echo $dsgn_row;		
?>	

      </table>
	</div>
  </div>
  
<?php }  
?>