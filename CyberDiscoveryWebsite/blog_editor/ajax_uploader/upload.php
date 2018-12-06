<?php
	if($_GET['referer'] == 'index'){
		$uploaddir = '../../db/home_uploads/';
	}
	if($_GET['referer'] == 'blog'){
		$uploaddir = '../../camps/'. $_GET['camp'] .'/blog_uploads/';
	}
	if($_GET['referer'] == 'assignments'){
		$uploaddir = '../../camps/'. $_GET['camp'] .'/assignments_uploads/';
	}
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	  echo "success";
	} else {
	  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
	  // Otherwise onSubmit event will not be fired
	  echo "error"; 
	}
?>