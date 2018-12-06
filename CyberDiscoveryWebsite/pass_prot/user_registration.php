<?php
//Get configuration file	
	include('pass_config.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<?php
		if($pageSetupIncludes == ''){
			include('pass_pagesetup.php');
		} else {
			$webPage = 'register';
			include($pageSetupIncludes);
		}
	?>
		<h2>User Registration</h2>
		<p>Please provide the information below to request access to this site. The process requires approval so it may take a few days. Please be patient. Thank you!</p>
		<form action="request_user.php" method="post" onSubmit="return validate()">
			<table>
				<tr>
					<td>First Name:</td>
					<td><input type="text" name="fName" id="fName" /></td>
				</tr>
				<tr>
					<td>Last Name:</td>
					<td><input type="text" name="lName" id="lName" /></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="email" id="email" /></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="password" id="password" /></td>
				</tr>
				<tr>
					<td>Retype Password:</td>
					<td><input type="password" name="repassword" id="repassword" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea style="width:100%;" name="reason" id="reason">Reason for requesting access</textarea></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
				          require_once('recaptchalib.php');
				          $publickey = "6LcUE8USAAAAAGaEW90zToN2Wa-XvykNDCf41K23"; // you got this from the signup page
				          echo recaptcha_get_html($publickey);
        				?></td>
				</tr>
				<tr>
					<td></td>
					<td><input style="float:right;" type="submit" value="Request Access" /></td>
				</tr>
			</table>
		</form>
		<span style="color:red;" id="errorHolder"><?php if($_GET['error'] == 'email'){ ?><script type="text/javascript">$('#errorHolder').html('<span>That email is already in use. Please try again.</span>').children('span').delay(2000).fadeOut(500);</script><?php } ?></span>
		<script type="text/javascript">
			$('#fName').focus();
			function validate(){
				var fName = $('#fName').val();
				var lName = $('#lName').val();
				var email = $('#email').val();
				var pass = $('#password').val();
				var repass = $('#repassword').val();
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //email regex
			
			//Check first name
				if(fName != ''){
					//Check last name
					if(lName != ''){
						//Check email
						if(email != ''){
							//check email format
							if (filter.test(email)) {
								//Check password
								if(pass != '' ){
									//Check password consistancy
									if(pass == repass ){
										return true;
									} else {//password inconsistancy
										$('#errorHolder').html('<span>Passwords do not match. Please try again.</span>').children('span').delay(2000).fadeOut(500);
										return false;
									}
								} else {//no password
									$('#errorHolder').html('<span>You must include a password. Please try again.</span>').children('span').delay(2000).fadeOut(500);
									return false;
								}
							} else {//wrong email format
								$('#errorHolder').html('<span>Your email must be of the format username@domain.something . Please try again.</span>').children('span').delay(2000).fadeOut(500);
								return false;
							}
						} else {//no email
							$('#errorHolder').html('<span>You msut include your email. Please try again.</span>').children('span').delay(2000).fadeOut(500);
							return false;
						}
					} else {//no last name
						$('#errorHolder').html('<span>You must include your last name. Please try again.</span>').children('span').delay(2000).fadeOut(500);
						return false;
					}
				} else {//no first name
					$('#errorHolder').html('<span>You must include your first name. Please try again.</span>').children('span').delay(2000).fadeOut(500);
					return false;
				}
			};
		</script>
	</body>
</html>