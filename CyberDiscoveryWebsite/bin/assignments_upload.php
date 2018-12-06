<?php
ini_set("post_max_size","30M");
ini_set("upload_max_filesize","30M");
session_start();
include('../pass_prot/pass_config.php');
include('../txtdb/txtdb_commands.php');


	if(($_SESSION[$session_prefix .'permissions'] == '50') || ($_SESSION[$session_prefix .'permissions'] >= '70')) {
		$blogger = $_SESSION[$session_prefix .'unid'];
		$curCampdb = '../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/assignmentsdb.txt';
		$timestamp = time();
		function date2unix($chosenhour, $chosenminute, $chosenperiod, $chosendate){
			$datepieces = explode('/',$chosendate);
			if($chosenperiod == 'PM' && $chosenhour != 12){
				$chosenhour = $chosenhour + 12;
			}
			if($chosenperiod == 'AM' && $chosenhour == 12){
				$chosenhour = 0;
			}
			$unixtime = mktime($chosenhour,$chosenminute,0,$datepieces[0],$datepieces[1],$datepieces[2]);
			return $unixtime;
		}
//---------------------------------------------------New Blog------------------------------------------------------------------		
		if (isset($_POST['newblogcontent'])) {
			$blog_unid = md5($_SESSION[$session_prefix .'unid'] . $timestamp);
			insert_txtdb($curCampdb,'UNID,title,op,op_date,content,exp_date', $blog_unid .','. clean_txtdb($_POST['blog_title']) .','. $blogger .','. $timestamp .','. clean_txtdb($_POST['newblogcontent']) .','. date2unix($_POST['hour'], $_POST['minute'], $_POST['period'], $_POST['date']));	
		}
//---------------------------------------------------Edit Blog--------------------------------------------------------------		
		if (isset($_POST['editblogcontent'])) {
			update_txtdb($curCampdb,'UNID,'. $_POST['blog_unid'],'title,edit_author,edit_date,content,exp_date',clean_txtdb($_POST['blog_title']) .','. $blogger .','. $timestamp .','. clean_txtdb($_POST['editblogcontent']) .','. date2unix($_POST['hour'], $_POST['minute'], $_POST['period'], $_POST['date']));
		}
//--------------------------------------------Delete Blog---------------------------------------------------------------------	
		if (isset($_POST['deleteblogunid'])) {
			delete_txtdb($curCampdb,'UNID,'. $_POST['deleteblogunid']);
		}

		header('location: ../assignments.php');
	} else {
		header('location: ../assignments.php');
	}
?>	