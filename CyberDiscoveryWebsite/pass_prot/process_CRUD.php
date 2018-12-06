<?php
	session_start();
	include('pass_config.php');
	include('../txtdb/txtdb_commands.php');
	if ($_SESSION[$session_prefix .'permissions'] >= 80) {
		if ($_SESSION[$session_prefix .'permissions'] < 90) {
			$campvar = $_SESSION[$session_prefix .'campunid'];
		} else {
			$campvar = $_POST['camp'];
		}
//------------------USERS--------------------------------------------			
		if (isset($_POST['newemail'])) {
			$usersdbobj = select_txtdb('../db/users.txt');
			$usersdb = query_txtdb($usersdbobj,'*','email,'. $_POST['newemail']);
			if(!($usersdb)){
				$unid = md5($_POST['newemail'].time());
				$password = md5($_POST['newpassword']);
				insert_txtdb('../db/users.txt','UNID,first_name,last_name,email,password,camp,permissions',$unid . ',' . clean_txtdb($_POST['newfName']) . ',' . clean_txtdb($_POST['newlName']) . ',' . clean_txtdb($_POST['newemail']) . ',' . $password . ',' . clean_txtdb($campvar) .','. $_POST['newpermissions']);
			} else {
				header('Location: admin.php?error=email');
				exit;
			}
		}
		
		if(isset($_POST['editemail'])){
			$usersdbobj = select_txtdb('../db/users.txt');
			$usersdb = query_txtdb($usersdbobj,'*','email,'. $_POST['editemail']);
			$emailgood = '1';
			foreach($usersdb as $row){
				if(!($row['UNID'] == $_POST['unid'])){
					$emailgood = '0';
				}
			}
			if($emailgood == '1'){
				if(!($_POST['editpassword'] == 'HIDDEN')){
					$hashPassword = md5($_POST['editpassword']);
				} else {
					$hashPassword = $_POST['edithpassword'];
				}
				update_txtdb('../db/users.txt','UNID,'. $_POST['unid'],'UNID,first_name,last_name,email,password,camp,permissions',$_POST['unid'] . ',' . clean_txtdb($_POST['editfName']) . ',' . clean_txtdb($_POST['editlName']) . ',' . clean_txtdb($_POST['editemail']) . ',' . $hashPassword . ',' . clean_txtdb($campvar) .','. $_POST['editpermissions']);
			} else {
				header('Location: admin.php?error=email');
				exit;
			}
		}
		if(isset($_POST['deleteUser'])){
			$usersdbobj = select_txtdb('../db/users.txt');
			$usersdb = query_txtdb($usersdbobj,'*','UNID,'. $_POST['deleteUser']);
			insert_txtdb('../db/users_graveyard.txt','first_name,last_name,camp,email,password,permissions,UNID',clean_txtdb($usersdb[0]['first_name']) .','. clean_txtdb($usersdb[0]['last_name']) .','. clean_txtdb($usersdb[0]['camp']) .','. clean_txtdb($usersdb[0]['email']) .','. $usersdb[0]['password'] .','. $usersdb[0]['permissions'] .','. $usersdb[0]['UNID']);
			delete_txtdb('../db/users.txt','UNID,' . $_POST['deleteUser']);
		}
		
//------------------TEAMS--------------------------------------------
		if (isset($_POST['newteam_name'])) {
			$teamunid = md5($_SESSION[$session_prefix .'unid'].time());
			$teampassword = md5($_POST['newteam_password']);
			insert_txtdb('../db/teams.txt','UNID,team_name,email,password,camp',$teamunid . ',' . clean_txtdb($_POST['newteam_name']) . ',' . clean_txtdb($_POST['newteam_email']) . ',' . $teampassword . ',' . clean_txtdb($campvar));
		}
		
		if(isset($_POST['editteam_name'])){
			if(!($_POST['editteam_password'] == 'HIDDEN')){
				$hashPassword = md5($_POST['editteam_password']);
			} else {
				$hashPassword = $_POST['editteam_hpassword'];
			}
			update_txtdb('../db/teams.txt','UNID,' . $_POST['unid'],'UNID,team_name,email,password,camp',$_POST['unid'] . ',' . clean_txtdb($_POST['editteam_name']) . ',' . clean_txtdb($_POST['editteam_email']) . ',' . $hashPassword . ',' . clean_txtdb($campvar));
		}
		if(isset($_POST['deleteteam_unid'])){
			$teamsdbobj = select_txtdb('../db/teams.txt');
			$teamsdb = query_txtdb($teamsdbobj,'*','UNID,'. $_POST['deleteteam_unid']);
			insert_txtdb('../db/teams_graveyard.txt','UNID,team_name,email,password,camp',clean_txtdb($teamsdb[0]['UNID']) .','. clean_txtdb($teamsdb[0]['team_name']) .','. clean_txtdb($teamsdb[0]['email']) .','. clean_txtdb($teamsdb[0]['password']) .','. $teamsdb[0]['camp']);
			delete_txtdb('../db/teams.txt','UNID,' . $_POST['deleteteam_unid']);
		}
//------------------CAMPS--------------------------------------------
		if ($_SESSION[$session_prefix .'permissions'] >= 90) {
		if (isset($_POST['newcamp_name'])) {
			$campunid = md5($_SESSION[$session_prefix .'unid'].time());
			function date2unix($chosendate){
				$datepieces = explode('/',$chosendate);
				$unixtime = mktime(0,0,0,$datepieces[0],$datepieces[1],$datepieces[2]);
				return $unixtime;
			}
		//Create New Camp Directory and Files
			mkdir('../camps/'. $campunid, 0770);
			mkdir('../camps/'. $campunid .'/blog_uploads', 0770);
			mkdir('../camps/'. $campunid .'/assignments_uploads', 0770);
			mkdir('../camps/'. $campunid .'/submissions', 0770);
			$bloglocation = '../camps/'. $campunid .'/blogdb.txt';
			$bloghandle = fopen($bloglocation,'w');
			fwrite($bloghandle,'UNID(*)title(*)op(*)op_date(*)edit_author(*)edit_date(*)content|!|');
			while(!(filesize($bloglocation) > 0)){
				fwrite($bloghandle,'UNID(*)title(*)op(*)op_date(*)edit_author(*)edit_date(*)content|!|');
			}
			fclose($bloghandle);
			$assignmentslocation = '../camps/'. $campunid .'/assignmentsdb.txt';
			$assignmentshandle = fopen($assignmentslocation,'w');
			fwrite($assignmentshandle,'UNID(*)title(*)op(*)op_date(*)edit_author(*)edit_date(*)content(*)exp_date|!|');
			while(!(filesize($assignmentslocation) > 0)){
				fwrite($assignmentshandle,'UNID(*)title(*)op(*)op_date(*)edit_author(*)edit_date(*)content(*)exp_date|!|');
			}
			fclose($assignmentshandle);
			$submissionslocation = '../camps/'. $campunid .'/submissionsdb.txt';
			$submissionshandle = fopen($submissionslocation,'w');
			fwrite($submissionshandle,'UNID(*)title(*)assignment(*)team(*)time(*)url(*)judge_rankings(*)overall_ranking|!|');
			while(!(filesize($submissionslocation) > 0)){
				fwrite($submissionshandle,'UNID(*)title(*)assignment(*)team(*)time(*)url(*)judge_rankings(*)overall_ranking|!|');
			}
			fclose($submissionshandle);
			chmod('../camps/'. $campunid,0770);
			chmod('../camps/'. $campunid .'/blog_uploads',0770);
			chmod('../camps/'. $campunid .'/assignments_uploads',0770);
			chmod('../camps/'. $campunid .'/submissions',0770);
			chmod('../camps/'. $campunid .'/assignmentsdb.txt',0770);
			chmod('../camps/'. $campunid .'/blogdb.txt',0770);
			chmod('../camps/'. $campunid .'/submissionsdb.txt',0770);
			insert_txtdb('../db/sessions.txt','UNID,camp_name,camp_start,camp_end',$campunid . ',' . clean_txtdb($_POST['newcamp_name']) . ',' . date2unix($_POST['newcamp_start']) . ',' . date2unix($_POST['newcamp_end']));
		}
		
		if(isset($_POST['editcamp_name'])){
			function date2unix($chosendate){
				$datepieces = explode('/',$chosendate);
				$unixtime = mktime(0,0,0,$datepieces[0],$datepieces[1],$datepieces[2]);
				return $unixtime;
			}	
			update_txtdb('../db/sessions.txt','UNID,' . $_POST['unid'],'UNID,camp_name,camp_start,camp_end',$_POST['unid'] . ',' . clean_txtdb($_POST['editcamp_name']) . ',' . date2unix($_POST['editcamp_start']) . ',' . date2unix($_POST['editcamp_end']));
		}
		if(isset($_POST['deletecamp_unid'])){
				 function recursive_remove_directory($directory, $empty=FALSE)
					{
				    // if the path has a slash at the end we remove it here
				     if(substr($directory,-1) == '/')
				     {
				         $directory = substr($directory,0,-1);
				     }
				  
				     // if the path is not valid or is not a directory ...
				     if(!file_exists($directory) || !is_dir($directory))
				     {
				         // ... we return false and exit the function
				         return FALSE;
				  
				     // ... if the path is not readable
				     }elseif(!is_readable($directory))
				     {
				         // ... we return false and exit the function
				         return FALSE;
				  
				     // ... else if the path is readable
				     }else{
				  
				         // we open the directory
				         $handle = opendir($directory);
				  
				         // and scan through the items inside
				         while (FALSE !== ($item = readdir($handle)))
				         {
				             // if the filepointer is not the current directory
				             // or the parent directory
				             if($item != '.' && $item != '..')
				             {
				                 // we build the new path to delete
				                 $path = $directory.'/'.$item;
				  
				                 // if the new path is a directory
				                 if(is_dir($path)) 
				                 {
				                     // we call this function with the new path
				                     recursive_remove_directory($path);
				  
				                 // if the new path is a file
				                 }else{
				                     // we remove the file
				                     unlink($path);
				                 }
				             }
				         }
				         // close the directory
				         closedir($handle);
				  
				         // if the option to empty is not set to true
				         if($empty == FALSE)
				         {
				             // try to delete the now empty directory
				             if(!rmdir($directory))
				             {
				                 // return false if not possible
				                 return FALSE;
				             }
				         }
				         // return success
				         return TRUE;
				     }
				 }
				 recursive_remove_directory('../camps/'. $_POST['deletecamp_unid']);
				 delete_txtdb('../db/teams.txt','camp,' . $_POST['deletecamp_unid']);
			delete_txtdb('../db/sessions.txt','UNID,' . $_POST['deletecamp_unid']);
		}
		}
	} else {
	include('pass_config.php');
	header('location: '. $website_main);
	}
?>