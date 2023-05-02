<script language="php">
  if(isset($_GET['dl']) && $_GET['dl'] == 'template') {	
    $file_Name = "sld_template.xls";
    $filePost = "template/".$file_Name;
    header("Content-Type: octet/stream");
		
    header("Content-Disposition: attachment; filename=\"".$file_Name."\"");
    $fp = fopen($filePost, "r");
    $data = fread($fp, filesize($filePost));
    fclose($fp);
    print $data;	
    echo "<script type='text/javascript'>window.close();</script>";	
  }

  if(isset($_GET['dl']) && $_GET['dl'] == 'hw_template') {	
    $file_Name = "opname_hw_template.xls";
    $filePost = "template/".$file_Name;
    header("Content-Type: octet/stream");
		
    header("Content-Disposition: attachment; filename=\"".$file_Name."\"");
    $fp = fopen($filePost, "r");
    $data = fread($fp, filesize($filePost));
    fclose($fp);
    print $data;	
    echo "<script type='text/javascript'>window.close();</script>";	
  }
  
</script>