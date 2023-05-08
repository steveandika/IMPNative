<?php
  if(isset($_GET['have'])) {	
    $have=base64_decode($_GET['have']);
	if($have=="have_template") {
      $file_Name = "template_price_list.xls";
      $filePost = "template/".$file_Name;
      header("Content-Type: octet/stream");		
      header("Content-Disposition: attachment; filename=\"".$file_Name."\"");
      $fp = fopen($filePost, "r");
      $data = fread($fp, filesize($filePost));
      fclose($fp);
      print $data;	
      echo "<script type='text/javascript'>window.close();</script>";	
	}
  }
?>