<?php
	session_start();
//Get configuration file	
	include('pass_config.php');
	if($_SESSION['permissions'] < 90){
		echo('<p>This page is only visible to admins. <a href="login.php">Need to Login?</a></p>');
	} else { 
		//Open user database and read contents out of file
			$myFile = "users_db.txt";	//This is location of database file
			$fh = fopen($myFile, 'r');
			$userDb = fread($fh, filesize($myFile));
			fclose($fh);
			
		//Seperate file into seperate entries. Delete last entry which should always be null
			$userRows = explode('|!|',$userDb);
			if($userRows[count($userRows)-1] == ''){
				unset($userRows[count($userRows)-1]);
			}

		//Loop through each entry and store user information in appropriate variables		
			foreach($userRows as $key => $rows){
				$tempUserPieces = explode('(*)',$rows);
				$users['fName'][$key] = str_replace("\n",'',$tempUserPieces[0]);
				$users['lName'][$key] = $tempUserPieces[1];
				$users['email'][$key] = $tempUserPieces[2];
				$users['password'][$key] = $tempUserPieces[3];
				$users['permissions'][$key] = $tempUserPieces[4];
			}
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<title>Admin</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
		<script src="admin.js" type="text/javascript"></script>
		<?php
			if($pageSetupIncludes == ''){
				include('pass_pagesetup.php');
			} else {
				$webPage = 'admin';
				include($pageSetupIncludes);
			}
		?>
			<h1>User Information</h1>
			<div id="navAdmin">
				<a href="#" name="addentry">Add User</a> |
				<a href="#" name="editentry">Edit User</a> |
				<a href="#" name="deleteentry">Delete User</a>
			</div>
			<div id="userTableHolder">
				<table id="userTable" style="width:100%; border-style:solid;">
				<tr style="background-color:#AAA;"><td>First Name</td><td>Last Name</td><td>Email</td><td>Password</td><td>Permissions</td></tr>
					<?php 
						natcasesort($users['lName']);
						$counter =0;
						foreach($users['lName'] as $key => $placeHolder){
							if(($counter)%2 == 1) {
								echo('<tr name="'. $key .'" class="dynamic" style="background-color:#DDD;">');
							} else {
								echo('<tr name="'. $key .'" class="dynamic" style="background-color:#FDFDFD;">');
							}
							echo('<td>'. $users['fName'][$key] .'</td>');
							echo('<td>'. $users['lName'][$key] .'</td>');
							echo('<td>'. $users['email'][$key] .'</td>');
							echo('<td name="'. $users['password'][$key] .'">HIDDEN</td>');
							echo('<td>'. $users['permissions'][$key] .'</td></tr>');
							$counter++;
						}
						unset($counter);
					?>
				</table>
			</div>
		</body>
		</html>
	<?php }
?>