<div style="padding:5px 10px">   
  <div style="padding:10px 10px 15px 15px;border:0">  
   <img src="../asset/img/pt-imp.png" >
  </div> 			   
  <h3>Waiting Survey</h3>  			  
   <table class="w3-table w3-bordered">
    <thead>	
	  <tr>
	    <th>Index</th>
        <th style="text-align:center">Container No.</th>
	    <th style="text-align:center">Size/Type/Height</th>
		<th>Mnfr.</th>
		<th>Constr.</th>
		<th>Port In</th>
        <th>Workshop In</th>
		<th>Workshop ID</th>
	    <th>Liners</th>
      </tr></thead><tbody>
	  
<script language="php">		
  include("../asset/libs/db.php");
  $query_ = "Select a.NoContainer, b.Size, b.Type, b.Height, b.Constr, b.Mnfr, Format(a.gateIn,'yyyy-MM-dd') As DTMIn, 
            a.workshopID, d.principle, d.consignee, format(GIPort,'yyyy-MM-dd') As GIPort, a.workshopID, a.bookInID
            From containerJournal a
            Inner Join containerLog b On b.ContainerNo=a.NoContainer
	        Inner Join tabBookingHeader d On d.bookID=a.BookInID
	        Where tanggalSurvey Is NULL And Cond='DM' And gateIn Is Not Null And gateOut is Null AND a.bookInID Not Like 'SLD%' AND CCleaning IS NULL 
			Order By a.workshopID, a.gateIn";
			
  $query="Select * From view_summary_hamparan where 
          tanggalSurvey Is NULL And Cond='DM' And gateIn Is Not Null And 
		  DTMOut=''
		  Order By workshopID, principle, gateIn";			
  $result = mssql_query($query);
  $loc = '';
  $design="";
  $index=0;
  while($arr = mssql_fetch_array($result)) {
	$index++;  
	$sizeTypeHeight = $arr["Size"].'/'.$arr["Height"];  
    $principle = haveCustomerName($arr["principle"]);
	$consignee = haveCustomerName($arr["consignee"]);	
	
    $design=$design.'<tr>				
	                  <td style="border-right:1px solid #ddd">'.$index.'.</td>
	                  <td style="border-right:1px solid #ddd">'.$arr["NoContainer"].'</td>
		              <td style="border-right:1px solid #ddd">'.$sizeTypeHeight.'</td>
		              <td style="border-right:1px solid #ddd">'.$arr['Mnfr'].'</td>
		              <td style="border-right:1px solid #ddd">'.$arr['Constr'].'</td>					  
			          <td style="border-right:1px solid #ddd">'.$arr["PortIn"].'</td>
   		              <td style="border-right:1px solid #ddd">'.$arr["DTMIn"].'</td>
					  <td style="border-right:1px solid #ddd">'.$arr["workshopID"].'</td>
	                  <td style="border-right:1px solid #ddd">'.$principle.'</td></tr>';
  } 
  mssql_close($dbSQL);
  
  echo $design;
</script>

    </tbody>	
   </table> 
   <div class="height-10"></div>
</div>
