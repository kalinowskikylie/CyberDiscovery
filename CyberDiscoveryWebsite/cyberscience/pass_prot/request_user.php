<?php
	session_start();
	
	require_once('recaptchalib.php');
$privatekey = "6LcUE8USAAAAAEzApOn97Llwi9_fo4bKpBdi2amN";
$resp = recaptcha_check_answer ($privatekey,
                              $_SERVER["REMOTE_ADDR"],
                              $_POST["recaptcha_challenge_field"],
                              $_POST["recaptcha_response_field"]);
if (!$resp->is_valid) {
  // What happens when the CAPTCHA was entered incorrectly
  die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
       "(reCAPTCHA said: " . $resp->error . ")");
} else {
  // Your code here to handle a successful verification
	
	
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
		$users['email'][$key] = $tempUserPieces[2];
	}
//Find userRow of selected user by email address
	if(in_array(cleanString($_POST['email']),$users['email'])){
		header('location: user_registration.php?error=email');
	} else {
	
		include('pass_config.php');

		//creat permissions 1 account
		$newuser = "\n" . cleanString($_POST['fName']) .'(*)'. cleanString($_POST['lName']) .'(*)'. cleanString($_POST['email']) .'(*)'. md5($_POST['password']) .'(*)1|!|';
		$myFile = "users_db.txt";
		$fh = fopen($myFile, 'a');	
		fwrite($fh, $newuser);
		fclose($fh);
		
	//Send request through email
		$to = $admin_email;
		$subject = $website_name . ' User Access Request: ' . cleanString($_POST['email']);
		$message  = "Admin,\n";
		$message .= $_POST['fName'] ." ". $_POST['lName'] ." is requesting access to ". $website_name .". \n";
		$message .= "Grant him/her access?\n";
		$message .= "----Approve----\n";
		$message .= $path_to_pass_folder ."/pass_prot/accept_user.php?user=". $_POST['email'] ."\n";
		$message .= "----Decline----\n";
		$message .= $path_to_pass_folder ."/pass_prot/decline_user.php?user=". $_POST['email'];
		$headers = 'From: ' . $_POST['email'];
		
		mail($to, $subject, $message, $headers);
		
		echo('<h1>Request Sent.</h1><p>Please be patient. You will be notified via the supplied email address when your account is ready.</p>');
	}
}
?>