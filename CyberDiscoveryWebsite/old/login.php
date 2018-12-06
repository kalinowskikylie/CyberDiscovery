<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>
Login | Cyber
</title>
<?php include('pagesetup.php'); ?>

        <?php
        if (isset($_SESSION['cdpermissions'])) {
			if ($_GET['login'] == 'success'){
					echo('<div id="login_success" class="blogpost" style="background-color:#C6F5C6;border-color:#00D600;">Login Successful...</div>');
			}
			echo('You are already logged in. <a href="logout.php">Logout?</a>');
             if ($_SESSION['cdpermissions'] > 50) {

                  $curSub = $_GET['curSub'];
                  if (!isset($curSub)) {
                  echo('<br /><br /><h2>Assignment Ranking Module</h2><p>Please select an assignment to rank the submissions.</p>');
//Generate list of all assignment folders is submissions
                       $path = 'submissions/';
                       if ($handle = opendir($path)) {
                            while (false !== ($file = readdir($handle))) {
                                 if($file!="." && $file!="..") {
                                      if (is_dir($path . $file)){
										  		if ($file!="pleasedeletemeaswell" && $file!="pleasedeleteme" && $file!="pleasedeletemealso") {
                                           			echo '<a href="' . $_SERVER['PHP_SELF'] . '?curSub=' . $file . '/">' . $file . "</a><br/>\n";
												}
                                      }
                                 }
                            }
                            closedir($handle);
                       }
                  } else {
                       echo('<br /><br /><h2>Assignment Ranking Module</h2><a href="http://cyberdiscovery.latech.edu/login.php">Back</a><p>Please place rankings in text boxes next to the submissions. WARNING: until all submissions are submitted numbering is subject to change!!</p>');                       
//show list all all assignments in folder (no names, just sub1, sub2, sub3, sub4, etc) weed out the backup file
                       $path = 'submissions/' . $curSub . '/';
                       echo('<form action="bin/submission_rankings.php" method="POST"><input type="hidden" name="assignmentnum" value="' . $curSub . '" />');
                       if ($handle = opendir($path)) {
                            while (false !== ($file = readdir($handle))) {
                                 if($file!="." && $file!="..") {
                                      if ($file!="backup.txt") {
                                           $fileExplosion = explode('.',$file);
                                           echo('<a href="' . $path . $file . '">' . $file . '</a><input type="text" name="' . $fileExplosion[0] . '" />' . "<br/>\n");
                                           unset($fileExplosion);
                                           $subcount++;
                                      }
                                 }
                            }
                            closedir($handle);
                       }
                       echo('<input type="submit" /></form>');
                  }
             }
	}
	else {
	?>
        
        <form action="bin/admin_login.php" enctype="multipart/form-data" method="post">
        <center>
            <table class="blogpost">
            	<?php if($_GET['login'] == 'fail'){ echo('<tr><td align="center" colspan="2"><span style="color:red;">Incorrect Username/Password</span></td></tr>'); } ?>
                <tr>
                    <td>
                        Username:
                    </td>
                    <td>
                        <input type="text" id="user" name="user"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Password:
                    </td>
                    <td>
                        <input type="password" id="password" name="password"/>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td align="right">
                        <input type="submit" value="Login" />
                    </td>
                </tr>
            </table>
        </center>
        </form>
        <?php } ?>

<?php include('footer.php'); ?>