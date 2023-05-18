<?php
	if(!isset($_SESSION["uid"])) 
	{
		$url="../"; 
		echo "<script type='text/javascript'>location.replace('$url');</script>"; 
	} 
	else 
	{ 
?>
	
		<div class="height-10"></div>
		<table class="w3-table">
			<tr>
				<td><h6>Monitoring Penagihan EoR</h6></td>		
			</tr>
			<tr>
				<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=0" ?>>EoR Belum Ditagihkan</a>
				<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=1" ?>>EoR (PDF) Belum Ditagihkan</a>
				<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=2" ?>>Menunggu ID Invoice</a>
				<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=3" ?>>Penagihan Complete</a>		
			</tr>
			
		</table> 
		<div class="height-10"></div>
		<div class="w3-container w3-responsive w3-light-grey w3-round-large">
			
	<?php
		if(isset($_GET['sid']))
		{
			include("asset/libs/new_db.php");
			
			$id = $_GET['sid'];
			if($id == 0) {include("mntreorlayout1.php");}
/*			if($id == 1) {include("mntreorlayout2.php");}
			if($id == 2) {include("mntreorlayout3.php");}
			if($id == 3) {include("mntreorlayout4.php");}*/
		}		
	?>
		
		</div>
		
<?php		

	}
?>	

