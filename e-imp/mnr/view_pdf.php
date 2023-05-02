<?php
  header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="' . $dirNamePDF  . '"');
  header('Content-Transfer-Encoding: binary');
  header('Accept-Ranges: bytes');
  @readfile($file);
?>