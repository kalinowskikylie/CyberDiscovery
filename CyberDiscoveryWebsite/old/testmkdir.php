<?php
	if(!mkdir("testfolder", 0777)){
		echo('Epic Fail! Couldn\'t create folder!');
		unlink("testfolder/testtext.txt");
		rmdir("testfolder");
	} else {
		if(!($handle = fopen("testfolder/testtext.txt", "x"))){
			echo('Epic Fail! Couldn\'t create file!');
		} else {
			fwrite($handle, "This is some sample text to test the file writing system.");
			fclose($handle);
		}
	}
?>