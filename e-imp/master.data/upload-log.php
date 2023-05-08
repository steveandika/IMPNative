<?php
  include("../asset/libs/db.php");
?>

<div class="height-20"></div>
<div class="display-form-shadow form-main w3-round-medium">
  <div class="form-header">Upload Log</div>
  <div class="height-10"></div>
  <div class="w3-container">
  
<?php
  $design='';
  $query="Select * From m_RepairPriceList_Header Order By priceCode ";
  $result=mssql_query($query);
 
  $design=$design.' <div class="w3-responsive"> 
                     <table class="w3-table w3-bordered">
                     <thead><tr>
                      <th colspan="2" style="text-align:center">Action</th>
                      <th>Price List Name</th>
					  <th>Currency</th>
					  <th>Total Record(s)</th>
					 </tr></thead><tbody>';   
  
  if(mssql_num_rows($result) <= 0) { $design=$design.'<tr><td colspan="4" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; }
	
  while($arr=mssql_fetch_array($result)) {
	$totalRow=0;  
	$subqry="Select Count(priceCode) As TotalRow From m_RepairPriceList Where priceCode='".$arr[0]."'";
    $execsub=mssql_query($subqry);
    if(mssql_num_rows($execsub) > 0) {
	  $col=mssql_fetch_array($execsub);
      $totalRow=$col[0];	  
	}
	mssql_free_result($execsub);
	
	$urlvar=str_ireplace(' ', '+', $arr[0]);
    $design=$design.'<tr>';	
	
	if($_SESSION["allowDelete"]==1) { 
	  //$design=$design.' <td style="text-align:center"><a onclick=confirmDelete("'.$urlvar.'") style="cursor:pointer;font-size:16px" class="w3-text-blue"><i class="fa fa-trash"></i></td>'; 
	  $design=$design.' <td style="text-align:center"></td>'; 	  
	}	
	else { 
	  $design=$design.' <td style="text-align:center"><i class="fa fa-lock"></i></td>'; 
	}
	$design=$design.' <td style="text-align:center"><a onclick=viewdetail("'.$urlvar.'") style="cursor:pointer;font-size:16px" title="Download" class="w3-text-blue"><i class="fa fa-cloud-download"></i></a></td>
	                  <td>'.$arr[0].'</td>
                      <td>'.$arr[3].'</td>
					  <td style="text-align:right">'.$totalRow.'</td>
					 </tr>';  
  }
  mssql_close($dbSQL);
  
  $design=$design.' </tbody></table></div>';
  echo $design;
?>
  </div>
  <div class="height-10"></div>
</div>  

<script type="text/javascript">  
  function viewdetail(urlVariable) {     
    var w=window.open("view-detail_price_list.php?id="+urlVariable); 
 	$(w.document.body).html(response);}
</script>