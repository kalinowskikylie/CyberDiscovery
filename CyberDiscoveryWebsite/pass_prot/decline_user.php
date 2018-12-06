<?php
	session_start();
	include('pass_config.php');
	if ($_SESSION[$session_prefix .'permissions'] >= 90) {
			
		if (isset($_GET['user'])) {
		include('../txtdb/txtdb_commands.php');
		
		delete_txtdb('../db/users.txt','UNID,' . $_GET['user']);
		$usersdbobj = select_txtdb('../db/users.txt');
		$usersdb = query_txtdb($usersdbobj,'*','UNID,'. $_GET['user']);	
		//Send response through email
			$to = $usersdb[0]['email'];
			$subject = $website_name . ' User Access Denied';
			$message  = "Dear ". $usersdb[0]['first_name'] .",\n";
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