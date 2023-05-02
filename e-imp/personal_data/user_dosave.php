<script language="php">
  session_start();
  include ("../asset/libs/db.php");
 
  if(isset($_POST['id']) && Trim($_POST['id']) != '') 
  {
    $keywrd=$_POST['id'];
	if($_POST['isAktif'] == "on") { $isaktif=1; }
	else { $isaktif=0; }
	if($_POST['alInsert'] == "on") { $alinsert=1; }
	else { $alinsert=0; }
	if($_POST['alDelete'] == "on") { $aldelete=1; }
	else { $aldelete=0; }
	if($_POST['alUpdate'] == "on") { $alupdate=1; }
	else { $alupdate=0; }
	
	$locationid='ALL';
    $query="Select locationID From m_Employee Where empRegID='$keywrd' And locationID Is Not Null;";
    $result=mssql_query($query);
    while($arr=mssql_fetch_array($result)) { $locationid=$arr['locationID']; }
    mssql_free_result($result);
	
	$query="Update userProfile Set isActive=".$isaktif;
	$query=$query.", alInsert=".$alinsert.", alDelete=".$aldelete.", alEdit=".$alupdate;
	$query=$query.",locationID='$locationid' Where userID='$keywrd'; ";
	
	$query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	$query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Update User Role: ','".$keywrd."')); ";
	$result=mssql_query($query);
	echo '<script>swal("Success","Record has been updated.");</script>'; 
	echo '<script>$("#result").load("users_list.php?id=+'.$keywrd.'");</script>'; 
  }    
  else 
  {
	if($_POST['isAktif'] == "on") { $isaktif=1; }
	else { $isaktif=0; }
	if($_POST['alInsert'] == "on") { $alinsert=1; }
	else { $alinsert=0; }
	if($_POST['alDelete'] == "on") { $aldelete=1; }
	else { $aldelete=0; }
	if($_POST['alUpdate'] == "on") { $alupdate=1; }
	else { $alupdate=0; }
	
	$query="Insert Into userProfile(userID, accessKey, isActive, isLogin, alInsert, alDelete, alEdit, locationID) ";
	$query=$query."Values('".$_POST['employee']."', '".$_POST['employee']."', ".$isaktif.", 0, ".$alinsert.", ".$aldelete.", ".$alupdate.", '".$_POST['location']."')";

	$result=mssql_query($query);
    echo '<script>swal("Success","Your entry has been saved.");</script>'; 
	if($_POST["whatToDo"] == 'save_AddNew') 
	{ 
      $url = "?show=cu_a";
	  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
      /*echo '<script>$("#result").load("user-reg.php");</script>'; */
    }
	else 
	{ 
      $url = "?show=ru";
	  echo "<script type='text/javascript'>location.replace('$url');</script>"; 
	/*  echo '<script>$("#result").load("users_list.php");</script>'; */
	}		
  }
</script>