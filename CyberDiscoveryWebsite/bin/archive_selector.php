<?php
	session_start();
	include('../pass_prot/pass_config.php');
	$_SESSION[$session_prefix .'curCamp'] = $_POST['archive']; //Set Chosen Archive as current camp
	header('Location: ../index.php');
?>