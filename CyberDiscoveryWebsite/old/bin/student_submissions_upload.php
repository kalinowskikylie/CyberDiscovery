<?php
session_start();
if ($_SESSION['cdpermissions'] == 10) {
     if ($_FILES['studentsubmission']['size'] > 1000) {
          $studentsubmission = $_POST['studentsubmission'];
          $assignnum = $_POST['assignnum'];
          $teamname = $_SESSION['cdname'];

          if(!is_dir('../submissions/assignment' . $assignnum)){
               mkdir('../submissions/assignment' . $assignnum);
          }

          $target =  basename($_FILES['studentsubmission']['name']);
          $targetexplosion = explode('.',$target);
          $targettype = $targetexplosion[count($targetexplosion)-1];

          if ($teamname == 'Ruston') {
               $teamname = 3568789*($assignnum+3);
          }
          elseif ($teamname == 'Benton') {
               $teamname = 1235798*($assignnum+3);
          }
          elseif ($teamname == 'Parkway') {
               $teamname = 2340589*($assignnum+3);
          }
          elseif ($teamname == 'Haughton') {
               $teamname = 2456787*($assignnum+3);
          }
		  elseif ($teamname == 'Minden') {
               $teamname = 5687432*($assignnum+3);
          }
		  elseif ($teamname == 'North Desoto') {
               $teamname = 4878895*($assignnum+3);
          }
		  elseif ($teamname == 'Evangel') {
               $teamname = 1234790*($assignnum+3);
          }
		  elseif ($teamname == 'El Dorado') {
               $teamname = 9800567*($assignnum+3);
          }
		  elseif ($teamname == 'Byrd') {
               $teamname = 7521446*($assignnum+3);
          }
		  elseif ($teamname == 'Airline') {
               $teamname = 8797437*($assignnum+3);
          }
		  
          if(file_exists('../submissions/assignment' . $assignnum . '/' . $teamname . '.' . $targettype)) {
               echo('File Already Exists. If you have not submitted your file yet and this has been reached in error please contact camp administration immediately.');
          } else {
               move_uploaded_file($_FILES["studentsubmission"]["tmp_name"], '../submissions/assignment' . $assignnum . '/' . $teamname . '.' . $targettype);
			   echo('File Uploaded Successfully!');
          }

     } else {
          echo('An error has occurred. If this message was reached during normal usage of the website, please contact a camp administrator.');
     }
}
?>
