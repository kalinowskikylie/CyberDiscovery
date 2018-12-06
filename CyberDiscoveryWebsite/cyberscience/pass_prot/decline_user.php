<?php
	session_start();
	if ($_SESSION['permissions'] >= 90) {
		if (isset($_GET['user'])) {
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
		//Loop through each entry and store user information in appropriate variables		
			foreach($userRows as $key => $rows){
				$tempUserPieces = explode('(*)',$rows);
				$users['fName'][$key] = $tempUserPieces[0];
				$users['lName'][$key] = $tempUserPieces[1];
				$users['email'][$key] = $tempUserPieces[2];
				$users['password'][$key] = $tempUserPieces[3];
				$users['permissions'][$key] = $tempUserPieces[4];
			}
		//Find userRow of selected user by email address
			if(in_array($_GET['user'],$users['email'])){
				$chosenEntry = array_search($_GET['user'],$users['email']);
				unset($userRows[$chosenEntry]);	//Delete user data
			} else {
				echo('No such user. Consult admin page.');
				exit;
			}
		
			$writeUserDb = '';
			foreach ($userRows as $r) {
				$writeUserDb .= $r . '|!|';
			}
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $writeUserDb);
			fclose($fh);
			
		//Send response through email
			include('pass_config.php');
			$to = $_GET['user'];
			$subject = $website_name . ' User Access Denied';
			$message  = "Dear ". $users['fName'][$chosenEntry] .",\n";
			$message .= "You have been denied access to ". $website_name .". \n";
			$message .= "Please direct questions to ". $admin_email ." . \n";
			$headers = 'From: ' . $admin_email;
			
			mail($to, $subject, $message, $headers);
			
			header('location: admin.php');
		}
	} else {
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'] . '?user=' . $_GET['user'];
		header('location: login.php');
	}
?>