<?php
	session_start();
	if ($_SESSION['permissions'] >= 90) {
		
		function cleanString($string) {  
			$detagged = strip_tags($string);  
			if(get_magic_quotes_gpc()) {  
				$stripped = stripslashes($detagged);  
				$escaped = htmlspecialchars($stripped, ENT_QUOTES);  
			} else {  
				$escaped = htmlspecialchars($detagged, ENT_QUOTES);  
			}  
			return $escaped;  
		} 
		
		if (isset($_POST['newemail'])) {
			$newuser = "\n" . cleanString($_POST['newfName']) .'(*)'. cleanString($_POST['newlName']) .'(*)'. cleanString($_POST['newemail']) .'(*)'. md5($_POST['newpassword']) .'(*)'. cleanString($_POST['newpermissions']). '|!|';
			$myFile = "users_db.txt";
			$fh = fopen($myFile, 'a');	
			fwrite($fh, $newuser);
			fclose($fh);
		}
		
		if (isset($_POST['editemail'])) {
		//Open user database and read contents out of file
			$myFile = "users_db.txt";	//This is location of database file
			$fh = fopen($myFile, 'r');
			$userDb = fread($fh, filesize($myFile));
			fclose($fh);
			
		//Seperate file into seperate entries. Delete last entry which should always be null
			$userRows = explode('|!|',$userDb);
			if($userRows[count($userRows)-1] == ''){
				unset($userRows[count($userRows)-1]);
			}

			if(!($_POST['editpassword'] == 'HIDDEN')){
				$hashPassword = md5($_POST['editpassword']);
			} else {
				$hashPassword = $_POST['edithpassword'];
			}
			if($_POST['editUser'] == 0) {
				$userRows[cleanString($_POST['editUser'])] = cleanString($_POST['editfName']) .'(*)'. cleanString($_POST['editlName']) .'(*)'. cleanString($_POST['editemail']) .'(*)'. $hashPassword .'(*)'. cleanString($_POST['editpermissions']);	//Update user data
			} else {
				$userRows[cleanString($_POST['editUser'])] = "\n" . cleanString($_POST['editfName']) .'(*)'. cleanString($_POST['editlName']) .'(*)'. cleanString($_POST['editemail']) .'(*)'. $hashPassword .'(*)'. cleanString($_POST['editpermissions']);	//Update user data
			}
			
			$writeUserDb = '';
			foreach ($userRows as $r) {
				$writeUserDb .= $r . '|!|';
			}
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $writeUserDb);
			fclose($fh);
		}
		
		if (isset($_POST['deleteUser'])) {
		//Open user database and read contents out of file
			$myFile = "users_db.txt";	//This is location of database file
			$fh = fopen($myFile, 'r');
			$userDb = fread($fh, filesize($myFile));
			fclose($fh);
			
		//Seperate file into seperate entries. Delete last entry which should always be null
			$userRows = explode('|!|',$userDb);
			if($userRows[count($userRows)-1] == ''){
				unset($userRows[count($userRows)-1]);
			}	
			unset($userRows[$_POST['deleteUser']]);	//Delete user data
			
			$writeUserDb = '';
			foreach ($userRows as $r) {
				$writeUserDb .= $r . '|!|';
			}
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $writeUserDb);
			fclose($fh);
		}
	}
	include('pass_config.php');
	header('location: '. $website_main);
?>