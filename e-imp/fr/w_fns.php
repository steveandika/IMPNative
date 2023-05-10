<div class="height-10"></div>
  <div style="padding:10px 10px 15px 15px;border:0">  
   <img src="../asset/img/pt-imp.png" >
  </div>			   
  <h3 >&nbsp;&nbsp;Waiting Complete Repair (C/R) Report</h3>  			  
  <div class="w3-responsive">   
   <div class="height-5"></div>
   <table class="w3-table w3-bordered">
    <thead>	
	  <tr>
	    <th>Index</th>
        <th>Container No.</th>
	    <th>Size/Type/Height</th>
        <th>Workshop In</th>
		<th>Due Date</th>
		<th>Survey</th>
		<th>Estimate No.</th>
		<th>Submitted</th>
		<th>Approved</th>
	    <th>Liners</th>
	    <th>Ex. User</th>
       </tr></thead><tbody>

<?php  
  include("../asset/libs/db.php");
  $query_="Select a.NoContainer, b.Size, b.Type, b.Height, b.Constr, Format(a.gateIn,'yyyy-MM-dd') As DTMIn, 
          a.workshopID, d.principle, d.consignee, DATEDIFF(day, a.gateIn, GETDATE()) AS dueDate,
	   	  Format(a.CRDate, 'yyyy-MM-dd') As CRDate, Format(a.CCleaning, 'yyyy-MM-dd') As CCleaning, Format(a.tanggalSurvey, 'yyyy-MM-dd') As tglSurvey,	
		  Format(estimateDate,'yyyy-MM-dd') As submitDate, e.estimateID, Format(tanggalApprove,'yyyy-MM-dd') As Approved
          From containerJournal a
          Inner Join containerLog b On b.ContainerNo=a.NoContainer
		  Inner Join tabBookingHeader d On d.bookID=a.BookInID
		  Inner Join repairHeader e On e.BookID=a.bookInID And e.containerID=a.NoContainer
		  Where CRDate Is NULL And tanggalApprove Is Not Null Order By a.workshopID, d.principle, a.gateIn";
		  
  $query="Select * From view_summary_hamparan where 
          CRDate Is NULL And tanggalApprove Is Not Null 
		  Order By workshopID, principle, gateIn";
  $result = mssql_query($query);  
  $loc="";
  $Index=0;
  while($arr = mssql_fetch_array($result)) {
	$index++;  
	$sizeTypeHeight = $arr["Size"].'/'.$arr["Type"].'/'.$arr["Height"];  
    $principle = haveCustomerName($arr["principle"]);
	$consignee = haveCustomerName($arr["consignee"]);	
	
	if($loc != $arr["workshopID"]) {
	  echo '<tr><td colspan="11" style="color:Red;font-weight:500">Location: '.$arr["workshopID"].'</td></tr>';
	  $loc = $arr["workshopID"];
	}
    echo '<tr>				
	        <td>'.$index.'.</td>
			<td>'.$arr["NoContainer"].'</td>
		    <td>'.$sizeTypeHeight.'</td>
   		    <td>'.$arr["DTMIn"].'</td>
		    <td>'.$arr["dueDate"].'</td>
		    <td>'.$arr["tglSurvey"].'</td>
			<td>'.$arr["estimateID"].'</td>
			<td>'.$arr["submitDate"].'</td>
			<td>'.$arr["Approved"].'</td>
	        <td>'.$principle.'</td>
            <td>'.$consignee.'</td></tr>';
  } 
  mssql_close($dbSQL);
?>
  
  </tbody>  
</table>
<div class="height-10"></div>