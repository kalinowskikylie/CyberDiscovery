<?php
$emailspoof = 'richard.davidj@gmail.com';
$passwordspoof = 'kpcofgs52';
//Get configuration file	
	include('pass_config.php');
	include('../txtdb/txtdb_commands.php');
		
//Get user table
	$usersdbobj = select_txtdb('../db/users.txt');
	$usersdb = query_txtdb($usersdbobj,'*','email,' . $emailspoof);
	
	$teamsdbobj = select_txtdb('../db/teams.txt');

//check if submitted email and password are a matching pair. Login if so, reject if not	
	if($usersdb){
		foreach($usersdb as $rowcontent){
			if($rowcontent['password'] == md5($passwordspoof)){
				session_start();
				$_SESSION[$session_prefix .'fName'] = $usersdb[0]['first_name'];
				$_SESSION[$session_prefix .'lName'] = $usersdb[0]['last_name'];
				$_SESSION[$session_prefix .'email'] = $usersdb[0]['email'];
				$_SESSION[$session_prefix .'permissions'] = $usersdb[0]['permissions'];
				$_SESSION[$session_prefix .'unid'] = $usersdb[0]['UNID'];
				
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
						}
					}
				}
				if(in_array($_COOKIE['curCamp'],$activecamp)){ //If the prefferred camp save in the cookie is found to bew a current camp (also checks that the cookie exists))
					$_SESSION[$session_prefix .'curCamp'] = $_COOKIE['curCamp']; // Cookie becomes session
				} else { //If cookie is not set or if it is expired
					$_SESSION[$session_prefix .'curCamp'] = $activecamp[0]; //Set first active camp as current camp
				}
				setcookie('curCamp',$_SESSION[$session_prefix .'curCamp'],time() + (86400 * 31)); // 86400 = 1 day  | sets whatever is the current camp as the preffered camp
				$_SESSION[$session_prefix .'campunid'] = $activecamp; // Sets campunid as an array of active camps that user has access to.
			}
		}
	}
?>