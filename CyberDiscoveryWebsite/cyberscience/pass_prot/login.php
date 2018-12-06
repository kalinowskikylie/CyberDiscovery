<?php
	session_start(); 
//Get configuration file	
	include('pass_config.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style type="text/css">
	table, tr, td {
		border-style:none;
	}
</style>


	<?php
	if($pageSetupIncludes == ''){
		include('pass_pagesetup.php');
	} else {
		$webPage='login';
		include($pageSetupIncludes);
	}
	if($_GET['error'] == 'email'){
		echo('<p style="color:red;">Login Failed. Invalid Email.</p>');
	} elseif($_GET['error'] == 'password'){
		echo('<p style="color:red;">Login Failed. Incorrect Password.</p>');
	} elseif($_GET['error'] == 'login'){
		echo('<p style="color:red;">You must be logged in to view this page.</p>');
	} elseif($_GET['error'] == 'perm'){
		echo('<p style="color:red;">You do not have permission to view this page.</p>');
	}
	if($_SESSION['permissions'] > 1){
		echo('<p>You are already logged in as '. $_SESSION['fName'] . ' ' . $_SESSION['lName'] .'. <a href="logout.php">Logout?</a></p>');
	} else { ?>
		<form action="process_login.php" method="post" id="loginForm">
			<table>
				<tr>
					<td><span>Email:</span></td>
					<td><input type="text" name="loginEmail" /></td>
				</tr>
				<tr>
					<td><span>Password:</span></td>
					<td><input type="password" name="loginPassword" /></td>
				</tr>
				<tr>
					<td></td>
					<td align="right"><input type="submit" value="Login" /></td>
				</tr>
			</table>
		</form>
		<br /><a href="user_registration.php">Not a user yet? Register here!</a>
	<?php } ?>
	<script type="text/javascript">
		document.getElementsByName("loginEmail")[0].focus();
	</script>
</body>
</html>