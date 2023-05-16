	<div class="height-10"></div>
	<div class="wrapper-header">
		<div class="title">Monitoring Penagihan EoR</div>
		<div class="header-right">
			<div class="w3-dropdown-hover">
				<button class="w3-button w3-gray">Tipe Monitoring</button>
				<div class="w3-dropdown-content w3-bar-block w3-border">
			<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=0" ?>><b>EoR Belum Ditagihkan</b></a>
			<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=1" ?>><b>EoR (PDF) Belum Ditagihkan</b></a>
			<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=2" ?>><b>Menunggu ID Invoice </b></a>
			<a class="w3-bar-item w3-button" href=<?php echo "/e-imp/1?src=".base64_encode("fr/monitoring-eor.php")."?sid=3" ?>><b>Penagihan Complete</b></a>				
				</div>
			</div>
		</div>
	</div>
	<div class="height-10"></div>