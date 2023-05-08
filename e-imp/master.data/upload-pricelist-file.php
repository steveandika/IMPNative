<?php session_start(); ?>

<div class="height-20"></div>
<div id="info"style="font-family:Cairo,sans-serif;font-size:.830rem"></div>

<script language="php">  
  include("../asset/libs/db.php");
  include("../asset/libs/upload_reader.php");
  
  if(isset($_POST['namaFile'])) 
  {
	$namaPriceCode=strtoupper($_POST['namaFile']);
    $target = basename($_FILES['fileUser']['name']);	
	$curr=strtoupper($_POST['curr']);
	
    move_uploaded_file($_FILES['fileUser']['tmp_name'], $target);	  
	$data = new Spreadsheet_Excel_Reader($target, false);
    // menghitung jumlah baris file xls
    $baris = $data->rowcount($sheet_index=0);

    // nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
    // import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
	
	$query="If Not Exists(Select * From m_RepairPriceList_Header Where priceCode Like '$namaPriceCode') Begin 
	         Insert Into m_RepairPriceList_Header(priceCode, Description, fromStreamFile, Currency) Values('".$namaPriceCode."','',1, '$curr');
			End Else Begin
			      Update m_RepairPriceList_Header Set Currency='$curr' Where priceCode Like '$namaPriceCode';
			    End;"; 
	$result=mssql_query($query);

    $del="Delete From m_RepairPriceList Where priceCode='$namaPriceCode'";	
	//$res=mssql_query($del);
	
	$qry = "";	
	$curDate = date("Y-m-d");
	$uid=$_SESSION["uid"];
	
    for($i=1; $i<=$baris; $i++) {
      //  menghitung jumlah real data. Karena kita mulai pada baris ke-2, maka jumlah baris yang sebenarnya adalah 
      //  jumlah baris data dikurangi 1. Demikian juga untuk awal dari pengulangan yaitu i juga dikurangi 1
      $barisreal = $baris-1;
      $k = $i-1;

      // menghitung persentase progress
      $percent = intval($k/$barisreal * 100)."%";

      // mengupdate progress
      /*echo '<script language="javascript">
            document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.'; background-color:lightblue;height:2px;\">&nbsp;</div>";
            document.getElementById("info").innerHTML="on progress '.$k.' data successfully inserted  ('.$percent.' complete).";
            </script>';*/
      /* echo '<script language="javascript">
            document.getElementById("info").innerHTML="on progress '.$k.' data successfully inserted  ('.$percent.' complete).";
            </script>';*/
      echo '<script language="javascript">
            document.getElementById("info").innerHTML="Reading .. '.$percent.'. Rows: '.$baris.'.";
            </script>';			
      // membaca data (kolom ke-1 sd terakhir)
      $field1 = $data->val($i, 1);
      $field2 = $data->val($i, 2);  
      $field3 = $data->val($i, 3);
      $field4 = $data->val($i, 4);	
      $field5 = $data->val($i, 5);
      $field6 = $data->val($i, 6);	
      $field7 = $data->val($i, 7);
      $field8 = $data->val($i, 8);	
      $field9 = $data->val($i, 9);
      $field10 = $data->val($i, 10);	
      $field11 = $data->val($i, 11);
      $field12 = $data->val($i, 12);  
      $field13 = $data->val($i, 13);
	  
	  $field1=str_replace(' ','',$field1);
	  
	  if((trim($field7) == '') || (is_null($field7)) || (strlen($field7) == 0)) { $field7 = 0; }
	  if((trim($field8) == '') || (is_null($field8)) || (strlen($field8) == 0)) { $field8 = 0; }
	  if((trim($field9) == '') || (is_null($field9)) || (strlen($field9) == 0)){ $field9 = 0; }
	  if((trim($field11) == '') || (is_null($field11)) || (strlen($field11) == 0)) { $field11 = 0; }
	  if((trim($field12) == '') || (is_null($field12)) || (strlen($field12) == 0)) { $field12 = 0; }
	  
	  
	  
	  $keterangan=str_ireplace("'","", $field13);
	  
	  if($field1 != "" && strtoupper($field1)!="TYPE") { 	       
	    $isMulti=0;
	    $sizeCode=0;
	    if($field10 == 'YES') { $isMulti=1; }
       
	    if((trim($field2) != '') && (trim($field3) != '')) {
	      $field2=trim($field2);
	      $field3=trim($field3);	  	  
	      $query="Select * From m_ISOCode Where Size='$field2' And Tipe='$field3'";
	      $result=mssql_query($query);
	      if(mssql_num_rows($result) > 0) { 
	        $arr=mssql_fetch_array($result);
	  	    $sizeCode=$arr[0]; 
	  	  }
	      else {$sizeCode=0;}  
		  mssql_free_result($result);
        }
	    else {$sizeCode=0;}
        if ($uid == "root") { echo $field12." ".$keterangan."<br>"; }
	    $qry="Insert Into m_RepairPriceList(priceCode, isType, unitSize, unitHeight, LocDamage, PartDamage, 
	                                        Act, cLength, cWidth, cQty, isMulti, MH, materialValue, Description, IDISO, 
											last_update,update_by) 
	                                 Values('".$namaPriceCode."','".$field1."','".$field2."','".$field3."',
	                                        '".$field4."','".$field5."','".$field6."',".$field7.","
	                                          .$field8.",".$field9.",".$isMulti.",".$field11.",".$field12.",'"
										      .$keterangan."',".$sizeCode.",'".$curDate."','".$uid."');  ";	    
        $result=mssql_query($qry); 
		if(!$result) { echo $qry; }
      }	
	  else { if (strtoupper($_SESSION["uid"])=="ROOT") { echo "skip  ke ".$i."<br>"; } }
	}	
	mssql_close($dbSQL);
	unlink($target);
	
	$url="/e-imp/master.data/?do=upload-log";
	//echo "<script type='text/javascript'>location.replace('$url');</script>"; 
  }
</script>