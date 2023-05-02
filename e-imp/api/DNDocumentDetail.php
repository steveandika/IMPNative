<?php
   $msg = "";
	
	if(isset($_POST['isMode']))
	{	
		$isMode = $_POST['isMode'];
		$uid = $_POST['uid'];
		$docname = $_POST['docname'];
		$invnum = "";
		$eor = $_POST['eor'];
		$billParty = $_POST['billParty'];
		$liner = $_POST['liner'];
		$activity = $_POST['act'];
		$docdate = $_POST['docdate'];
		$res = "";
		
		$sql = "EXEC C_DNDocumentDetail 
					@uid = '$uid',
		            @mode = '$isMode',
					@DocName = '$docname',
					@InvNum = '$invnum',
					@InvDate = NULL,
					@EOR = '$eor',
					@BillParty = '$billParty',
					@shortName = '$liner',
					@activityType = '$activity',
					@DocDate = '$docdate',
					@Result = '$res'; ";
		
		include($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");
		
		$msg = openDB();
		if($msg != "connected")
		{
			$msg = "Database utama tidak bisa di akses. Proses tidak dapat dilanjutkan.";
		}
		else 
		{
			$msg = "";
			$stmt =  mssql_query($sql);
			if (!$stmt) { //$msg = "Terjadi kesalahan saat eksekusi penambahan data. Data tidak tersimpan.";
                            $msg = $sql;	}
			else { $msg = "Accepted"; }
		}
	}
	
	echo $msg;
?>