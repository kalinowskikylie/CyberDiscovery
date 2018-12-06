<?php
//Get configuration file	
	include('pass_config.php');
	include('../txtdb/txtdb_commands.php');
		
//Get user table
	$usersdbobj = select_txtdb('../db/users.txt');
	$usersdb = query_txtdb($usersdbobj,'*','email,' . $_POST['loginEmail']);
	$teamsdbobj = select_txtdb('../db/teams.txt');
	$teamsdb = query_txtdb($teamsdbobj,'*','email,' . $_POST['loginEmail']);
//check if submitted email and password are a matching pair. Login if so, reject if not	
	if($usersdb){ //Check the users database for user
		foreach($usersdb as $rowcontent){
			if($rowcontent['password'] == md5($_POST['loginPassword'])){
				session_start();

// ------------------Camp Expiration Manager-----------------------------------------------------				
				//Break camp string by commas into array
				$camp = explode(',',$usersdb[0]['camp']);
				if($camp[count($camp)-1] == ''){
						unset($camp[count($camp)-1]);
				}
				//Create camp db of unids and dates
				$campdbobj = select_txtdb('../db/sessions.txt');
				
				//If camps are set to all, select all camps from camp database
				if($camp[0] == 'all'){
					$camptempdb = query_txtdb($campdbobj,'UNID');
					foreach($camptempdb as $key => $row){
						$camp[$key] = $row['UNID'];
					}
				}
			
				//Cycle through camps querying and checking dates for active or inactive
				$counter = 0;
				foreach($camp as $c){
					$campdb = query_txtdb($campdbobj,'*','UNID,' . $c);
					if($campdb[0]['camp_start'] <= time()){ //If the camp's start date has passed
						if($campdb[0]['camp_end'] >= time()){ //If the camp is still in session
							$activecamp[$counter] = $c;
							$counter++;
						} else { //If the camp has expired
							insert_txtdb('../db/sessions_graveyard.txt','UNID,camp_name,camp_start,camp_end',$campdb[0]['UNID'] .','. $campdb[0]['camp_name'] .','. $campdb[0]['camp_start'] .','. $campdb[0]['camp_end']); //Add camp to sessions_graveyard.txt
							delete_txtdb('../db/sessions.txt','UNID,'. $c); //Remove camp from sessions.txt
							//Add associated teams to teams_graveyard.txt
							$teamsdb = query_txtdb($teamsdbobj,'*','camp,'. $c);
							if($teamsdb){
								foreach($teamsdb as $row){
									insert_txtdb('../db/teams_graveyard.txt','UNID,team_name,email,password,camp',$row['UNID'] .','. $row['team_name'] .','. $row['email'] .','. $row['password'] .','. $row['camp']);
								}
							}
							delete_txtdb('../db/teams.txt','camp,'. $c);//Remove associated teams from teams.txt
							//Remove camp on users with associated camp
							$usersdbcamplookup = query_txtdb($usersdbobj,'UNID,camp');
							foreach($usersdbcamplookup as $rowkey => $rowcontent){
								$rowpieces = explode(',', $rowcontent['camp']);
								if(in_array($c,$rowpieces)){
									$key2delete = array_search($c, $rowpieces);
									unset($rowpieces[$key2delete]);
								}
								$rowcontentcamp = implode(',', $rowpieces);
								update_txtdb('../db/users.txt','UNID,'. $rowcontent['UNID'],'camp', clean_txtdb($rowcontentcamp));
							}
						}
					}
				}
				if(is_array($activecamp)){
					if(in_array($_COOKIE['curCamp'],$activecamp)){ //If the prefferred camp save in the cookie is found to be a current camp (also checks that the cookie exists))
						$_SESSION[$session_prefix .'curCamp'] = $_COOKIE['curCamp']; // Cookie becomes session
					} else { //If cookie is not set or if it is expired
						$_SESSION[$session_prefix .'curCamp'] = $activecamp[0]; //Set first active camp as current camp
					}
					setcookie('curCamp',$_SESSION[$session_prefix .'curCamp'],time() + (86400 * 31),'/cyberdiscovery/'); // 86400 = 1 day  | sets whatever is the current camp as the preffered camp
					$_SESSION[$session_prefix .'fName'] = $usersdb[0]['first_name'];
					$_SESSION[$session_prefix .'lName'] = $usersdb[0]['last_name'];
					$_SESSION[$session_prefix .'email'] = $usersdb[0]['email'];
					$_SESSION[$session_prefix .'permissions'] = $usersdb[0]['permissions'];
					$_SESSION[$session_prefix .'unid'] = $usersdb[0]['UNID'];
					$_SESSION[$session_prefix .'campunid'] = $activecamp; // Sets campunid as an array of active camps that user has access to.
				} else {
					header('Location: login.php?error=no_camps');
					exit;
				}
//------------------End Camp Expiration Manager-----------------------------------------------------
				if(isset($_SESSION['referingPage'])){
					$referingPage = $_SESSION['referingPage'];
					unset($_SESSION['referingPage']);
				} else {
					$referingPage = $website_main;
				}
				header('Location: '. $referingPage);	//Send user to page that caused them to login
				echo('<span>If you see this text, an error has occured. Please try visiting the website again. If the problem persists. Contact the webmaster.</span>');
				exit;
			} else {
					header('Location: login.php?error=password');
					exit;
			}
		}
	} elseif($teamsdb){ //Check the teams database for user
		foreach($teamsdb as $rowcontent){
			if($rowcontent['password'] == md5($_POST['loginPassword'])){
				session_start();
				

// ------------------Camp Expiration Manager-----------------------------------------------------				
				$c = $teamsdb[0]['camp'];
				//Create camp db of unids and dates
				$campdbobj = select_txtdb('../db/sessions.txt');
				//Check if camp is active or inactive
				$campdb = query_txtdb($campdbobj,'*','UNID,' . $c);
				if($campdb[0]['camp_start'] <= time()){ //If the camp's start date has passed
					if($campdb[0]['camp_end'] >= time()){ //If the camp is still in session
						$activecamp = $c;
					} else { //If the camp has expired
						insert_txtdb('../db/sessions_graveyard.txt','UNID,camp_name,camp_start,camp_end',$campdb[0]['UNID'] .','. $campdb[0]['camp_name'] .','. $campdb[0]['camp_start'] .','. $campdb[0]['camp_end']); //Add camp to sessions_graveyard.txt
						delete_txtdb('../db/sessions.txt','UNID,'. $c); //Remove camp from sessions.txt
						//Add associated teams to teams_graveyard.txt
						$teamsdb = query_txtdb($teamsdbobj,'*','camp,'. $c);
						foreach($teamsdb as $row){
							insert_txtdb('../db/teams_graveyard.txt','UNID,team_name,email,password,camp',$row['UNID'] .','. $row['team_name'] .','. $row['email'] .','. $row['password'] .','. $row['camp']);
						}
						delete_txtdb('../db/teams.txt','camp,'. $c);//Remove associated teams from teams.txt
						//Remove camp on users with associated camp
						$usersdbcamplookup = query_txtdb($usersdbobj,'UNID,camp');
						foreach($usersdbcamplookup as $rowkey => $rowcontent){
							$rowpieces = explode(',', $rowcontent['camp']);
							if(in_array($c,$rowpieces)){
								$key2delete = array_search($c, $rowpieces);
								unset($rowpieces[$key2delete]);
							}
							$rowcontentcamp = implode(',', $rowpieces);
							update_txtdb('../db/users.txt','UNID,'. $rowcontent['UNID'],'camp', clean_txtdb($rowcontentcamp));
						}
						header('Location: login.php?error=expired_camp');
						exit;
					}
				} else {
					header('Location: login.php?error=incubating_camp');
					exit;
				}
				$_SESSION[$session_prefix .'fName'] = $teamsdb[0]['team_name'];
				$_SESSION[$session_prefix .'email'] = $teamsdb[0]['email'];
				$_SESSION[$session_prefix .'permissions'] = '10';
				$_SESSION[$session_prefix .'unid'] = $teamsdb[0]['UNID'];
				$_SESSION[$session_prefix .'curCamp'] = $activecamp;
				//$_SESSION[$session_prefix .'campunid'] = $activecamp; // Sets campunid as an array of active camps that user has access to.
//------------------End Camp Expiration Manager-----------------------------------------------------
				if(isset($_SESSION['referingPage'])){
					$referingPage = $_SESSION['referingPage'];
					unset($_SESSION['referingPage']);
				} else {
					$referingPage = $website_main;
				}
				header('Location: '. $referingPage);	//Send user to page that caused them to login
				echo('<span>If you see this text, an error has occured. Please try visiting the website again. If the problem persists. Contact the webmaster.</span>');
				exit;
			} else {
					header('Location: login.php?error=password');
					exit;
			}
		}
	} else {
		header('Location: login.php?error=email');
		exit;
	}

?>