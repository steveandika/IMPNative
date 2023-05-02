<script language="php">
  session_start();  
</script>

<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS | Message</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>  
  <style>
   .display-form-shadow {-webkit-box-shadow: 0 8px 6px -6px black;-moz-box-shadow: 0 8px 6px -6px black;box-shadow: 0 8px 6px -6px black;}    
  </style>   
</head>

<body> 
<div class="w3-container"> 
<?php
  if (!isset($_SESSION["uid"])) {
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 	
  else {   
    $msq_confirmation="Proses setup remark Photo Inspeksi gagal.";
	
    if(isset($_POST['eq']) && isset($_POST['reg'])) {	 	  
	  include("../asset/libs/db.php");
	  
      $kodeBook=$_POST["reg"];
  	  $noCnt=strtoupper($_POST["eq"]);
	  $indexStatus=$_POST["statusPhoto"];
	  $remark=strtoupper($_POST["isremark"]);
	  
	  $do="If Not Exists(Select * From containerPhoto_Remark Where containerID='$noCnt' And BookID='$kodeBook' And statusPhoto=$indexStatus) Begin
	         Insert Into containerPhoto_Remark 
			 Values('$noCnt', '$kodeBook', $indexStatus, '$remark');
	       End Else Begin
	             Update containerPhoto_Remark Set RemarkStatus='$remark' Where containerID='$noCnt' And BookID='$kodeBook' And statusPhoto=$indexStatus; 
			   End;";
      $rsl=mssql_query($do);
      if($rsl) { 
	    $msg_confirmation="Proses setup remark berhasil dilakukan."; 

        $log_remark="UPDATE REMARK PHOTO ".$noCnt."/".$kodeBook;	
        $uid=$_SESSION["uid"];		
        $do="Insert Into userLogAct(userID, dateLog, DescriptionLog) Values('$uid', GETDATE(), '$log_remark') ";	
    	$rsl=mssql_query($do);		
	  }	  
    }
?>
 <div class="height-20"></div>
 <div class="display-form-shadow" style="width:380px;margin:auto;height:auto;border:1px solid #d7dbdd;background:#fdfefe">
   <div style="padding:0 10px;font-weight:500;border-bottom:1px solid #d7dbdd;background:#2196F3;line-height:25px">
     Message Info
   </div>

   <div class="height-10"></div>
   <div style="text-align:center">
     <div class="w3-container">
       <?php echo $msg_confirmation;?>
	 </div>  
   </div> 
   <div class="height-20"></div>
 
   <div style="text-align:center">
	 <form name="openovw" method="get" action="get-album">
	  <input type="hidden" name="eq" value="<?php echo base64_encode($noCnt)?>" />
	  <input type="hidden" name="reg" value="<?php echo base64_encode($kodeBook)?>" />
	  <button type="submit" class="w3-button w3-light-grey w3-border" style="padding:3px 12px!important">Confirm</button>
	 </form>   
   </div>
   <div class="height-10"></div>
  </div>
 </div>
</div> 

<?php
    mssql_close($dbSQL);
  }
?>

</body>
</html> 