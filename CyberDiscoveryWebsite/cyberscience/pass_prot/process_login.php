<?php
//Get configuration file	
	include('pass_config.php');
	
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

//check if submitted email and password are a matching pair. Login if so, reject if not	
	if(in_array($_POST['loginEmail'],$users['email'])){
		$chosenEntry = array_search(cleanString($_POST['loginEmail']),$users['email']);
		if(md5($_POST['loginPassword']) == $users['password'][$chosenEntry]){	//If email and password correct, craete session variables
			session_start();
			$_SESSION['fName'] = $users['fName'][$chosenEntry];
			$_SESSION['lName'] = $users['lName'][$chosenEntry];
			$_SESSION['email'] = $users['email'][$chosenEntry];
			$_SESSION['permissions'] = $users['permissions'][$chosenEntry];
			if(isset($_SESSION['referingPage'])){
				$referingPage = $_SESSION['referingPage'];
				unset($_SESSION['referingPage']);
			} else {
				$referingPage = $website_main;
			}
			header('Location: '. $referingPage);	//Send user to page that caused them to login
			echo('<span>If you see this text, an error has occured. Please try visiting the website again. If the problem persists. Contact the webmaster.</span>');
			exit;
		} else {
			header('Location: login.php?error=password');
			exit;
		}
	} else {
		header('Location: login.php?error=email');
		exit;
	}

?>