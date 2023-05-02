<script language="php">
  session_start();
  include ("../asset/libs/db.php");
  
  $keywrd = $_POST['userid'];
  
  $query="Delete From userMenuProfile Where userID='$keywrd'";
  $result=mssql_query($query);
  
  $employeeDetail = 0;
  $user = 0;
  $query="";
  
  if($_POST['employee_detail'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 11); ";  }
  if($_POST['user'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 12); ";  }
  //if($_POST['surveyor'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 13); ";  }
  if($_POST['group_repair'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 14); ";  }
  
  if($_POST['customer'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 15); ";  }
  if($_POST['port'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 16); ";  }
  if($_POST['vessel'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 17); ";  }
  if($_POST['location'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 18); ";  }
  if($_POST['price_list'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 19); ";  }
  
  if($_POST['gate_in'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 30); ";  }
  if($_POST['container_header'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 31); ";  }
  if($_POST['eor'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 32); ";  }
  if($_POST['container_photo'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 33); ";  }
  if($_POST['gate_out'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 34); ";  }
  if($_POST['cleaning'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 35); ";  }
  if($_POST['eorapproval'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 36); ";  }
  if($_POST['finishrep'] == "on") { $query=$query."Insert Into userMenuProfile Values('$keywrd', 37); ";  }
  
  $query=$query."Insert Into userLogAct(userID, dateLog, DescriptionLog) ";
  $query=$query."Values('".$_SESSION['uid']."', CONVERT(VARCHAR(20), GETDATE(), 120),CONCAT('Update Access: ','$keywrd')); ";	    

  $result=mssql_query($query);

  echo '<script>swal("Success","Your entry has been saved.");</script>'; 
  echo '<script>$("#result").load("users_list.php?id=+'.$keywrd.'");</script>'; 
</script>