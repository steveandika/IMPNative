<?php
  session_start();    
?>

<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
<div class="w3-container">
  <div id="messg"></div>
  <div class="height-20"></div>
  
<?php
  include("../asset/libs/db.php");

  $keywrd = $_POST["noCnt"];  
  $kodeBook = $_POST["kodeBook"];  
  $noEstimate = $_POST["noEst"];
  
  $uid = $_SESSION["uid"];
 
  $extension = "PDF";
      
  echo '<script language="javascript">document.getElementById("messg").innerHTML="Uploading, please wait...";</script>';	

  $file_name = basename($_FILES["fileUser"]["name"]);
  $file_tmp = $_FILES["fileUser"]["tmp_name"];
  $ext = strtoupper(pathinfo($file_name,PATHINFO_EXTENSION));	
  if(strtoupper($ext) == $extension)
  {
    $dateUpload = date('Y-m-d');
	if(!file_exists("doc/".$file_name))
	{
	  move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"],"doc/".$file_name);      
	  $query = "Update RepairHeader Set dirName='$file_name', loadDate='$dateUpload', CREATEDBY='$uid', 
	            CREATE_DTTM ='$dateUpload',REC_STATUS=1, LAST_UPDATE='$dateUpload' 
	            Where bookID='$kodeBook' And containerID='$keywrd'";	  
    }		
	else
	{
      $filename = basename($file_name,$ext);
      $newFileName = $filename.time().".".$ext;
	  echo $ext;
      move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"],"doc/".$newFileName);
	  $query = "Update RepairHeader Set dirName='$newFileName', loadDate='$dateUpload', CREATEDBY='$uid', 
	            CREATE_DTTM ='$dateUpload', REC_STATUS=1, LAST_UPDATE='$dateUpload'
	            Where bookID='$kodeBook' And containerID='$keywrd'";	  		
    }		
	$result = mssql_query($query);
	echo '<script language="javascript">document.getElementById("messg").innerHTML="Proses upload selesai.";</script>';		
  }
  else
  {
	echo '<script language="javascript">document.getElementById("messg").innerHTML="File ditolak. Pastikan ekstensi file dalam format PDF/pdf.";</script>';	  
  }
  
  $url = "attchFile.php?noCnt=".$keywrd."&kodeBook=".$kodeBook;
  echo '<div class="w3-container">
		  <p>Proses unggah selesai. <strong>Catatan:</strong> '.$error.' Tekan <a href='.$url.' class="button-blue">Ok</a> untuk kembali.</p>
		</div>';
  
  mssql_close($dbSQL);
?>

</div>