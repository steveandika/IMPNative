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
			<form id="mntr-eor" method="post">
					<select class="w3-select w3-border" name="HampName" style="width: 175px;" required />
						<option value="EoRIConS">Eor IConS belum ditagihkan</option>
						<option value="EoRPDF">Eor Pelayaran belum ditagihkan</option>
						<option value="EoRInv">Eor Sudah ditagihkan belum ada No. Invoice</option>
						<option value="EoRIComplete">Lengkap</option>
					</select><span></span>			
					<input type="submit" class="w3-button w3-blue" style="border-radius:5px" name="register" value="View" />
			</form>

		</div>
	</div>
	<div class="height-10"></div>
			
<?php
		if(isset($_GET['sid']))
		{
			$id = $_GET['sid'];
			if($id == 0) {include("mntreorlayout1.php");}
/*			if($id == 1) {include("mntreorlayout2.php");}
			if($id == 2) {include("mntreorlayout3.php");}
			if($id == 3) {include("mntreorlayout4.php");}*/
		}
	}
?>	

