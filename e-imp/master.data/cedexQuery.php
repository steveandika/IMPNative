<?php
  session_start();
  
  if(isset($_GET["saved"])) {
    if($_GET["saved"]==1) { echo '<script>swal("Berhasil", "Proses pembaharuan data terkait berhasil.")</script>'; }
	else { echo '<script>swal("Update Gagal", "Proses penyimpanan gagal dilakukan.")</script>'; }	  
  }
  
  if(isset($_GET["repairCode"]) || isset($_GET["unitsize"]) || isset($_GET["damloc"]) || isset($_GET["dampart"])) {	  
    include("../asset/libs/db.php");

	$priceCode = $_GET["repairCode"];
	$unitSize = "%".$_GET["unitsize"]."%"; 
	$damloc = $_GET["damloc"]."%";  
	$dampart = $_GET["dampart"]."%"; 
	$repair = $_GET["act"]."%"; 
	$qty = $_GET["qty"];
	$size1 = $_GET["size1"];
	$size2 = $_GET["size2"];
	
	$term = "";
	$filter = "";
	if($priceCode != "") { $terms = " priceCode = '$priceCode' "; }
	if($unitSize != "%%") {
	  if($terms != "") { $terms = $terms." And unitSize Like  '$unitSize' "; }
      else { $terms = " unitSize Like '$unitSize' "; } 	  
    }		
	if($damloc != "%") {
	  if($terms != "") { $terms = $terms." And LocDamage Like '$damloc' "; }
      else { $terms = " LocDamage Like '$damloc' "; } 	  
    }		
	if($dampart != "%")	{
	  if($terms != "") { $terms = $terms." And PartDamage Like '$dampart' "; }
      else { $terms = " PartDamage Like '$dampart' "; } 	  
    }		
	if($repair != "%") {
	  if($terms != "") { $terms = $terms." And Act Like '$repair' "; }
      else { $terms = " Act Like '$repair' "; } 	  
    }	
	if($qty != "") {
	  if($terms != "") { $terms = $terms." And cQty = $qty "; }
      else { $terms = " cQty = $qty "; } 	  
    }			
	if($size1 != "") {
	  if($terms != "") { $terms = $terms." And (cLength = $size1 or cWidth = $size1) "; }
      else { $terms = " (cLength = $size1 or cWidth = $size1) "; } 	  
    }			
	if($size2 != "") {
	  if($terms != "") { $terms = $terms." And (cLength = $size2 or cWidth = $size2) "; }
      else { $terms = " (cLength = $size2 or cWidth = $size2)"; } 	  
    }			
	
    $scriptSQL=	"Select * From m_RepairPriceList with (NOLOCK) where ".$terms.
	            "Order By priceCode, isType, unitSize, unitHeight, LocDamage, PartDamage, Act, cLength, cWidth, cQty, isMulti, MH, materialValue; ";		
	$sql = $scriptSQL;
	$res = mssql_query($sql);
	
    $urlfilter = "/e-imp/master.data/?show=cedexBrowse&".$filter   
?>
<div class="margin-left-10" style="position:fixed !important;margin-top:10px;width:1090px;height:55vh!important;overflow:auto">
    <?php if (strtoupper($_SESSION["uid"])=="ROOT") { echo $sql."<div class='height-10'></div>"; } ?>
	
	 <table class="w3-table w3-bordered">
	  <thead>
	   <tr>
	    <th colspan="2">Index</th>
		<th>ISO Size</th>
		<th>Height</th>
		<th>Loc. Dmg</th>
		<th>Part Dmg</th>
		<th>Repair/Act</th>
		<th>Qty.</th>
		<th>L1</th>
		<th>L2</th>
		<th>Multi?</th>
		<th>M/H</th>
		<th>Material</th>
		<th>Description</th>
	   </tr>
	  </thead>
	  
	  <tbody>

      <?php $index = 0;
	        $design="";
    
	        while($col = mssql_fetch_array($res)) {
	          $index++;
	          if($col["isMulti"] == 0) { $isMulti = "N"; }
	          else { $isMulti = "Y"; }
		
	          $url="priceCode=".$col["priceCode"]."&isType=".$col["isType"]."&unitSize=".$col["unitSize"]."&unitHeight=".$col["unitHeight"].
		           "&damloc=".str_replace(" ","%",$col["LocDamage"])."&dampart=".str_replace(" ","%",$col["PartDamage"])."&act=".str_replace(" ","%",$col["Act"]).
		           "&qty=".$col["cQty"]."&size1=".$col["cLength"]."&size2=".$col["cWidth"].
			       "&isMulti=".$col["isMulti"]."&MH=".$col["MH"]."&material=".number_format($col["materialValue"],2)."&desc=".str_replace(" ","+",$col["Description"]);
        
		//
	  ?>	
	    <tr>
		  <td><?php echo $index."." ?></td>
		  <td><a onclick=openDetail('<?php echo $url?>') class="w3-text-blue" style="text-decoration:none;font-weight:500;cursor:pointer">Edit</a></div></td>
   	      <td><?php echo $col["unitSize"] ?></td>
		  <td><?php echo $col["unitHeight"] ?></td>
		  <td><?php echo $col["LocDamage"] ?></td>
		  <td><?php echo $col["PartDamage"] ?></td>
		  <td><?php echo $col["Act"] ?></td>
		  <td style='text-align:right'><?php echo number_format($col["cQty"],2,",",".") ?></td>
		  <td style='text-align:right'><?php echo number_format($col["cLength"],2,",",".") ?></td>
		  <td style='text-align:right'><?php echo number_format($col["cWidth"],2,",",".") ?></td>
		  <td><?php echo $isMulti ?></td>
		  <td style='text-align:right'><?php echo $col["MH"];//number_format($col["MH"],2,",",".") ?></td>
		  <td style='text-align:right'><?php echo number_format($col["materialValue"],2,",",".") ?></td>
		  <td><?php echo $col["Description"] ?></td>
        </tr>		   
	  <?php if (strtoupper($_SESSION["uid"])=="ROOT") 
	         // { echo "<tr><td colspan='14'><strong>Programmer mode: </strong>material=".number_format($col["materialValue"],2,",","")."</td></tr>"; }
				 { echo "<tr><td colspan='14'><strong>Programmer mode: </strong>".$url."</td></tr>"; }
	     
      ?>	  
      <?php }
            mssql_free_result($res);	
            mssql_close($dbSQL);			
      ?>
	  
	  </tbody>
	 </table>	 
</div>

<?php } ?>

<script type="text/javascript">   
  function openDetail(urlVar) {
    $("#process").load("cedexManage.php?"+urlVar);	  
  }	  
</script>