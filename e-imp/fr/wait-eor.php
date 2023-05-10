<?php
  session_start();      
  if (!isset($_SESSION["uid"])) {
    $URL="/"; 
	echo "<script type='text/javascript'>location.replace('$URL');</script>"; } 	
  else { 
  include("../asset/libs/db.php");  

  $query="Select a.bookInID, a.NoContainer, c.Size, c.Type, c.Height, Format(a.gateIn, 'yyyy-MM-dd') As DateIn, a.workshopID, 
          a.remarkCond, a.Surveyor, c.Constr, c.Mnfr, b.principle, b.consignee , Format(tanggalSurvey,'yyyy-MM-dd') As Survey,
		  Format(a.GIPort, 'yyyy-MM-dd') As PortIn
          From containerJournal a 
		  Inner Join tabBookingHeader b On b.bookID=a.bookInID
		  Inner Join containerLog c On c.ContainerNo=a.NoContainer 
		  Where a.gateOut Is Null And  
		        (a.bookInID Not In (Select bookID As bookInID From repairHeader)) And 
				(a.bookInID Not In (Select bookID As bookInID From CleaningHeader))
		  Order By a.workshopID, a.gateIn, c.Size, c.Type, c.Height ";
  $result=mssql_query($query);  
?>

<div class="w3-container w3-responsive" style="background:#fff">
 <div style="padding:10px 10px 15px 15px;border:0"><img src="../asset/img/pt-imp.png" ></div> 		
 <h2>&nbsp;&nbsp;Waiting M N R</h2>  

 <table class="w3-table w3-bordered">
   <thead>
	<tr>
	  <th>Index</th>
      <th>Container No.</th>
	  <th>Size/Type/Height</th>
	  <th>Mnfr.</th>
	  <th>Constr.</th>
	  <th>Port In</th>
	  <th>Hamp. In</th>
	  <th>Survey</th>
	  <th>Surveyor</th>
	  <th>Shipping Line</th>
	  <th>User</th>
    </tr>
   </thead>
   <tbody>
  
<?php
    $index=0;
    if(mssql_num_rows($result) <= 0) {
	  echo '<tr><td colspan="10" style="letter-spacing:1px;color:red;font-weight:bold;text-align:left">RECORD NOT FOUND</td></tr>'; }
	
	else {
	  $loc="";
	 $design="";
	  while($arr=mssql_fetch_array($result)) {
		$index++;  
		$sizeType=$arr[2].'/'.$arr[3].'/'.$arr[4];
        $liners = haveCustomerName($arr["principle"]);
		$consignee = haveCustomerName($arr["consignee"]);
				
	    if($loc != $arr[6]) {
		  $loc=$arr[6];
		  $design=$design.'<tr><td colspan="10" style="color:Red;font-weight:500;text-align:left"><b>Location: '.$loc.'</b></td></tr>'; 
		}
        
		$design=$design.'<tr>
		                   <td>'.$index.'.</td>
				           <td>'.$arr["NoContainer"].'</td>
				           <td>'.$sizeType.'</td>
				           <td>'.$arr["Mnfr"].'</td>
				           <td>'.$arr["Constr"].'</td>
				           <td>'.$arr["PortIn"].'</td>
				           <td>'.$arr["DateIn"].'</td>
				           <td>'.$arr["Survey"].'</td>
				           <td>'.$arr["Surveyor"].'</td>
				           <td>'.$liners.'</td>
				           <td>'.$consignee.'</td>				  
			             </tr>';
	  }
	}
    mssql_close($dbSQL);
	echo $design;
?>
  
   </tbody>
 </table>
 <div class="height-10"></div>

 </div>
 </div>
<?php  
  }
?>