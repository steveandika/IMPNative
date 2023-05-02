<!DOCTYPE html>
<html>
<head>  
  <meta charset="utf-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <meta name="author" content="Edmund" />
  <link rel="stylesheet" type="text/css" href="../asset/css/master.css" />
  <script src="../asset/js/modernizr.custom.js"></script>       
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>     
  <script type="text/javascript">
    function opensub_modul(privatevar) {$("#mod_content").load(privatevar);}
  </script>  
</head>

<body> 
<script language="php">
  $unit = $_GET['unit'];
  $dtmIn = $_GET['dtmin'];
  $wrkid = $_GET['wrkid'];
  $transid = $_GET['transid'];
  

  
  $contProfile = 'contprofile.php?unit='.$unit.'&dtmin='.$dtmIn.'&wrkid='.$wrkid.'&transid='.$transid;
  $contOnSurvey = 'site_survey.php?unit='.$unit.'&wrkid='.$wrkid.'&transid='.$transid;
  $contMNR = 'hw_mnr.php?unit='.$unit.'&dtmin='.$dtmIn.'&wrkid='.$wrkid.'&transid='.$transid;
  
  echo '
         <h2 style="padding:10px 0 9px 0;border-bottom:1px solid #ccc;">Manage Container Detail - '.$unit.'</h2>
		 <div class="height-10"></div>
		 
		 <div class="w3-padding-row">
		   <div class="w3-third">
             <label class="w3-text-dark-grey"><strong>Categories</strong><br></label>
	  	     <table class="w3-table">
		       <tr style="border-bottom:1px dotted #ddd">
                <td><a onclick=opensub_modul("'.$contProfile.'") style="cursor:pointer;text-decoration:none" class="w3-text-blue">Container Profile</a>
					   &nbsp;&nbsp;<i class="fa fa-chevron-circle-down"></i></td>		   
		       </tr>
		       <tr style="border-bottom:1px dotted #ddd">
			     <td><a onclick=opensub_modul("'.$contOnSurvey.'") style="cursor:pointer;text-decoration:none" class="w3-text-blue">Survey</a>
					   &nbsp;&nbsp;<i class="fa fa-chevron-circle-down"></i></td>
			   </tr>
		       <tr style="border-bottom:1px dotted #ddd">
			     <td><a onclick=opensub_modul("'.$contMNR.'") style="cursor:pointer;text-decoration:none" class="w3-text-blue">Cleaning And Repair</a>
					   &nbsp;&nbsp;<i class="fa fa-chevron-circle-down"></i></td>
			   </tr>

		      </table>		     
			  <div id="pre_content"></div>
		   </div>
		   
		   <div class="w3-twothird"><div id="mod_content"></div></div>
		   
		 </div>';

    if(isset($_GET['page'])) { include($_GET['page'].".php"); }
		
    echo '<div class="height-20"></div>';	
    
</script>
</body>
</html>