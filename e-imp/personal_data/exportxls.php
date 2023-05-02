<script language="php">
  session_start();  
  include("../asset/libs/db.php");
  include("../asset/libs/common.php");

  $filename = 'group_rep_'.date('YmdHis');
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$filename.".xls");	  

  $design=$design.'  <table style="font-size:12px;border:1px solid #ccc;border-collapse:collapse" >';
  $design=$design.'   <thead>';
  $design=$design.'    <tr><th colspan="2" style="border:1px solid #ccc;font-size:14px;text-align:left">Repair Group List</th></tr>';
  $design=$design.'     <th style="border:1px solid #ccc;">Employee Name</th>
                        <th style="border:1px solid #ccc;">Employee Function</th>
                       </tr></thead><tbody>';
  
  $query="SELECT a.groupID, a.groupID AS GroupIndex, b.empRegID, c.completeName, d.Description, a.Description AS DescGroup ";
  $query=$query."FROM m_GroupRepairHeader a ";
  $query=$query."INNER JOIN m_GroupRepair b ON b.groupID=a.groupID ";
  $query=$query."INNER JOIN m_Employee c ON c.empRegID=b.empRegID ";
  $query=$query."INNER JOIN m_EmployeeFunction d ON d.functionID=c.currentFunction ";
  $query=$query."ORDER BY a.groupID, b.empRegID; "; 
   
  $result = mssql_query($query);	   
  $groupID='';
  while($row=mssql_fetch_array($result)) {	
    if($groupID != $row[1]) {
	  $groupDesc=$row[1].' '.$row["DescGroup"];	
	  $design=$design.'<tr><td colspan="2" style="text-align:left;background-color:#ff9800;color:#fff;border:1px solid #ccc;">Group ID: '.$groupDesc.'</td></tr>'; 
	  $groupID=$row[1];
	}
	
	$design=$design.'<tr>';
	$design=$design.' <td style="border:1px solid #ccc;">'.$row[3].'</td>';
	$design=$design.' <td style="border:1px solid #ccc;">'.$row[4].'</td></tr>';}	
	    
  mssql_free_result($result);
  $design=$design.' </tbody></table>';
  
  echo $design;	  
</script>