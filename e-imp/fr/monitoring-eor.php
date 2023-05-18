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
	<div class="wrapper-header">
		<div class="title">Monitoring Penagihan EoR</div>
		
		<div class="header-right">
			<div class="w3-dropdown-hover">
				<button class="w3-button w3-gray">Tipe Monitoring</button>
				<div class="w3-dropdown-content w3-bar-block w3-border">
					<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=0" ?>>EoR Belum Ditagihkan</a>
					<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=1" ?>>EoR (PDF) Belum Ditagihkan</a>
					<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=2" ?>>Menunggu ID Invoice</a>
					<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."&sid=3" ?>>Penagihan Complete</a>				
				</div>
			</div>		
		</div>
	</div>
	<div class="height-10"></div>
			
<?php
		if(isset($_GET['sid']))
		{
			include("../asset/libs/new_db.php");
			
			$id = $_GET['sid'];
			if($id == 0) {include("mntreorlayout1.php");}
/*			if($id == 1) {include("mntreorlayout2.php");}
			if($id == 2) {include("mntreorlayout3.php");}
			if($id == 3) {include("mntreorlayout4.php");}*/
		}
	}
?>	

