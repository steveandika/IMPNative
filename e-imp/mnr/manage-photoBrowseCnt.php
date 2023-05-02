<script language="php">
  include("../asset/libs/db.php");
  
  if(isset($_POST['noCnt'])) {
</script>
   <p><strong>* Catatan:</strong><br>
    Hasil dari pencarian nomor Container dibatasi pada 2 kegiatan terakhir ditandai dengan adanya Survey, dan 2 kegiatan
    terakhir di mana tidak ada kegiatan Survey pada Container ybs.</p>	
   <div class="height-10"></div>
   <table class="w3-striped">
     <thead>
	  <tr> 
	    <th>Index</th>
		<th>Workshop In</th>
		<th>Survey</th>		
	    <th>Estimate Number</th>
		<th>Workshop Out</th>
	  </tr>
     </thead>
	 <tbody>
	 
<?php	  
    $keywrd=$_POST['noCnt'];

    $query="Select * From view_Summary_Hamparan where NoContainer='$keywrd' order by gateIn Desc;";
	$resl = mssql_query($query);
	while($arr = mssql_fetch_array($resl)) {
	  $urlvar = 'get_album.php?id='.$arr["bookInID"].'&unit='.$keywrd;
	  $index++;
	  
	  echo '<tr>
	         <td>'.$arr["bookInID"].'</td>';
             
?>
			 <td>			
              <form action="get-album" name="form_".$index." target="_blank" method="get">						
			    <input type="hidden" name="reg" value="<?php echo base64_encode($arr['bookInID'])?>" />
				<input type="hidden" name="eq" value="<?php echo base64_encode($keywrd)?>" />				
				<button class="w3-text-blue" style="border:none;background:none;font-weight:500;outline:none">
				<?php echo date('Y-m-d', strtotime($arr['gateIn']))?></button>
			  </form>	
			 </td>
				
<?php
      echo ' <td>'.$arr["tanggalSurvey"].'</td>
	         <td>'.$arr["estimateID"].'</td> 
	         <td>'.$arr["DTMOut"].'</td>
			</tr>'; 

	}
	mssql_close($dbSQL);	
?>

     </tbody>
   </table>
   
<script language="php">
  }
</script>


<script type="text/javascript">	 
  function viewlog(urlVariable) { $("#album").load("get-album.php"+urlVariable); }	
</script>