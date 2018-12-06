<?php
	session_start();
	include('../pass_prot/pass_config.php');
	if(in_array($_POST['switchcamp'],$_SESSION[$session_prefix .'campunid'])){
		$_SESSION[$session_prefix .'curCamp'] = $_POST['switchcamp'];
		setcookie('curCamp',$_SESSION[$session_prefix .'curCamp'],time() + (86400 * 31),'/cyberdiscovery/'); // 86400 = 1 day
	}
	header('Location: '. $_POST['referringPage']);
?>