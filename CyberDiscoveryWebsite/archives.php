<?php
	session_start();
	include('pass_prot/pass_config.php');
	if($_SESSION[$session_prefix .'permissions'] < 90){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: '. $path_to_pass_folder .'/pass_prot/login.php?error=perm');
		exit;
	}
	include('txtdb/txtdb_commands.php');
	
	$sessions_graveyardobj = select_txtdb('db/sessions_graveyard.txt'); //Select and create session graveyard database structure
	$oldcamps = query_txtdb($sessions_graveyardobj,'*','','op_date,ascend');
?>

<!DOCTYPE html>
<html>
<head>
<title>
Archives | Cyber
</title>
<?php include('pagesetup.php'); ?>

<h2>Archives</h2>
<?php
	echo('<form action="bin/archive_selector.php" method="post">');
	echo('<select name="archive">');
	foreach($oldcamps as $camp){
		echo('<option value="'. $camp['UNID'] .'">'. $camp['camp_name'] .'</option>');
	}
	echo('<input type="submit" value="Select" />');
	echo('</select>');
	echo('</form>');
?>
<?php include('footer.php'); ?>