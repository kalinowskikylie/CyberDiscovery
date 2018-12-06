<?php 
session_start();
//include('../pagesetup.php');
if (isset($_SESSION['cdpermissions'])) {	
     echo('You are already logged in.');
}
		else {
			$user=$_POST[user];
			$password=$_POST[password];
			
			if ($user == "htims" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Heath";
				$_SESSION['cdlname'] ="Tims";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "kcorbett" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Krystal";
				$_SESSION['cdlname'] ="Corbett";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "billw" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Bill";
				$_SESSION['cdlname'] ="Willoughby";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "gturner" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Galen";
				$_SESSION['cdlname'] ="Turner";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "kellyc" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Kelly";
				$_SESSION['cdlname'] ="Crittenden";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "jmhire" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Jeremy";
				$_SESSION['cdlname'] ="Mhire";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "betheridge" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Brian";
				$_SESSION['cdlname'] ="Etheridge";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "duncan" && $password == "bu11d0g5") {
				$_SESSION['cdpermissions'] = "90";
				$_SESSION['cdname'] ="Christian";
				$_SESSION['cdlname'] ="Duncan";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "judge1" && $password == "Bog@rdH@ll251") {
				$_SESSION['cdpermissions'] = "60";
				$_SESSION['cdname'] ="Judge";
				$_SESSION['cdlname'] ="One";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "judge2" && $password == "@rduino120") {
				$_SESSION['cdpermissions'] = "60";
				$_SESSION['cdname'] ="Judge";
				$_SESSION['cdlname'] ="Two";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "drichard" && $password == "kpcofgs52") {
				$_SESSION['cdpermissions'] = "100";
				$_SESSION['cdname'] ="David";
				$_SESSION['cdlname'] ="Richard";
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=success');
			}
			elseif ($user == "ruston" && $password == "rabbit") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Ruston";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "benton" && $password == "brick") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Benton";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "parkway" && $password == "paper") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Parkway";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "haughton" && $password == "hybrid") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Haughton";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "minden" && $password == "major") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Minden";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "northdesoto" && $password == "newt") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="North Desoto";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "evangel" && $password == "elevator") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Evangel";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "eldorado" && $password == "echo") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="El Dorado";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "byrd" && $password == "brother") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Byrd";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			elseif ($user == "airline" && $password == "anchor") {
				$_SESSION['cdpermissions'] = "10";
				$_SESSION['cdname'] ="Airline";
				$_SESSION['cdlname'] ="";
				header('Location: http://cyberdiscovery.latech.edu/old/index.php?login=success');
			}
			else {
				session_destroy();
				header('Location: http://cyberdiscovery.latech.edu/old/login.php?login=fail');
			}
		}
        ?>