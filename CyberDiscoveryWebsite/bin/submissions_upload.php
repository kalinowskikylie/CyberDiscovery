<?php
session_start();
include('../pass_prot/pass_config.php');
include('../txtdb/txtdb_commands.php');

//Check if assignment due date is passed or not
	$timestamp = time();
	$assignmentdbobj = select_txtdb('../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/assignmentsdb.txt');
	$exp_date = query_txtdb($assignmentdbobj,'exp_date', 'UNID,'. $_POST['assignment']);
	if($exp_date[0]['exp_date'] > $timestamp){

		if($_SESSION[$session_prefix .'permissions'] == '10') {
			$blacklist = array(".php", ".phtml", ".php3", ".php4"); //check for PHP files
			foreach ($blacklist as $item) {
				if(preg_match("/$item\$/i", $_FILES['submission']['name'])) {
					echo "We do not allow uploading PHP files\n";
				exit;
				}
			}
			
			$team = $_SESSION[$session_prefix .'unid'];
			$newUNID = md5($_SESSION[$session_prefix .'unid'] . $timestamp);
			$curCampdb = '../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt';
			$urlPath = 'http://cyberdiscovery.latech.edu/camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissions/';
			$namepieces = explode('.',basename($_FILES['submission']['name']));
			$title = $namepieces[0];
			$extension = $namepieces[1];
			
		
			if ($_FILES["submission"]["size"] < 300000000){
				if ($_FILES["submission"]["error"] > 0){
					echo "Return Code: " . $_FILES["submission"]["error"] . "<br />";
			  	} else {
					move_uploaded_file($_FILES["submission"]["tmp_name"],'../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissions/' . $title .'-'. $newUNID .'.'. $extension);	
					$submissionsdbobj = select_txtdb($curCampdb);
					$assignmentcheck = query_txtdb($submissionsdbobj,'UNID,assignment,url','team,'. $team);				
					if($assignmentcheck){
						foreach($assignmentcheck as $row){
							if($row['assignment'] == $_POST['assignment']){
								delete_txtdb($curCampdb,'UNID,'. $row['UNID']);
								unlink('../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissions/' . basename($row['url']));
							}
						}
					}
					insert_txtdb($curCampdb,'UNID,title,assignment,team,time,url', $newUNID .','. $title .','. $_POST['assignment'] .','. $team .','. $timestamp .','. $urlPath . $title .'-'. $newUNID .'.'. $extension);
					header('location: ../assignments.php');	
				}
			} else {
				echo "Invalid file size. Must be less than 300mb.";
			}
		} else {
			header('location: ../assignments.php');
		}
	} else {
		echo('The due date for this assignment has passed. If you feel like you have reached this message in error, please contact a camp administrator immediately.');
	}
?>	
