<?php
  session_start();  
?>

<!DOCTYPE html>
<html style="overflow-y:auto!important">
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <title>I-ConS | Inspection Image</title>
 
  <link rel="shortcut icon" href="../asset/img/office.png" type="image/x-icon" /> 
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>  
  <script src="../asset/js/jquery.min.2.1.1.js"></script>

</head>

<body style="overflow:auto!important"> 
<?php
  if (!isset($_SESSION["uid"])) {
    $url = "/"; 
	echo "<script type='text/javascript'>location.replace('$url');</script>"; } 	
  else {   
    if (strtoupper($_SESSION["uid"])=="ROOT") {
	  $kodeBook = strtoupper(base64_decode($_GET["reg"]));
  	  $noCnt = strtoupper(base64_decode($_GET["eq"]));
	  $ac=base64_decode($_GET["ac"]);
	  echo "programmer : ".$kodeBook." ".$noCnt." AC: ".$ac."<br>";
	}		
	
    if(isset($_GET["eq"]) && isset($_GET["reg"])) {	 	  
	  include("../asset/libs/db.php");
	  
      $kodeBook = strtoupper(base64_decode($_GET["reg"]));
  	  $noCnt = strtoupper(base64_decode($_GET["eq"]));
 
	  if(isset($_GET["ac"])) {
	    $file_name = base64_decode($_GET["fd"]);
	
	    $do = "Delete From containerPhoto Where BookID='$kodeBook' And directoryName='$file_name' And containerID='$noCnt'";
	    $resl = mssql_query($do);
	    $dirfile = "photo/".$file_name;
        try { 
	      unlink($dirfile);
		} catch (Exception $e) { 		
		    echo '<script type="text/javascript">alert("There was an error while trying to delete selected file.","error");</script>'; 
		  }
	  }
	 
	  $allow_remark = 0;
      $qry = "Select * From containerPhoto Where BookID = '$kodeBook' And containerID = '$noCnt' ";	
	  $rsl = mssql_query($qry);
	  if (strtoupper($_SESSION["uid"])=="ROOT") { echo $qry."<br>"; }
	  if (mssql_num_rows($rsl) > 0) { $allow_remark = 1; }
	  mssql_free_result($rsl);
	
	  $allow_update = 1;
	
	  $tglSurvey = "";
	  $qry = "SELECT CAST(tanggalSurvey AS DATE) AS tglSurvey FROM containerJournal WHERE bookInID= '$kodeBook' And NoContainer= '$noCnt'; ";
	  $rsl = mssql_query($qry);
	  $arr = mssql_fetch_array($rsl);
	  $tglSurvey = $arr["tglSurvey"];
	  mssql_free_result($rsl);
?>

<div class="w3-container">  	
 <div class="height-10"></div>
 <div style="max-width:1280px;top:10px;bottom:10px;margin:0 auto;background:#fff;">
  <div class="height-20"></div> 
  
  <div class="w3-row-padding">
   <div class="w3-third">
	 
	 <div class="w3-container" style="background:#f8f9f9">
	   <div class="height-5"></div>
 	   <label style="font: 600 18px/35px Play,Arial,Tahoma,sans-serif;">Upload Image</label>
	   <div class="height-5"></div>
	 
       <form id="fupload" method="post" action="upload-img" enctype="multipart/form-data">		
         <input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />
	     <input type="hidden" name="kodeBook" value="<?php echo $kodeBook?>" />
		 
         <label><strong>Survey Date :</strong></label>
<?php   if($tglSurvey=="") {
           echo '<input type="date" class="w3-input w3-border" name="tgl_survey" required />';
        } else {
             echo '<input type="text" readonly class="w3-input w3-border" value="'.$tglSurvey.'" />';			
		  } 			
?>		 
         <div class="height-5"></div>
         <label><strong>Image ID :</strong></label>
         <select class="w3-select w3-border" name="statusPhoto" >
	        <option value="INDEX">Index</option>
	        <option value="BEFORE">Before Repair</option>
	        <option value="AFTER">After Repair</option>
         </select>
         <div class="height-5"></div> 
		 
		 <label class="w3-text-grey" style="font-size:.830rem">*supported extension: .jpg, .jpeg, .png, .bmp</label> 
         <input class="w3-input" type="file" required name="fileUser[]" multiple />
		 <div class="height-10"></div>
         
		 <button type="submit" <?php if ($allow_update == 0) { echo "disabled"; } ?> class="imp-button-grey-blue">Upload Image</button>
	   </form>
	   <div class="height-20"></div>
	 </div>	 
	 <div class="height-10"></div>
	 
	 <div class="w3-container" style="background:#e9f7ef">
	   <div class="height-5"></div>
 	   <label style="font: 600 18px/35px Play,Arial,Tahoma,sans-serif;">Setup Remark</label>
	   <div class="height-5"></div>

       <form id="fupload" method="post" action="store-img-rem">			   
	     <input type="hidden" name="eq" value="<?php echo $noCnt?>" />
		 <input type="hidden" name="reg" value="<?php echo $kodeBook?>" />

         <label><strong>Image ID :</strong></label>
         <select class="w3-select w3-border" name="statusPhoto" >
	        <option value=0>Index</option>
	        <option value=1>Before Repair</option>
	        <option value=2>After Repair</option>
         </select>
         <div class="height-5"></div> 

         <label><strong>Remark :</strong></label>		 
		 <input type="text" required class="w3-input w3-border" style="text-transform:uppercase" name="isremark" maxlength=80 />		 
		 <div class="height-10"></div>
         
		 <button type="submit" <?php if($allow_remark==0) { echo "disabled"; } ?> class="imp-button-grey-blue">Set Remark</button>		 
	   </form>
	   <div class="height-20"></div>
	 </div>
	 <div class="height-10"></div>
	 
	 <div class="w3-container" style="background:#f9e79f">
	   <div class="height-5"></div>
 	   <label style="font: 600 18px/35px Play,Arial,Tahoma,sans-serif;">Image Print</label>
	   <div class="height-5"></div>
	   <a class="button-blue" href="viewPh_noEstimate?eq=<?php echo base64_encode($noCnt)?>&reg=<?php echo base64_encode($kodeBook)?>&id=1" target="_blank">Print</a>
	   <div class="height-20"></div>
	 </div>
	 
   </div>
   
   <div class="w3-twothird">   
     <?php echo '<div id="frameindex" style="width:90%;text-align:center;margin:0 auto;height:23px;border:1px solid #ddd">
                  INDEX IMAGE
                 </div>		
		         <div class="height-10"></div>';
           
		   $remark_status = "";
		   $query = "Select RemarkStatus From containerPhoto_Remark Where BookID = '$kodeBook' And containerID = '$noCnt' And statusPhoto = 0;";
		   $result = mssql_query($query);
		   if(mssql_num_rows($result) > 0) {
		     $arr = mssql_fetch_array($result);
             $remark_status = $arr["RemarkStatus"]; 			 
		   }
		   mssql_free_result($result);
		   
		   echo '<div id="indexRemark" style="width:90%;text-align:center;margin:0 auto">'.$remark_status.'</div>
		         <div class="height-10"></div>';
		   
           $query = "Select Top(1) * From containerPhoto Where BookID='$kodeBook' And containerID='$noCnt' And UPPER(statusPhoto) = 'INDEX';";
           $result = mssql_query($query);
           while($cols = mssql_fetch_array($result)) {
	         $photoName = $cols['directoryName'];  
             $photoDir = '"photo/'.$photoName.'"';
	        
			 echo '<div id="indexImage" style="width:90%;text-align:center;margin:0 auto">
	                 <img src='.$photoDir.' height="100" width="160" style="border:1px solid #f2f4f4" />
					 <div class="height-5"></div>
					 '.$photoName.'
					 <div class="height-5"></div>
                     <form name="fdelete_"'.$i.' action="get-album" method="get">
	                   <input type="hidden" name="reg" value="'.base64_encode($kodeBook).'" />
			           <input type="hidden" name="eq" value="'.base64_encode($noCnt).'" />
			           <input type="hidden" name="fd" value="'.base64_encode($photoName).'" />
			           <input type="hidden" name="ac" value="'.base64_encode('del').'" />';
            if($allow_update == 1) { echo '<button class="w3-button w3-round-small w3-red">Remove</button>'; }					   
			else { echo '<button class="w3-button w3-round-small w3-red" disabled>Remove</button>'; }					   
	        
            echo '   </form>		   
		           </div>
		           <div class="height-20"></div>';		  
           }
           mssql_free_result($result);

           echo '<div id="frameindex" style="width:90%;text-align:center;margin:0 auto;height:23px;border:1px solid #ddd">
                   BEFORE REPAIR
                 </div>		
		         <div class="height-10"></div>';
		   $remark_status="";
		   $query="Select RemarkStatus
		           From   containerPhoto_Remark
				   Where   BookID='$kodeBook' And containerID='$noCnt' And statusPhoto = 1";
		   $result=mssql_query($query);
		   if(mssql_num_rows($result) > 0) {
		     $arr=mssql_fetch_array($result);
             $remark_status=$arr['RemarkStatus']; 			 
		   }
		   mssql_free_result($result);
				 
           echo '<div id="beforeRemark" style="width:90%;text-align:center;margin:0 auto">'.$remark_status.'</div>
		         <div class="height-10"></div>';
		   
           $query = "Select * 
		             From containerPhoto 
					 Where  BookID='$kodeBook' And containerID='$noCnt' And UPPER(statusPhoto) = 'BEFORE'";
           $result = mssql_query($query);
           $rows = mssql_num_rows($result);
           $i=0;
		   
           while($i <$rows) {    
             echo '<div class="w3-row-padding" style="width:90%;margin:0 auto">';
	         for($col=1; $col<=4; $col++) {
	           echo '<div class="w3-quarter" style="text-align:center">';	
               if($i <$rows) { 
                 $photoName=mssql_result($result, $i, 'directoryName');
	             $photoDir='"photo/'.$photoName.'"';	
		//echo $photoName.'<br>';
	             echo  '<img src='.$photoDir.' height="100" width="160" style="border:1px solid #f2f4f4" />
				        <div class="height-5"></div>
					    '.$photoName.'
					    <div class="height-5"></div>

                        <form name="fdelete_"'.$i.' action="get-album" method="get">
	                      <input type="hidden" name="reg" value="'.base64_encode($kodeBook).'" />
		                  <input type="hidden" name="eq" value="'.base64_encode($noCnt).'" />
 			              <input type="hidden" name="fd" value="'.base64_encode($photoName).'" />
			              <input type="hidden" name="ac" value="'.base64_encode('del').'" />';
                 if($allow_update == 1) { echo '<button class="w3-button w3-round-small w3-red">Remove</button>'; }					   
			     else { echo '<button class="w3-button w3-round-small w3-red" disabled>Remove</button>'; }					   
	        
                echo '   </form>';		   
   	             $i++;
               }
	           echo '</div>';	  
	         }
	         echo '</div>
			       <div class="height-20"></div>';
           }
           mssql_free_result($result);

           echo '<div id="frameindex" style="width:90%;text-align:center;margin:0 auto;height:23px;border:1px solid #ddd">
                   AFTER REPAIR
                 </div>		
		         <div class="height-10"></div>';
		   $remark_status="";
		   $query="Select RemarkStatus
		           From   containerPhoto_Remark
				   Where   BookID='$kodeBook' And containerID='$noCnt' And statusPhoto = 2";
		   $result=mssql_query($query);
		   if(mssql_num_rows($result) > 0) {
		     $arr=mssql_fetch_array($result);
             $remark_status=$arr['RemarkStatus']; 			 
		   }
		   mssql_free_result($result);
				 
           echo '<div id="afterRemark" style="width:90%;text-align:center;margin:0 auto">'.$remark_status.'</div>
		         <div class="height-10"></div>';
           $query = "Select * 
		             From containerPhoto 
					 Where  BookID='$kodeBook' And containerID='$noCnt' And UPPER(statusPhoto) = 'AFTER'";
           $result = mssql_query($query);
           $rows = mssql_num_rows($result);
           $i=0;
           while($i <$rows) {    
             echo '<div class="w3-row-padding" style="width:90%;margin:0 auto">';
	         for($col=1; $col<=4; $col++) {
	           echo '<div class="w3-quarter" style="text-align:center">';	 
               if($i <$rows) { 
	             $photoName=mssql_result($result, $i, 'directoryName');
	             $photoDir='"photo/'.$photoName.'"';	
		         //$photoName.'<br>
	             echo  '<img src='.$photoDir.' height="100" width="160" style="border:1px solid #f2f4f4" />
				        <div class="height-5"></div>
					    '.$photoName.'
					    <div class="height-5"></div>

                        <form name="fdelete_"'.$i.' action="get-album" method="get">
	                      <input type="hidden" name="reg" value="'.base64_encode($kodeBook).'" />
		                  <input type="hidden" name="eq" value="'.base64_encode($noCnt).'" />
 			              <input type="hidden" name="fd" value="'.base64_encode($photoName).'" />
			              <input type="hidden" name="ac" value="'.base64_encode('del').'" />';
                 if($allow_update == 1) { echo '<button class="w3-button w3-round-small w3-red">Remove</button>'; }					   
			     else { echo '<button class="w3-button w3-round-small w3-red" disabled>Remove</button>'; }					   
		        echo  '</form>';
	            $i++;
              } 
	          echo '</div>';
	        }
	        echo '</div>
			      <div class="height-20"></div>';
          }
          mssql_free_result($result);
       ?>

   </div>
  </div> 
   
 </div> 
</div>

<div class="height-20"></div>
<?php
    }
  }
?>
</body>
</html>
