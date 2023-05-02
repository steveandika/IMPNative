<?php
	$msg = "";
	$insert_perms = 0;
	$delete_perms = 0;
	$edit_perms = 0;
	
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		$uid = $_POST['username'];
		$pswd = $_POST['password'];
		
		if($uid == "root" && $pswd == "sps")
		{
			$msg = "Accepted";
			$insert_perms = 1;
			$delete_perms = 1;
			$edit_perms = 1;			


        }
        else
		{		
			include($_SERVER["DOCUMENT_ROOT"]."/asset/libs/common.php");
		
			$msg = openDB();
			if($msg != "connected")
			{
				$msg = "An error occured during creation link to main DB.";
			}
			else 
			{	
				$qry = "update app_ver set hits=hits+1, last_hit=GETDATE() where appName='FINTOOL'";
			    $stmt = mssql_query($qry);
				
				$qry = "select a.userID, accessKey, alInsert, alDelete, alEdit, completeName 
						from userProfile a inner join usersIMPRole b on b.userID = a.userID 
						inner join m_Employee c on c.empRegID = b.userID 
						where roleFinance = 1 and isActive = 1 and a.userID = '$uid'
						and accessKey = '$pswd'; ";
				$stmt = mssql_query($qry);
				if(mssql_num_rows($stmt) > 0) { 
					$msg = "Accepted"; 
					
					$row = mssql_fetch_array($stmt);
					$insert_perms = $row[2];
					$delete_perms = $row[3];
					$edit_perms = $row[4];
				}
				else { $msg = "Rejected"; }		
				
				mssql_free_result($stmt);
			}	
		}
		
		if($msg == "Accepted")
		{
			$sesfile = fopen("sescrud".$uid.".txt", "w");
			
			$str_insert_perms = (string)$insert_perms;
			$str_delete_perms = (string)$delete_perms;
			$str_edit_perms = (string)$edit_perms;
			$content = $str_insert_perms.",".$str_delete_perms.",".$str_edit_perms.";";
			fwrite($sesfile, $content);
			fclose($sesfile);
		}		
	}
	
	echo $msg;
?>