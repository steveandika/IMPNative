<script language="php">
  session_start();
  
  include("../asset/libs/db.php");  
  $kywrd = $_POST["noCnt"];
  $kodeBooking = $_POST["bookID"];
  $loc = $_POST["loc"];
  
  $do = "Delete From containerJournal Where NoContainer='$kywrd' And bookInID='$kodeBooking';
         Insert Into userLogAct(userID, dateLog, DescriptionLog) 
	                     Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Delete Container From Hamparan ','$kywrd',' ','$kodeBooking')); ";
  $res = mssql_query($do);
  if($res) 
  { 
    echo '<h3 style="padding:5px 0 5px 0;background:#2196F3;color:#fff;margin-top:0!important">&nbsp;&nbsp;Overview : '.$kywrd.'</h3>
           <div class="w3-container w3-animate-zoom" style="font-size:.840rem!important;padding:10px;background:#f7f9f9;overflow-y:scroll;max-height:300px;">    
           <div class="height-10"></div>
	  	    <p style="letter-spacing:1px;color:red;font-weight:bold;font-size:13px">DATA BERHASIL DIHAPUS DARI DATABASE.</p>
		   </div>
		   <div class="height-10"></div>';	   
  }
  mssql_close($dbSQL);
</script>