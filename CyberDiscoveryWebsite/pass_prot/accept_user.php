<?php
	session_start();
	include('pass_config.php');
	if ($_SESSION[$session_prefix .'permissions'] >= 90) {
	
		if (isset($_GET['user'])) {
		include('../txtdb/txtdb_commands.php');
		update_txtdb('../db/users.txt','UNID,'. $_GET['user'],'permissions','11');
		$usersdbobj = select_txtdb('../db/users.txt');
		$usersdb = query_txtdb($usersdbobj,'*','UNID,'. $_GET['user']);
			
		//Send response through email
			
			$to = $usersdb[0]['email'];
			$subject = $website_name . ' User Access Granted';
			$message  = $usersdb[0]['first_name'] .",\n";
			$message .= "You have been given access to ". $website_name .". \n";
			$message .= "Please visit ". $website_main ." to login. \n";
			$message .= "If you have forgotten your password, please contact ". $admin_email ." for help. \n";
			$headers = 'From: ' . $admin_email;
			
			mail($to, $subject, $message, $headers);
			
			header('location: admin.php');
		}
	} else {
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'] . '?user=' . $_GET['user'];
		header('location: login.php');
	}
?>