<?php
	session_start();
//Get configuration file	
	include('pass_config.php');
	include('../txtdb/txtdb_commands.php');
	if($_SESSION[$session_prefix .'permissions'] < 80){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: '. $path_to_pass_folder .'/pass_prot/login.php?error=perm');
		exit;
	} else { 
		$curPage = $_GET['curPage'];
		if($curPage == ''){
			$curPage = 'users';
		}
	
	//Get list of camps and tokenize them
		$campdbobj = select_txtdb('../db/sessions.txt');
		$campdb = query_txtdb($campdbobj,'camp_name,UNID');
			
//-------------IF ON USERS PAGE---------------------------------------------------------
		if($curPage == 'users'){
			//Get list of users and tokenize them
			$usersdbobj = select_txtdb('../db/users.txt');
			$usersdb = query_txtdb($usersdbobj,'*','','last_name');
				
			//Create a DNS array for Permissions to Roles
			$roleDNS = array(1 => 'Unconfirmed`This user has requested access to this site but has not been confirmed by an admin yet. User has no permissions.', 11 => 'Guest`Can view blog and assignments.', 50 => 'Content Manager`User can edit the blog and the assignments.', 60 => 'Judge`User can view submitted assignments,rank assignments, and add comments to assignments.', 70 => 'Moderator`Can access everything for a designated camp except the admin panel.', 90 => 'Suadmin`Can access everything for all camps including designating users and teams.', 100 => 'Webmaster`User can access everything and see developmental features.'); //, 80 => 'Admin`Can access everything for a designated camp including designating users and teams.' ::Admin role to be added later. Many of the permissions exist already.
			foreach($roleDNS as $permissions => $role){
				$roleDNS[$permissions] = explode('`', $role);
			}
		}
//-----------------IF ON TEAMS PAGE----------------------------------------------------
		elseif($curPage == 'teams'){
		//Get team database, order by team name
			$teamsdbobj = select_txtdb('../db/teams.txt');
			$teamsdb = query_txtdb($teamsdbobj,'*','','team_name');
		}
//-----------------IF ON CAMPS PAGE----------------------------------------------------	
		else{
			if($_SESSION[$session_prefix .'permissions'] >= 90){
			//Get camp database, order by camp name
				$campdbobj = select_txtdb('../db/sessions.txt');
				$campdb = query_txtdb($campdbobj,'*','','camp_name');
			}
		}
//-----------------------PAGESETUP STUFF----------------------------------------------
		?>		
		<!DOCTYPE html>
		<html>
		<head>
		<title>Admin</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
		<link type="text/css" href="/cyberdiscovery/scripts/jqueryui.css" rel="stylesheet">
		<script type="text/javascript" src="/cyberdiscovery/scripts/jqueryui.js"></script>
		<script src="admin.js" type="text/javascript"></script>
		<?php
			if($pageSetupIncludes == ''){
				include('pass_pagesetup.php');
			} else {
				$webPage = 'admin';
				include($pageSetupIncludes);
			}
		?>
			<?php if($_SESSION[$session_prefix .'permissions'] < 90){ ?>
				<div id="foobar" style="display:none;" name="0"></div>
			<?php } else { ?>
				<div id="foobar" style="display:none;" name="1"></div>
			<?php } ?>
			<center><div id="masternav"><a href="?curPage=users">User Information</a> <a href="?curPage=teams">Team Information</a> <?php if($_SESSION[$session_prefix .'permissions'] >= 90){ ?><a href="?curPage=camps">Camp Information</a><?php } ?></div></center>
		<?php 			
//-----------------------------USERS----------------------------------------------------------
		if($curPage == 'users'){
		?>
		<?php if($_SESSION[$session_prefix .'permissions'] >= 90){ ?>
			<!-- Create list of camp names in a hidden div for later use-->
			<div style="display:none;" id="campnames">
				<div style="overflow:auto; max-height:4.3em; min-width:150px;">
					<span style="white-space:nowrap;"><input type="checkbox" class="all" value="all">all</span><br />
					<?php foreach($campdb as $row){
						echo('<span style="white-space:nowrap;"><input type="checkbox" class="'. str_replace(' ','',$row['camp_name']) .'" value="'. $row['UNID'] .'">'. $row['camp_name'] .'</span><br />');
					} ?>
				</div>
			</div>
		<?php } ?>
			<!-- Create roles dropdown menu for use with adding or editing users-->
			<div style="display:none;" id="roles">
				<select>
					<?php foreach($roleDNS as $permission => $role){
						echo('<option value="'. $permission .'" title="'. $role[1] .'">'. $role[0] .'</option>');
					} ?>
				</select>
			</div>
			
			<div id="navAdmin">
				<a href="#" name="addentry">Add User</a> |
				<a href="#" name="editentry">Edit User</a> |
				<a href="#" name="deleteentry">Delete User</a>
			</div>
			<div id="userTableHolder">
				<table id="userTable" style="width:100%; border-style:solid;">
				<tr style="background-color:#AAA;"><td>First Name</td><td>Last Name</td><?php if($_SESSION[$session_prefix .'permissions'] >= 90){ ?><td>Camp</td><?php } ?><td>Email</td><td>Password</td><td>Permissions</td></tr>
					<?php
						if(is_array($usersdb)){ 
							$counter =0;
							foreach($usersdb as $key => $placeHolder){
								if(($counter)%2 == 1) {
									echo('<tr name="'. $key .'" unid="'. $usersdb[$key]['UNID'] .'" class="dynamic" style="background-color:#DDD;">');
								} else {
									echo('<tr name="'. $key .'" unid="'. $usersdb[$key]['UNID'] .'" class="dynamic" style="background-color:#FDFDFD;">');
								}
								echo('<td>'. $usersdb[$key]['first_name'] .'</td>');
								echo('<td>'. $usersdb[$key]['last_name'] .'</td>');
								if($_SESSION[$session_prefix .'permissions'] >= 90){
									echo('<td>');
									$camp = explode(',',$usersdb[$key]['camp']);
									if($camp[count($camp)-1] == ''){
											unset($camp[count($camp)-1]);
									}
									$num = 0;
									foreach($camp as $c){	
										if(!($num == 0)){
											echo(', ');
										}
										if($c == 'all'){
											echo($c);
										} else {
											$dnsmatch = query_txtdb($campdbobj,'camp_name','UNID,' . $c);
											echo($dnsmatch[0]['camp_name']);
										}
										$num++;
									}
									echo('</td>');
								}
								echo('<td>'. $usersdb[$key]['email'] .'</td>');
								echo('<td name="'. $usersdb[$key]['password'] .'">HIDDEN</td>');
								echo('<td name="'. $usersdb[$key]['permissions'] .'">'. $roleDNS[$usersdb[$key]['permissions']][0] .'</td></tr>');
								$counter++;
							}
							unset($counter);
						}
					?>
				</table>
			</div>
		<?php 			
//-----------------------------TEAMS----------------------------------------------------------
		} elseif($curPage == 'teams'){
		?>
		<?php if($_SESSION[$session_prefix .'permissions'] >= 90){ ?>
			<!-- Create list of camp names in a hidden div for later use-->
			<div style="display:none;" id="campnames">
				<select>
					<?php foreach($campdb as $row){
						echo('<option class="'. str_replace(' ','',$row['camp_name']) .'" value="'. $row['UNID'] .'">'. $row['camp_name'] .'</option>');
					} ?>
				</select>
			</div>
		<?php } ?>
			<div id="navAdmin">
				<a href="#" name="addteam">Add Team</a> |
				<a href="#" name="editteam">Edit Team</a> |
				<a href="#" name="deleteteam">Delete Team</a>
			</div>
			<div id="userTableHolder">
				<table id="userTable" style="width:100%; border-style:solid;">
				<tr style="background-color:#AAA;"><td>Team Name</td><?php if($_SESSION[$session_prefix .'permissions'] >= 90){ ?><td>Camp</td><?php } ?><td>Email</td><td>Password</td></tr>
					<?php 
						if(is_array($teamsdb)){
							$counter =0;
							foreach($teamsdb as $key => $row){
								if(($counter)%2 == 1) {
									echo('<tr name="'. $key .'" unid="'. $row['UNID'] .'" class="dynamic" style="background-color:#DDD;">');
								} else {
									echo('<tr name="'. $key .'" unid="'. $row['UNID'] .'" class="dynamic" style="background-color:#FDFDFD;">');
								}
								echo('<td>'. $row['team_name'] .'</td>');
								if($_SESSION[$session_prefix .'permissions'] >= 90){
									if($row['camp'] == 'all'){
										echo('<td>'. $row['camp'] .'</td>');
									} else {
										$dnsmatch = query_txtdb($campdbobj,'camp_name','UNID,' . $row['camp']);
										echo('<td name="'. $row['camp'] .'">'. $dnsmatch[0]['camp_name'] .'</td>');
									}
								}
								echo('<td>'. $row['email'] .'</td>');
								echo('<td name="'. $row['password'] .'">HIDDEN</td>');
								$counter++;
							}
							unset($counter);
						}
					?>
				</table>
			</div>
		<?php 			
//-----------------------------CAMP SESSIONS----------------------------------------------------------
		} else{
			if($_SESSION[$session_prefix .'permissions'] >= 90){
		?>
			<div id="navAdmin">
				<a href="#" name="addcamp">Add Camp</a> |
				<a href="#" name="editcamp">Edit Camp</a> |
				<a href="#" name="deletecamp">Delete Camp</a>
			</div>
			<div id="userTableHolder">
				<table id="userTable" style="width:100%; border-style:solid;">
				<tr style="background-color:#AAA;"><td>Camp Name</td><td>Start Date</td><td>End Date</td></tr>
					<?php 
						if(is_array($campdb)){
							$counter =0;
							foreach($campdb as $key => $row){
								if(($counter)%2 == 1) {
									echo('<tr name="'. $key .'" unid="'. $row['UNID'] .'" class="dynamic" style="background-color:#DDD;">');
								} else {
									echo('<tr name="'. $key .'" unid="'. $row['UNID'] .'" class="dynamic" style="background-color:#FDFDFD;">');
								}
								echo('<td>'. $row['camp_name'] .'</td>');
								echo('<td name="'. $row['camp_start'] .'">'. date('m/d/Y', $row['camp_start']) .'</td>');
								echo('<td name="'. $row['camp_end'] .'">'. date('m/d/Y', $row['camp_end']) .'</td>');
								$counter++;
							}
							unset($counter);
						}
					?>
				</table>
			</div>
		<?php 	
			}		
		}
		?>
		</body>
		</html>
	<?php }
?>