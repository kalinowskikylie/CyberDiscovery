<?php
session_start();
ini_set("memory_limit","1000M");
ini_set("max_execution_time","100");
ini_set("max_input_time","100M");
function createThumbs( $pathToImages, $fname, $pathToThumbs, $thumbWidth ) 
{
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is a JPG, JPEG, PNG image
    if ( (strtolower($info['extension']) == 'jpg') || (strtolower($info['extension']) == 'jpeg') || (strtolower($info['extension']) == 'png')) 
    {
      // load image and get image size
      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );

      // create a new temporary image
	  
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
	  if ((strtolower($info['extension']) == 'png'))
		{
			imagepng( $tmp_img, "{$pathToThumbs}{$fname}" ); 
		} else {
			imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
		}
	  imagedestroy($tmp_img);
	  imagedestroy($img);
    }
}
// call createThumb function and pass to it as parameters the path 
// to the directory that contains images, the path to the directory
// in which thumbnails will be placed and the thumbnail's width. 
// We are assuming that the path will be a relative path working 
// both in the filesystem, and through the web for links


	if ($_SESSION['cdpermissions']>50) {
		$blogger = $_SESSION['cdname'] . ' ' . $_SESSION['cdlname'];
		$date = date('F j, Y');
		$timestamp = time();
//---------------------------------------------------New Blog------------------------------------------------------------------		
		if (isset($_POST['newblogcontent'])) {
			$newblogcontent = $_POST['newblogcontent'];
			if ($_FILES['newpic']['size'] > 1000){
				$target =  basename($_FILES['newpic']['name']);
				$targetexplosion = explode('.',$target);
				$targettype = $targetexplosion[count($targetexplosion)-1];
				
				move_uploaded_file($_FILES["newpic"]["tmp_name"], "../assignments/" . $timestamp . '.' . $targettype);
				move_uploaded_file($_FILES["newpic"]["tmp_name"], "../assignments/thumbs/" . $timestamp . '.' . $targettype);
				$fname = $timestamp . '.' . $targettype;
				createThumbs("../assignments/",$fname,"../assignments/thumbs/",200);
				createThumbs("../assignments/",$fname,"../assignments/",745);
				
                                $newfilenames= '';
                                if ($_FILES['newfile1']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile1"]["tmp_name"], "../assignments/" . basename($_FILES['newfile1']['name']));
                                     $newfilenames.= basename($_FILES['newfile1']['name']) . '(*)';
                                }
                                if ($_FILES['newfile2']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile2"]["tmp_name"], "../assignments/" . basename($_FILES['newfile2']['name']));
                                     $newfilenames.= basename($_FILES['newfile2']['name']) . '(*)';
                                }
                                if ($_FILES['newfile3']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile3"]["tmp_name"], "../assignments/" . basename($_FILES['newfile3']['name']));
                                     $newfilenames.= basename($_FILES['newfile3']['name']) . '(*)';
                                }

                                $newblogcontent = $newblogcontent . '(*)' . 'Written by: ' . $blogger . ' on ' . $date . '(*)' . $timestamp . '.' . $targettype . '(*)' . $newfilenames . '*****END_OF_ENTRY*****';
                        }
			else {
                                $newfilenames= '';
                                if ($_FILES['newfile1']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile1"]["tmp_name"], "../assignments/" . basename($_FILES['newfile1']['name']));
                                     $newfilenames.= basename($_FILES['newfile1']['name']) . '(*)';
                                }
                                if ($_FILES['newfile2']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile2"]["tmp_name"], "../assignments/" . basename($_FILES['newfile2']['name']));
                                     $newfilenames.= basename($_FILES['newfile2']['name']) . '(*)';
                                }
                                if ($_FILES['newfile3']['size'] > 1000){
                                     move_uploaded_file($_FILES["newfile3"]["tmp_name"], "../assignments/" . basename($_FILES['newfile3']['name']));
                                     $newfilenames.= basename($_FILES['newfile3']['name']) . '(*)';
                                }

                                $newblogcontent = $newblogcontent . '(*)' . 'Written by: ' . $blogger . ' on ' . $date . '(*)undefined(*)' . $newfilenames . '*****END_OF_ENTRY*****';
			}
			$myFile = "../assignments/cdassignments.txt";
			$fh = fopen($myFile, 'a') or die("can't open file");
			fwrite($fh, $newblogcontent);
			fclose($fh);
		}
//---------------------------------------------------Edit Blog--------------------------------------------------------------		
		if (isset($_POST['editblogcontent'])) {
                                $editfilenames = '';
                                if ($_FILES['editfile1']['size'] > 1000){
                                     move_uploaded_file($_FILES["editfile1"]["tmp_name"], "../assignments/" . basename($_FILES['editfile1']['name']));
                                     $editfilenames.= basename($_FILES['editfile1']['name']) . '(*)';
                                } else {
                                     $editfilenames.= $_POST['oldbfile1'] . '(*)';
                                }
                                if ($_FILES['editfile2']['size'] > 1000){
                                     move_uploaded_file($_FILES["editfile2"]["tmp_name"], "../assignments/" . basename($_FILES['editfile2']['name']));
                                     $editfilenames.= basename($_FILES['editfile2']['name']) . '(*)';
                                } else {
                                     $editfilenames.= $_POST['oldbfile2'] . '(*)';
                                }
                                if ($_FILES['editfile3']['size'] > 1000){
                                     move_uploaded_file($_FILES["editfile3"]["tmp_name"], "../assignments/" . basename($_FILES['editfile3']['name']));
                                     $editfilenames.= basename($_FILES['editfile3']['name']) . '(*)';
                                } else {
                                     $editfilenames.= $_POST['oldbfile3'] . '(*)';
                                }
			$editblogcontent = $_POST['editblogcontent'];
			$editpicnumber = $_POST['editpicnumber'];
			if ($_FILES['editpic']['size'] > 1000){
				if (file_exists('../assignments/' . $editpicnumber)) {
					unlink('../assignments/' . $editpicnumber);
				}
				if (file_exists('../assignments/thumbs/' . $editpicnumber)) {
					unlink('../assignments/thumbs/' . $editpicnumber);
				}
				$target =  basename($_FILES['editpic']['name']);
				$targetexplosion = explode('.',$target);
				$targettype = $targetexplosion[count($targetexplosion)-1];
				move_uploaded_file($_FILES["editpic"]["tmp_name"], "../assignments/" . $timestamp . '.' . $targettype);
				move_uploaded_file($_FILES["editpic"]["tmp_name"], "../assignments/thumbs/" . $timestamp . '.' . $targettype);
				$fname = $timestamp . '.' . $targettype;
				createThumbs("../assignments/",$fname,"../assignments/thumbs/",200);
				createThumbs("../assignments/",$fname,"../assignments/",745);
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date . '(*)' . $timestamp . '.' . $targettype . '(*)' . $editfilenames;
			} elseif($editpicnumber !== "undefined") {
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date . '(*)' . $editpicnumber . '(*)' . $editfilenames;
			} else {
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date .'(*)undefined(*)' . $editfilenames;
			}
			$editblognumber = $_POST['editblognumber'];
			$myFile = "../assignments/cdassignments.txt";
			$fh = fopen($myFile, 'r') or die("can't open file");
			$blogzor = fread($fh, filesize($myFile));
			fclose($fh);
			$blogzorExplosion = explode("*****END_OF_ENTRY*****",$blogzor);
			unset($blogzorExplosion[count($blogzorExplosion)-1]);
			$blogzorExplosion[$editblognumber] = $editblogcontent;
			$blogzor = $blogzorExplosion;
			$count = 0;
			$blogzorfinal = '';
			foreach ($blogzor as $b) {
				$blogzorfinal .= $b . '*****END_OF_ENTRY*****';
				$count+=1;
			}
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $blogzorfinal);
			fclose($fh);
		}
