<?php
session_start();
include('../pass_prot/pass_config.php');
include('../txtdb/txtdb_commands.php');


	if(($_SESSION[$session_prefix .'permissions'] == '50') || ($_SESSION[$session_prefix .'permissions'] >= '70')) {
		$blogger = $_SESSION[$session_prefix .'unid'];
		$curCampdb = '../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/blogdb.txt';
		$timestamp = time();
//---------------------------------------------------New Blog------------------------------------------------------------------		
		if (isset($_POST['newblogcontent'])) {
			$blog_unid = md5($_SESSION[$session_prefix .'unid'] . $timestamp);
			insert_txtdb($curCampdb,'UNID,title,op,op_date,content', $blog_unid .','. clean_txtdb($_POST['blog_title']) .','. $blogger .','. $timestamp .','. clean_txtdb($_POST['newblogcontent']));	
		}
//---------------------------------------------------Edit Blog--------------------------------------------------------------		
		if (isset($_POST['editblogcontent'])) {
			update_txtdb($curCampdb,'UNID,'. $_POST['blog_unid'],'title,edit_author,edit_date,content',clean_txtdb($_POST['blog_title']) .','. $blogger .','. $timestamp .','. clean_txtdb($_POST['editblogcontent']));
		}
//--------------------------------------------Delete Blog---------------------------------------------------------------------	
		if (isset($_POST['deleteblogunid'])) {
			delete_txtdb($curCampdb,'UNID,'. $_POST['deleteblogunid']);
		}

		header('location: ../blog.php');
	} else {
		header('location: ../blog.php');
	}
?>	