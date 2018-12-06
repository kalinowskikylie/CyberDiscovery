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
				
				move_uploaded_file($_FILES["newpic"]["tmp_name"], "../blog/" . $timestamp . '.' . $targettype);
				move_uploaded_file($_FILES["newpic"]["tmp_name"], "../blog/thumbs/" . $timestamp . '.' . $targettype);
				$fname = $timestamp . '.' . $targettype;
				createThumbs("../blog/",$fname,"../blog/thumbs/",200);
				createThumbs("../blog/",$fname,"../blog/",745);
				$newblogcontent = $newblogcontent . '(*)' . 'Written by: ' . $blogger . ' on ' . $date . '(*)' . $timestamp . '.' . $targettype . '*****END_OF_ENTRY*****';
			}
			else {
				$newblogcontent = $newblogcontent . '(*)' . 'Written by: ' . $blogger . ' on ' . $date . '*****END_OF_ENTRY*****';
			}
			$myFile = "../blog/cdblog.txt";
			$fh = fopen($myFile, 'a') or die("can't open file");
			fwrite($fh, $newblogcontent);
			fclose($fh);
		}
//---------------------------------------------------Edit Blog--------------------------------------------------------------		
		if (isset($_POST['editblogcontent'])) {
			$editblogcontent = $_POST['editblogcontent'];
			$editpicnumber = $_POST['editpicnumber'];
			if ($_FILES['editpic']['size'] > 1000){
				if (file_exists('../blog/' . $editpicnumber)) {
					unlink('../blog/' . $editpicnumber);
				}
				if (file_exists('../blog/thumbs/' . $editpicnumber)) {
					unlink('../blog/thumbs/' . $editpicnumber);
				}
				$target =  basename($_FILES['editpic']['name']);
				$targetexplosion = explode('.',$target);
				$targettype = $targetexplosion[count($targetexplosion)-1];
				move_uploaded_file($_FILES["editpic"]["tmp_name"], "../blog/" . $timestamp . '.' . $targettype);
				move_uploaded_file($_FILES["editpic"]["tmp_name"], "../blog/thumbs/" . $timestamp . '.' . $targettype);
				$fname = $timestamp . '.' . $targettype;
				createThumbs("../blog/",$fname,"../blog/thumbs/",200);
				createThumbs("../blog/",$fname,"../blog/",745);
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date . '(*)' . $timestamp . '.' . $targettype;
			} elseif($editpicnumber !== "undefined") {
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date . '(*)' . $editpicnumber;
			} else {
				$editblogcontent = $editblogcontent . '(*)' . 'Last edited by: ' . $blogger . ' on ' . $date;
			}
			$editblognumber = $_POST['editblognumber'];
			$myFile = "../blog/cdblog.txt";
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
			if (file_exists('../blog/' . $deletepicnumber)) {
				unlink('../blog/' . $deletepicnumber);
			}
			if (file_exists('../blog/thumbs/' . $deletepicnumber)) {
				unlink('../blog/thumbs/' . $deletepicnumber);
			}
			$myFile = "../blog/cdblog.txt";
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

		header('location: ../index.php');
	} else {
		header('location: ../index.php');
	}
?>	