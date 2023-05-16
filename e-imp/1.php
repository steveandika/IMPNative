<script language="php">
  session_start();
  $defHTML = $_SESSION['defurl'];
</script>

<!DOCTYPE html>
<html>
	<head>  
		<meta charset="utf-8"> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
		<title>IMP | Integrated Container System</title>
		<link rel="shortcut icon" href="asset/img/office.png" type="image/x-icon" />    
		<link rel="stylesheet" type="text/css" href="asset/css/master.css" />  
		<script src="asset/js/modernizr.custom.js"></script> 	
		<script src="asset/js/jquery.min.2.1.1.js"></script>    
		<script type='text/javascript'>
			var file;
			function prepareUpload()
			{
				document.getElementById('fileSize').innerHTML = '';
				document.getElementById('bytesUploaded').innerHTML = '';
				document.getElementById('percentUploaded').innerHTML = '';
				document.getElementById('uploadProgressBar').style.width = '0%';
  
				// get file name
				file = document.getElementById('file').value;
				if(file.lastIndexOf('\\')>=0)
				file = file.substr(file.lastIndexOf('\\')+1);
				document.getElementById('fileName').innerHTML = file;
                
				// get folder path
				var curFolder = window.location.href;
				if(curFolder[curFolder.length-1]!='/')
				curFolder = curFolder.substring(0, curFolder.lastIndexOf('/')+1)+'log/';
  
				document.getElementById('target').innerHTML = curFolder;
				document.getElementById('frm').action = curFolder;
			}
        
			var timerId;
			function formSubmit()
			{
				timerId = setInterval('updateProgress()', 1000);
				document.getElementById('cancelUploadBtn').disabled = false;
			}
        
			function updateProgress()
			{
				var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
				var uploadTarget = document.getElementById("frm").action + file;
            
				request.open("REPORT", uploadTarget, false);
				request.send("<upload-progress xmlns='ithit'/>");
				var resp = request.responseText;
            
				// Extract number of bytes uploaded and total content length of the file.
				// Usually you will use XML DOM or regular expressions for this purposes
				// but here for the sake of simplicity we will just extract using string methods.
				var size;
				var sizeIndex = resp.indexOf("total-content-length");
				if(sizeIndex != -1)
				{
					size = resp.substring(resp.indexOf(">", sizeIndex)+1, resp.indexOf("</", sizeIndex));
					document.getElementById("fileSize").innerHTML = size;
				}
      
				var bytes = "Finished";
				var percent = 100;
				var bytesIndex = resp.indexOf("bytes-uploaded");
				if(bytesIndex != -1)
				{
					bytes = resp.substring(resp.indexOf(">", bytesIndex)+1, resp.indexOf("</", bytesIndex));
					if(parseInt(size)!=0)
					percent = 100*parseInt(bytes)/parseInt(size);
				}
  
				document.getElementById("bytesUploaded").innerHTML = bytes;
				document.getElementById("percentUploaded").innerHTML = percent.toString().substr(0, 4) + " %";
				document.getElementById("uploadProgressBar").style.width = percent.toString() + "%";
				
				if(percent==100)
				{
					clearInterval(timerId);
					document.getElementById("cancelUploadBtn").disabled = true;
				}
			}
        
			function cancelUpload()
			{
				// recreate iframe to cancel upload
				document.getElementById("uploadFrameHolder").innerHTML = "<iframe name='uploadFrame' ></iframe>";
				clearInterval(timerId);
				document.getElementById("cancelUploadBtn").disabled = true;
			}
    
			function validateForm() 
			{
				function hasExtension(inputID, exts) 
				{
					var fileName = document.getElementById("file").value;		
					return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
				}
       
				if(!hasExtension('fileUser', ['.xls'])) 
				{
					alert("Only Microsoft Excel 97-2003 (XLS) files are permitted.");
					return false;
				}
			}	
		</script>    
	</head>

	<body>
		<div class="w3-container"

		<?php
			if(!isset($_SESSION["uid"])) 
			{
				$url="../"; 
				echo "<script type='text/javascript'>location.replace('$url');</script>"; 
			} 
			else 
			{ 
				include_once ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/common.php"); 	
				openDB();
				include ($_SERVER["DOCUMENT_ROOT"]."/e-imp/asset/libs/dashboard.php");
				
				echo '<div class="wrapper">';	  
				if(isset($_GET["src"])) 
				{
					$sourcepage = base64_decode($_GET["src"]);  				
					include($sourcepage);
				}	  
				echo "</div>";
			}		
		?>
		
		</div>

		<script>
			var dropdown = document.getElementsByClassName("dropdown-btn");
			var i;

			for (i = 0; i < dropdown.length; i++) 
			{
				dropdown[i].addEventListener("click", function() 
				{
					this.classList.toggle("active");
					var dropdownContent = this.nextElementSibling;
					if (dropdownContent.style.display === "block") 
					{
						dropdownContent.style.display = "none";
					} 
					else 
					{
						dropdownContent.style.display = "block";
					}
				});
			}
	
			function toggleSideNav() 
			{		
				if (document.getElementById("sideBar").style.left == "0px") 
				{
					document.getElementById("sideBar").style.left = "-250px";	
				} 
				else 
				{
					document.getElementById("sideBar").style.left = "0px";			
				}		  
			}
			
			function dateSeparator(varID) 
			{
				var str = document.getElementById(varID).value;
				panjang = str.length;
		
				if (panjang==8) 
				{
					var partYear = str.slice(0,4);
					var partMonth = str.slice(4,6); 
					var partDate = str.slice(6,8);
	  
					result = partYear.concat('-', partMonth, '-', partDate);
					document.getElementById(varID).value = result;
				} 		 
			}

			function addRow_mine(tableID) 
			{
				var table = document.getElementById(tableID);
				var rowCount = table.rows.length;
				if(rowCount < 100)
				{							// limit the user from creating fields more than your limits
					var row = table.insertRow(rowCount);
					var colCount = table.rows[0].cells.length;
					for(var i=0; i<colCount; i++) 
					{
						var newcell = row.insertCell(i);
						var insrow = table.rows[rowCount-1];		  
						newcell.innerHTML = insrow.cells[i].innerHTML;
					}
				}
				else 
				{
					alert("Maximum record per EOR is 100.");			   
				}
			}

			function deleteRow_mine(tableID) 
			{
				var table = document.getElementById(tableID);
				var rowCount = table.rows.length;

				//hitung jumlah baris yang ditandai untuk hapus	
				for(var i=1; i<rowCount; i++) 
				{
					var flag = 0;
					var row = table.rows[i];
					var chkbox = row.cells[0]; 	  
					if(chkbox != null) { flag++; }	 
				}	

				//proses hapus	
				var terhapus = 0;
				var baris = 1;
				var stop = 0;
  
				while(terhapus < flag) 
				{	
					rowCount = table.rows.length;
					for(var j=1; j<rowCount; j++) 
					{  
						row = table.rows[j];
						//alert(row.cells[0]);
						chkbox = row.cells[0];
						if(chkbox != null) 
						{		  
							table.deleteRow(j);
							j = rowCount +1;
							terhapus++;
						}   
					}
				}	
			}  
  
			function isNumber(evt) 
			{
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;

				if(charCode == 46) 
				{
					return true;
				} 
				else 
				{	  
					if(charCode > 31 && (charCode < 48 || charCode > 57)) 
					{
						return false;
					}
				}	      
				return true;
			}      
		</script>  
	</body>
</html>