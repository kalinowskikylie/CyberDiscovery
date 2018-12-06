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
	include('../txtdb/txtdb_commands.php');
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

//ADD A LVL 1 USER ; dont forget to include the txtdb library
	$usersdbobj = select_txtdb('../db/users.txt');
	$usersdb = query_txtdb($usersdbobj,'*','email,'. $_POST['email']);
	if(!($usersdb)){
		$unid = md5($_POST['email'].time());
		$password = md5($_POST['password']);
		insert_txtdb('../db/users.txt','UNID,first_name,last_name,email,password,permissions',$unid . ',' . clean_txtdb($_POST['fName']) . ',' . clean_txtdb($_POST['lName']) . ',' . clean_txtdb($_POST['email']) . ',' . $password .','. '1');
	} else {
		header('Location: admin.php?error=email');
		exit;
	}

	//Send request through email
		$to = $admin_email;
		$subject = $website_name . ' User Access Request: ' . cleanString($_POST['email']);
		$message  = "Admin,\n";
		$message .= $_POST['fName'] ." ". $_POST['lName'] ." is requesting access to ". $website_name .". \n";
		$message .= "Reason: ". cleanString($_POST['reason']) ."\n";
		$message .= "Grant him/her access?\n";
		$message .= "----Approve----\n";
		$message .= $domain_address . $path_to_pass_folder ."/pass_prot/accept_user.php?user=". $unid ."\n";
		$message .= "----Decline----\n";
		$message .= $domain_address . $path_to_pass_folder ."/pass_prot/decline_user.php?user=". $unid;
		$headers = 'From: ' . $_POST['email'];
		
		mail($to, $subject, $message, $headers);
		
		echo('<h1>Request Sent.</h1><p>Please be patient. You will be notified via the supplied email address when your account is ready.</p>');
}
?>