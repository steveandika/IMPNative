<?php
  $haveFile = "";  
  if (isset($_GET["dl"])) { $haveFile = base64_decode($_GET["dl"]); }

  if ($haveFile == "tmpappcrcc") {	
    $file_Name = "app_cr_cc.xls";
    $filePost = "template/".$file_Name;
    header("Content-Type: octet/stream");
		
    header("Content-Disposition: attachment; filename=\"".$file_Name."\"");
    $fp = fopen($filePost, "r");
    $data = fread($fp, filesize($filePost));
    fclose($fp);
    print $data;	
    echo "<script type='text/javascript'>window.close();</script>";	
  }
  
    if ($haveFile == "tmplhw") {	
    $file_Name = "laporan_hamparan_workshop.xls";
    $filePost = "template/".$file_Name;
    header("Content-Type: octet/stream");
		
    header("Content-Disposition: attachment; filename=\"".$file_Name."\"");
    $fp = fopen($filePost, "r");
    $data = fread($fp, filesize($filePost));
    fclose($fp);
    print $data;	
    echo "<script type='text/javascript'>window.close();</script>";	
  }
?>