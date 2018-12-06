<?php
session_start();
if (! ($_SESSION['permissions'] >= 70)) {
    $_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
    header('Location: pass_prot/login.php?error=perm');
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="charset=utf-8" />
<link rel="stylesheet" type="text/css" href="cyber.css" />
<title>Cyber Curriculum</title>
</head>

<body>
    	<?php $webPage='upload'; include('pagesetup.php');?>
        <div
		style="background-color: #CCC; width: 180px; margin: 25px; padding: 15px;">
		<form action="uploader.php" enctype="multipart/form-data"
			method="POST">
			Choose a file to upload:
			<input name="uploaded" id="uploaded" type="file" />
			<br /> Enter Initials:
			<input type="text" name="initials" id="initials" />
			<br />
			<input type="submit" value="Upload File" />
		</form>
	</div>
        <?php if ($_SESSION['permissions'] >= 90){?><iframe
		src="uploader_admin.php" style="border-width:0" width="688.019"
		height="700" frameborder="0" scrolling="yes"></iframe><?php } ?>
        
    </body>
</html>