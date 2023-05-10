<script language="php">
  include("../asset/libs/db.php");
</script>

<div class="w3-container w3-responsive" style="background:#fff">
  <div style="padding:10px 10px 15px 15px;border:0">  
   <img src="../asset/img/pt-imp.png" >
  </div> 			   
  <h3 style="padding:0 0 5px 0;border-bottom:1px solid #839192">&nbsp;&nbsp;InHouse Container</h3>  			  
  <div style="padding:10px 10px 15px 15px;border:0">   
   <div class="height-5"></div>
   <table  class="w3-table w3-bordered">
   <thead>
	<tr>
      <th>Container No.</th>
	  <th>Size/Type/Height</th>
	  <th>Mnfr</th>
	  <th>Port In</th>
      <th>Hamp. In</th>
	  <th>Ex. Vessel Voyage</th>
      <th>Due Date</th>
      <th>C/R</th>
	  <th>C/C</th>
	  <th>Shipping Line</th>
	  <th>User</th>
    </tr></thead><tbody>


<script language="php">
  $query="Select a.NoContainer, Format(gateIn,'yyyy-MM-dd') As DTMIn, isPending, 
	      b.principle, b.consignee, b.vessel, b.voyageID,
	      c.Mnfr, c.Size, c.Type, c.Height, c.Constr, a.workshopID, d.locationDesc,  DATEDIFF(day, a.gateIn, GETDATE()) AS dueDate, a.Cond,
          Format(a.CRDate, 'yyyy-MM-dd') As CRDate, Format(a.CCleaning, 'yyyy-MM-dd') As CCleaning, Format(a.GIPort, 'yyyy-MM-dd') As GIPort
          From containerJournal a 
	      Inner Join tabBookingHeader b On b.bookID = a.bookInID
		  Inner Join containerLog c On c.ContainerNo = a.NoContainer
		  Left Join m_Location d On d.locationID = a.workshopID
		  Where gateIn Is Not Null And gateOut Is Null Order By a.workshopID Asc, dueDate Desc";
  $result=mssql_query($query);
  $loc="";
  $design="";
  while($arr=mssql_fetch_array($result)) {
	$sizeTypeHeight=$arr["Size"].'/'.$arr["Type"].'/'.$arr["Height"];  
    $principle = haveCustomerName($arr["principle"]);
	$consignee = haveCustomerName($arr["consignee"]);	
	
	if($loc != $arr["workshopID"]) {
	  $design=$design.'<tr><td colspan="13"><strong>Location: '.$arr["workshopID"].'</strong></td></tr>';
	  $loc=$arr["workshopID"];
	}
    $design=$design.'<tr>				
			          <td>'.$arr[0].'</td>
			          <td>'.$sizeTypeHeight.'</td>
			          <td>'.$arr["Mnfr"].'</td>
			          <td>'.$arr["GIPort"].'</td>
			          <td>'.$arr["DTMIn"].'</td>
			          <td>'.$arr["vessel"].", ".$arr["voyageID"].'</td>
			          <td>'.$arr["dueDate"].'</td>
			          <td>'.$arr["CRDate"].'</td>
			          <td>'.$arr["CCleaning"].'</td>
	                  <td>'.$principle.'</td>
                      <td>'.$consignee.'</td></tr>';
  }
  mssql_close($dbSQL);
  echo $design;
</script>
  	
   </tbody>
  </table>
 </div>
</div>