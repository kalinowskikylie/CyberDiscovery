<?php
session_start();
    if(!($_SESSION['permissions'] >= 70)){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: pass_prot/login.php?error=perm');
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="cyber.css" />
		<title>Cyber Curriculum</title>
	</head>
	
	<body>
    	<div class="header">
        	<div style="float:right;"><a href="admin.php">Admin Login</a></div>
        	<div class="titler">Cyber Curriculum</div>
            <div style="width:5px;float:left;"></div><div class="nav" style="background-color:#CCC;"><a href="index.php">View Files</a></div> <div class="nav"><a href="upload.php">Upload Files</a></div>
            <div style="clear:both;"></div>
        </div>
        
		<?php 
			 $initials = $_POST["initials"];
			 $target = "uploads/"; 
			 $uploadedfile = basename( $_FILES['uploaded']['name']);
			 $revisionCheckerCount = 0;
			 if ($handle = opendir($target)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$fileNameBreak = explode('-',$file);
						$fileExtBreak = explode('.',$file);
						$fileName = $fileNameBreak[0] . '.' . $fileExtBreak[1];
						if($uploadedfile == $fileName){
							$revisionCheckerCount ++;
						}
					}
				}
				closedir($handle);
			}
			 
			 $target = $target . basename( $_FILES['uploaded']['name']);
			 $targetbreak = explode('.', $target);
			 $target = $targetbreak[0] . '-' . strtoupper($initials) . '**' . $revisionCheckerCount . '.' . $targetbreak[1];
			 
			 
			 echo($target);
			 $ok=1; 
		
			 //This is our size condition 
			 if ($uploaded_size > 1000000000) 
			 { 
			 echo "Your file is too large. Limit 1.gb. Please contact administrator to upload larger files.<br>"; 
			 $ok=0; 
			 } 
			 
			 //This is our limit file type condition 
			 if ($uploaded_type =="text/php") 
			 { 
			 echo "No PHP files<br>"; 
			 $ok=0; 
			 } 
			 
			 //Here we check that $ok was not set to 0 by an error 
			 if ($ok==0) 
			 { 
			 Echo "Your file was not uploaded."; 
			 } 
			 
			 //If everything is ok we try to upload it 
			 else 
			 { 
			 if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) 
			 { 
			 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded"; 
			 } 
			 else 
			 { 
			 echo "There was a problem uploading your file."; 
			 } 
			 }
		?> 

    </body>
</html>
			