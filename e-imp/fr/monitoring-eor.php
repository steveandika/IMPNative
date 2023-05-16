	<?php
		 $defHTML = $_SESSION['defurl'];
	?>
	
	<div class="height-10"></div>
	<div class="wrapper-header">
		<div class="header-right">
			<a href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=0" ?>><b>EoR Belum Ditagihkan</b></a>
			<a href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=1" ?>><b>EoR (PDF) Belum Ditagihkan</b></a>
			<a href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=2" ?>><b>Menunggu ID Invoice </b></a>
			<a href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=3" ?>><b>Penagihan Complete</b></a>
		</div>
	</div>
	<div class="height-10"></div>