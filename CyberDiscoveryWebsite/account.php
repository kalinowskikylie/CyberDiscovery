<?php
	session_start();
	include('pass_prot/pass_config.php');
	if($_SESSION[$session_prefix .'permissions'] < 10){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: '. $path_to_pass_folder .'/pass_prot/login.php?error=perm');
		exit;
	} 
	include('txtdb/txtdb_commands.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>
Account | Cyber
</title>
<?php include('pagesetup.php'); ?>

<h2>Account information is currently deactivated.</h2>
<?php include('footer.php'); ?>