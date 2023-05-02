<?php
	$insert_perms = 1;
	$delete_perms = 1;
	$edit_perms = 2;
	
	$sesfile = fopen("sescrud".$uid.".txt", "w");
			
	$content = (string)$insert_perms.",".(string)$delete_perms.",".(string)$edit_perms.";";
	fwrite($sesfile, $content);
	fclose($sesfile);
?>	