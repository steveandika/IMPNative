<?php
  if(isset($_GET["fd"]))
  {
	$filePost = "doc/".$_GET['fd'];  
    header("Content-Type: octet/stream");
    header("Content-Disposition: attachment; filename=\"".$_GET['fd']."\"");
    $fp = fopen($filePost, "r");
    $data = fread($fp, filesize($filePost));
    fclose($fp);
    print $data;		  
  }		  
?>

<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <!-- <script src="../asset/js/modernizr.custom.js"></script>  -->

  <script src="../asset/js/sweetalert2.min.js"></script>
  <script src="../asset/js/jquery.min.2.1.1.js"></script>
</head>

<body style="overflow:auto!important"> 
<?php
    if(isset($_GET["noCnt"]) && isset($_GET["kodeBook"]) != '') 
    {
      include("../asset/libs/db.php");  	 
	
      $noCnt = strtoupper($_GET["noCnt"]);
   	  $BookID = $_GET["kodeBook"];
	
	  $noEstimate = '';	
	  $eventInDTM = '';
	  $filePDF = '';
	  $query = "Select * From RepairHeader Where containerID='$noCnt' And bookID='$BookID'";
	  $result = mssql_query($query);
	  if(mssql_num_rows($result) > 0)
	  {
	    $arrFetch = mssql_fetch_array($result);
        $noEstimate = $arrFetch["estimateID"];	  
		$filePDF = $arrFetch["dirname"];
      }		
	  mssql_free_result($result);
	  
	  $query = "Select Format(gateIn, 'yyyy-MM-dd') As tglIn From containerJournal Where BookInID='$BookID' And NoContainer='$noCnt'";
	  $result = mssql_query($query);
	  if(mssql_num_rows($result) > 0)
	  {
		$arrFetch = mssql_fetch_array($result);
        $eventInDTM = $arrFetch["tglIn"];		
      }
      mssql_free_result($result);	  
	  
?>

<div class="w3-container"> 
  <h3 style="padding:0 0 10px 0;border-bottom:1px solid #b3b6b7;color:#b3b6b7;margin-top:0">&nbsp;&nbsp;Upload Printed EOR Doc.</h3>  
  <form id="fuploadEOR" method="post" action="upload-pdfdoc" enctype="multipart/form-data">		
    <input type="hidden" name="kodeBook" value="<?php echo $BookID?>" />
	<input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
	<input type="hidden" name="noEst" value="<?php echo $noEstimate?>" />
	
    <div class="w3-row-padding">
	  <div class="w3-third"><label class="w3-text-grey">Container Number</label></div>
	  <div class="w3-third"><?php echo $noCnt;?></div>
	  <div class="w3-third">&nbsp;</div>	  
	</div>
	<div class="height-5"></div>

    <div class="w3-row-padding">
	  <div class="w3-third"><label class="w3-text-grey">Approved Estimate Number</label></div>
	  <div class="w3-third"><?php echo $noEstimate?></div>
	  <div class="w3-third">&nbsp;</div>	  
	</div>
	<div class="height-5"></div>
	
    <div class="w3-row-padding">
	  <div class="w3-third"><label class="w3-text-grey">Hamparan In Event</label></div>
	  <div class="w3-third"><?php echo $eventInDTM;?></div>
	  <div class="w3-third">&nbsp;</div>	  
	</div>	
	<div class="height-5"></div>
    <div class="w3-row-padding">	  
      <div class="w3-third"><label class="w3-text-grey" style="font-size:.830rem">*supported extension: .pdf</label> 
	    <input class="w3-input w3-border" type="file" required name="fileUser"></div>
      <div class="w3-twothird"></div>
    </div>	  
    <div class="height-5"></div>
	
    <div class="w3-row-padding">
	  <div class="w3-half"><button type="submit" class="w3-button w3-blue w3-round-small">Upload</button></div>  		
	  <div class="w3-half"></div>
	</div>  
  </form>
  <div class="height-10"></div>

	  <?php
	    if($filePDF != '') 
	    {		
	      $filePost = "doc/".$filePDF;
		  echo '<embed type="application/pdf" src="'.$filePost.'" width=900 height=700 />';
		  //$url = "attchFile.php?noCnt=".$keywrd."&kodeBook=".$kodeBook."&fd=".urlencode($filePDF);
		  //echo '<a href='.$url.' class="w3-text-blue" style="text-decoration:none;cursor:pointer">DOWNLOAD UPLOADED DOC.</a>';
		}  
	  ?>  

  <div class="height-10"></div>
</div>

<?php
      mssql_close($dbSQL);
    }
?>

</body>
</html>