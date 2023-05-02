<?php
	if(isset($_GET['noCnt'])) 
	{	  
		include("../asset/libs/common.php"); 
		openDB();
	
		$noCnt = strtoupper($_GET['noCnt']);
		$err = 0;
		$msg = "";
	
		if(strlen($noCnt) == 11) 
		{
			$result = validUnitDigit($noCnt);	  
			if($result != 'OK') { $msg = "Warning: ".$result;}
		} 
		else 
		{
			$msg = "No Container tidak standard. Minimum 11 digit";
			$err = 1;
	    }	  
		echo $msg;
	
		if($err == 0) 
		{
			$query = "Select * from containerJournal with (NOLOCK) 
					  where NoContainer = '$noCnt' 
					    and gateOut Is Null and gateIn Is Not Null 
						and bookInID not like '%BATAL'; ";
	  
			$stmt = mssql_query($query);
			$numRecord = mssql_num_rows($stmt);			 
			mssql_free_result($stmt);	
	
			if ($numRecord <= 0) {	
				$size = '';
				$tipe = ''; 
				$height = '';
				$mnfr = '';
				$constr = '';
				$principle = '';
				$consignee = '';
				$kodeBooking = '';

				$query = "Select * from containerLog with (NOLOCK) 
						  where containerNo='$noCnt';";

				$stmt = mssql_query($query);
				$numRecord = mssql_num_rows($stmt);
				if($numRecord > 0) 
				{
					$col = mssql_fetch_array($stmt);
					$size = $col['Size'];
					$tipe = $col['Type']; 
					$height = $col['Height'];
					$mnfr = $col['Mnfr'];
					$constr = $col['Constr'];		
				}
				mssql_free_result($stmt);	
				
				include("gatein_detail_ui.php");  				
			} 
			else 
			{
				$msg = "Ditemukan data container terkait yang masih aktif (gate out = null). Proses In Hamparan tidak bisa dilanjutkan.";	
				echo $msg;
			}	
		}	  
		else 
		{
			echo '<script type="text/javascript>doreset_inhamparan();</script>';		
		}
	}	
?>

<script type="text/javascript">
  function doreset_inhamparan() { 
    $url="/e-imp/mnr/?do=hw_registry";  
	location.replace($url); 
  }     
</script>     