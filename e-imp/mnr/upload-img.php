<link rel="stylesheet" type="text/css" href="../asset/css/master.css" />

<div class="height-20"></div>
<div class="wrapper w3-container">
  <div id="messg"></div>
  
<script language="php">
  include("../asset/libs/db.php");

  $keywrd = $_POST["noCnt"];  
  $kodeBook = $_POST["kodeBook"];  
  $status = $_POST["statusPhoto"];

  if(isset($_POST["tgl_survey"])) {
	$tglSurvey=$_POST["tgl_survey"];
    $do="UPDATE containerJournal SET tanggalSurvey='$tglSurvey' WHERE bookInID='$kodeBook' AND NoContainer='$keywrd'; ";
    $rsl=mssql_query($do);			 
  }	  
   
  $error = array();
  $extension = array("JPEG","JPG","PNG","BMP");
  $valid=1;

  foreach($_FILES["fileUser"]["tmp_name"] as $key=>$tmp_name)
  {
      $file_name = $_FILES["fileUser"]["name"][$key];
      $file_tmp = $_FILES["fileUser"]["tmp_name"][$key];
      $ext = strtoupper(pathinfo($file_name,PATHINFO_EXTENSION));	
      echo '<script language="javascript">document.getElementById("messg").innerHTML="Uploading '.$file_name.', please wait...";</script>';  	  
	  
      if(in_array($ext,$extension))
      {	
        if(strtoupper($status)== "INDEX") {
		  $qry="Select * From containerPhoto Where containerID='$keywrd' And BookID='$kodeBook' And statusPhoto='$status';";	
		  $rsl=mssql_query($qry);
		  if(mssql_num_rows($rsl) > 0) { echo 'Failed, Index is already setup .. '.$file_name.' rejected. <br>'; $valid=0; }
		  else { $valid=1; }
		}
		else { $valid=1; }
		
		if($valid==1) {
          $dateUpload = date('Y-m-d');
          if(!file_exists("photo/".$file_name))
          {
            move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"][$key],"photo/".$file_name);      
            $query = "Insert Into containerPhoto(containerID, estimateID, statusPhoto, directoryName, dateUpload, BookID)
		              Values('$keywrd', '', '$status', '$file_name', '$dateUpload', '$kodeBook'); ";
			if(!$query) { echo 'Failed, Index is already setup .. '.$file_name.' rejected. <br>'; }
  	      }
          else
          {
            $filename = basename($file_name,$ext);
            $newFileName = $filename.time().".".$ext;
            move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"][$key],"photo/".$newFileName);
            $query = "Insert Into containerPhoto(containerID, estimateID, statusPhoto, directoryName, dateUpload, BookID)
		              Values('$keywrd', '', '$status', '$newFileName', '$dateUpload', '$kodeBook'); ";		  
          }          
		  $result = mssql_query($query);		
		}  
//         echo $query;		
      }
      else { echo 'Invalid extension.. '.$file_name.'<br>' ; }	
  }

  /*  
  else
  { 
    echo $_FILES["fileUser"]["name"];
    $file_name = $_FILES["fileUser"]["name"];
    $file_tmp = $_FILES["fileUser"]["tmp_name"];
    $ext = strtoupper(pathinfo($file_name,PATHINFO_EXTENSION));	
    if(in_array($ext,$extension))
    {	  	
      $dateUpload = date('Y-m-d');
      if(!file_exists("photo/".$file_name))
      {
        move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"],"photo/".$file_name);      
        $query = "Insert Into containerPhoto(containerID, estimateID, statusPhoto, directoryName, dateUpload, BookID)
	              Values('$keywrd', '', 'INDEX', '$file_name', '$dateUpload', '$kodeBook'); ";
  	  }
      else
      {
        $filename = basename($file_name,$ext);
        $newFileName = $filename.time().".".$ext;
        move_uploaded_file($file_tmp = $_FILES["fileUser"]["tmp_name"],"photo/".$newFileName);
        $query = "Insert Into containerPhoto(containerID, estimateID, statusPhoto, directoryName, dateUpload, BookID)
  	              Values('$keywrd', '', '$INDEX', '$newFileName', '$dateUpload', '$kodeBook'); ";		  
      }          
	  $result = mssql_query($query);				
	}  
  }
*/
  
  echo '<script language="javascript">document.getElementById("messg").innerHTML="Finish...";</script>';	
  $url = "get-album?reg=".base64_encode($kodeBook)."&eq=".base64_encode($keywrd);
  
  if($valid==1) {
    echo '<div class="height-10"></div><p>Upload was finished. <a href='.$url.' class="button-blue">Confirm</a></p>';
  }
  else {
    echo '<div class="height-10"></div><p>There was an error while trying to save/upload image(s). <a href='.$url.' class="button-blue">Confirm</a></p>';
	  
  }	  
  

/*  
  $validNoContainer=0;
  
  $kodeEstimate='ERR';
  if(isset($_POST['noCnt']) && isset($_POST['kodeBook'])) {
	$kodeEstimate = $_POST['kodeEstimate'];
  	$imageFileName = basename($_FILES["fileUser"]["name"]);
    $imageFileSize = $_FILES['fileUser']['size'];
	$imageTempName = $_FILES['fileUser']['tmp_name'];
    $target_file = "photo/".$imageFileName;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	$url = "get-album.php?id=".$kodeBook."&unit=".$keywrd;

	if ((strtoupper($imageFileType) == "JPG") || (strtoupper($imageFileType) == "JPEG") || (strtoupper($imageFileType) == "PNG") || (strtoupper($imageFileType) == "BMP")) {	  
	  $query = "Select * From containerPhoto Where containerID='$keywrd' And directoryName='$imageFileName' And bookID='$kodeBook'";
	  $result = mssql_query($query);
	  if(mssql_num_rows($result) <= 0) {
		echo '<script language="javascript">document.getElementById("emssg").innerHTML="on progress";</script>';			
		
		if (move_uploaded_file($imageTempName, $target_file)) {		
		  $dateUpload = date('Y-m-d');    
		  $query = "Insert Into containerPhoto(containerID, estimateID, statusPhoto, directoryName, dateUpload, BookID)
		           Values('$keywrd', '', '$status', '$imageFileName', '$dateUpload', '$kodeBook'); ";
		  $result = mssql_query($query);
		  echo '<div class="w3-container">
		          <p>Proses unggah image berhasil. Tekan <a href='.$url' class="w3-button w3-pink">Confirm</a> untuk kembali.</p>
		        </div>';
        }		
	  }
	  else { 
  	    echo '<div class="w3-container">
		        <p>Proses unggah image gagal. File sudah pernah di unggah. 
				   Tekan <a href='.$url' class="w3-button w3-pink">Ok</a> untuk kembali.</p>
		      </div>';
	  }	  
    }
	else { 
  	  echo '<div class="w3-container">
		      <p>Proses unggah image gagal. Ekstensi File tidak sesuai kriteria. Tekan <a href='.$url' class="w3-button w3-pink">Ok</a> untuk kembali.</p>
		    </div>';	
	}			
  
    
  }*/
</script>  
</div>

<script>	 
  function doclose() { window.close(); }	
</script>