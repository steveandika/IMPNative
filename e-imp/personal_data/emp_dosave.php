<script language="php">	    	
  session_start();
  include("../asset/libs/db.php");	

  if((isset($_POST['empregid'])) && ($_POST['empregid'] != '')) {
    $keywrd=$_POST['empregid'];	 
    
	$query="Update m_Employee Set ";
	$query=$query."citizenID='".$_POST['noktp']."',";
	$query=$query."licenseID='".$_POST['nosim']."',";
	$query=$query."homePNumber='".$_POST['phone_home']."',";
	$query=$query."mobileCNumber='".$_POST['handphone']."',";
	$query=$query."placeOfBirth='".strtoupper($_POST['birth_place'])."',";
	$query=$query."dateOfBirth='".$_POST['birth_date']."',";
	$query=$query."DTMIn='".$_POST['datein']."',";
	$query=$query."currentFunction='".$_POST['current']."', ";
	$query=$query."homeAddress='".$_POST['homeaddr']."', ";
	$query=$query."locationID='".$_POST['location']."' ";
	$query=$query."Where empRegID='$keywrd'; ";
	
	$query=$query."If Not Exists(Select * From m_EmployeeFunctionLog Where empRegID='$keywrd' And currentFunction=".$_POST['current'].") Begin";
	$query=$query."  Insert Into m_EmployeeFunctionLog(empRegID, currentFunction, DTMLog) ";
	$query=$query."  Values('$keywrd', ".$_POST['current'].", CONVERT(VARCHAR(10), GETDATE(), 126));";
	$query=$query."End;"; 
	
	$query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
	$query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(10), GETDATE(), 126),CONCAT('Update Employee Detail ','".$keywrd."')); ";
	
	$result=mssql_query($query);
	echo '<script>swal("Success","Record has been updated.");</script>'; 
	echo '<script>$("#result").load("emp_list.php?id=+'.$keywrd.'");</script>'; }  
	
  else {
    list($nama_depan, $nama_belakang) = split(" ", strtoupper($_POST['empname']));
	$keywrd=substr($nama_depan,0,1);
	if(strlen($nama_belakang) > 2) { $keywrd=$keywrd.substr($nama_belakang,0,2); }
	else { $keywrd=substr($nama_depan,0,3); }
	
	$todayIs=date("Y-m-d");
	$keywrd=$keywrd.substr($todayIs,0,1).substr($todayIs,2,2);

	$query="Declare @KeyField VarChar(11), @LastKey Int; ";
	$query=$query."If Not Exists(Select * From logKeyField Where keyFName Like CONCAT('$keywrd', '%')) Begin ";
	$query=$query."  Set @KeyField=CONCAT('$keywrd', '1'); ";
	$query=$query."  Insert Into logKeyField(keyFName, lastNumber) Values('$keywrd', 1); ";
	$query=$query."End Else Begin ";
	$query=$query."      Select @LastKey=lastNumber +1 From logKeyField Where keyFName Like CONCAT('$keywrd', '%'); ";
	$query=$query."      Update logKeyField Set lastNumber=lastNumber +1 Where keyFName Like CONCAT('$keywrd', '%'); ";
/*    $query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 1 Set @KeyField=CONCAT('$keywrd','000000',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 2 Set @KeyField=CONCAT('$keywrd','00000',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 3 Set @KeyField=CONCAT('$keywrd','0000',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 4 Set @KeyField=CONCAT('$keywrd','000',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 5 Set @KeyField=CONCAT('$keywrd','00',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) = 6 Set @KeyField=CONCAT('$keywrd','0',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."      If LEN(LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))) >= 7 Set @KeyField=CONCAT('$keywrd',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";*/
	$query=$query."      Set @KeyField=CONCAT('$keywrd',LTRIM(RTRIM(CONVERT(VARCHAR(3),@LastKey)))); ";
	$query=$query."    End; ";
	
	$query=$query."Insert Into m_Employee(empRegID, completeName, citizenID, licenseID, homePNumber, mobileCNumber, placeOfBirth, dateOfBirth, DTMIn, initialFunction, ";
	$query=$query."currentFunction, isResign, homeAddress, locationID) ";
	$query=$query."Values(@KeyField, '".strtoupper($_POST['empname'])."', '".$_POST['noktp']."', '".$_POST['nosim']."', ";
	$query=$query."'".$_POST['phone_home']."', '".$_POST['handphone']."', '".strtoupper($_POST['birth_place'])."', ";
	$query=$query."'".$_POST['birth_date']."', '".$_POST['datein']."', ".$_POST['initial'].", ".$_POST['initial'].", 0, ";
	$query=$query."'".strtoupper($_POST['homeaddr'])."','".$_POST['location']."'); ";
	
	$query=$query."Insert Into m_EmployeeFunctionLog(empRegID, currentFunction, DTMLog) ";
	$query=$query."Values(@KeyField, ".$_POST['initial'].", CONVERT(VARCHAR(10), GETDATE(), 126));";
    $result=mssql_query($query); 
  
	echo '<script>swal("Success","Your entry has been saved.")</script>'; 
	if($_POST["whatToDo"] == 'save_AddNew') { echo '<script>$("#result").load("emp_reg.php");</script>'; }
	else { echo '<script>$("#result").load("emp_list.php");</script>'; }	  	
  }
</script>