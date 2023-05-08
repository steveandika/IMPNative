<?php
  
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=".$_GET['id'].".xls");
  include("../asset/libs/db.php");
?>

<style>
    table { border:1px solid #ccc;width:100%;margin:0;padding:0;border-collapse: collapse; }
    table tr { padding: 5px; }
    table th, table td { padding: 5px;text-align:center;border:1px solid #ccc; font-size:.830rem; }
</style>	

  
   <table>
		  <tr>		  
		   <td>Type</td>
		   <td>Size</td>
		   <td>Height</td>
		   <td>Location</td>
		   <td>Part</td>
		   <td>Repair</td>
		   <td>L1</td>
		   <td>L2</td>
		   <td>Qty</td>
		   <td>Multi</td>
		   <td>MH</td>
		   <td>Material</td>
		   <td>Description</td></tr>
		   
<?php

	    $keywrd=$_GET['id'];
	  
	    $query="Select * From m_RepairPriceList Where priceCode='$keywrd'";
	    $result=mssql_query($query);  
		
		while($col = mssql_fetch_array($result))
		{	
		  if($col['isMulti'] == 1) { $isMulti='YES'; }
		  else { $isMulti='NO'; }
		  echo '<tr> 
                    <td>'.$col['isType'].'</td>
					<td>'.$col['unitSize'].'</td>
                    <td>'.$col['unitHeight'].'</td>
					<td>'.$col['LocDamage'].'</td>
                    <td>'.$col['PartDamage'].'</td>
					<td>'.$col['Act'].'</td>
                    <td style="text-align:right">'.$col['cLength'].'</td>
					<td style="text-align:right">'.$col['cWidth'].'</td>
                    <td style="text-align:right">'.$col['cQty'].'</td>
					<td>'.$isMulti.'</td>
                    <td style="text-align:right">'.$col['MH'].'</td>
					<td style="text-align:right">'.$col['materialValue'].'</td>
					<td>'.$col['Description'].'</td>
				</tr>'; 

		}
		 mssql_close($dbSQL);
?>
	
	  </table>