//--------------------------------------------Delete Blog---------------------------------------------------------------------	
		if (isset($_POST['deleteblognumber'])) {
			$deleteblognumber = $_POST['deleteblognumber'];
			$deletepicnumber = $_POST['deletepicnumber'];
			if (file_exists('../assignments/' . $deletepicnumber)) {
				unlink('../assignments/' . $deletepicnumber);
			}
			if (file_exists('../assignments/thumbs/' . $deletepicnumber)) {
				unlink('../assignments/thumbs/' . $deletepicnumber);
			}
			$myFile = "../assignments/cdassignments.txt";
			$fh = fopen($myFile, 'r') or die("can't open file");
			$blogzor = fread($fh, filesize($myFile));
			fclose($fh);
			$blogzorExplosion = explode("*****END_OF_ENTRY*****",$blogzor);
			unset($blogzorExplosion[count($blogzorExplosion)-1]);
			unset($blogzorExplosion[$deleteblognumber]);
			$blogzor = $blogzorExplosion;
			$count = 0;
			$blogzorfinal = '';
			foreach ($blogzor as $b) {
				$blogzorfinal .= $b . '*****END_OF_ENTRY*****';
				$count+=1;
			}
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $blogzorfinal);
			fclose($fh);
		}

		header('location: ../assignments.php');
	} else {
		header('location: ../assignments.php');
	}
?>	