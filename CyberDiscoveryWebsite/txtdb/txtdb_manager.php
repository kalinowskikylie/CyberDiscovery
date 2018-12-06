<?php
function create_txtdb($TXTDBURL,$TXTDBcolumnName){
	//Start writing $TXTDBnewrow to be inserted into the database 
	$TXTDBnewrow = "";
	
	//make parameter headers into an array
	$TXTDBcolumnName = explode(',',$TXTDBcolumnName);
	
	//Loop through headers, creating a row with the corresponding values to headers
	foreach($TXTDBcolumnName as $header){
			$TXTDBnewrow .=  $header."(*)";
	}
	
	//Clear final (*) which is not part of the protocol for TXTDB and add |!|
	$TXTDBnewrow = substr($TXTDBnewrow, 0, -3);
	$TXTDBnewrow .= "|!|";
	
	if(!($TXTDBnewrow == '' || $TXTDBnewrow == '|!|' || $TXTDBnewrow == '|!|(*)')){	//Check that row to be uploaded is not completely empty.
		//Add new row to end of database
		$TXTDBhandle = fopen($TXTDBURL,a);
		fwrite($TXTDBhandle,$TXTDBnewrow);
		fclose($TXTDBhandle);
	}
	
	
	//get file name and path from provided URL
	if(strpos($TXTDBURL,'/')){
		$TXTDBpath = substr($TXTDBURL,0,strrpos($TXTDBURL,'/')+1);
		$TXTDBfilename = substr($TXTDBURL,strrpos($TXTDBURL,'/')+1);
	}	else {
		$TXTDBpath = '';
		$TXTDBfilename = $TXTDBURL;
	}
	//Add htacess file to secure file on server
	$TXTDBhtaccess = "<Files ".$TXTDBfilename.">\norder allow,deny\ndeny from all\nallow from 127.0.0.1\n</Files>";
	$TXTDBhthandle = fopen($TXTDBpath.'.htaccess',a);
		fwrite($TXTDBhthandle,$TXTDBhtaccess);
		fclose($TXTDBhthandle);
}

function delete_txtdb($TXTDBURL){
	//get file name and path from provided URL
	if(strpos($TXTDBURL,'/')){
		$TXTDBpath = substr($TXTDBURL,0,strrpos($TXTDBURL,'/')+1);
		$TXTDBfilename = substr($TXTDBURL,strrpos($TXTDBURL,'/')+1);
	}	else {
		$TXTDBpath = '';
		$TXTDBfilename = $TXTDBURL;
	}
	
	//Delete file and its .htaccess file
	if(file_exists($TXTDBURL)){
		unlink($TXTDBURL);
	} else {
		echo('File, '.$TXTDBURL.', does not exist!');
	}
	if(file_exists($TXTDBpath . '.htaccess')){
		unlink($TXTDBpath . '.htaccess');
	}
}

function addColumn_txtdb($TXTDBURL,$TXTDBcolumnName){
	if(is_readable($TXTDBURL)){
		//Open file, Retrieve data from file, then close file.
		$TXTDBhandle = fopen($TXTDBURL,r);
		$TXTDBcontents = fread($TXTDBhandle, filesize($TXTDBURL));
		fclose($TXTDBhandle);
		
		//Parse database contents into arrays
		$TXTDBrows = explode('|!|', $TXTDBcontents);	//Break content into rows
		if($TXTDBrows[count($TXTDBrows)-1] == ''){
			unset($TXTDBrows[count($TXTDBrows)-1]);			//Delete final row which should be null
		}
		
		//add header title to first row (the row that contains all the column names) and blank cells to subsequent rows
		if(isset($TXTDBnewrows)){
			unset($TXTDBnewrows);
		}
		foreach($TXTDBrows as $key => $row){
			if($key == 0){
				$TXTDBnewrows[$key] = $row . "(*)" . $TXTDBcolumnName;
			} else {
				$TXTDBnewrows[$key] = $row . "(*)";
			}
		}
		
		//read array into a string to be inserted back into the database
		$TXTDBinsertColumn = '';
		foreach($TXTDBnewrows as $row){
			$TXTDBinsertColumn .= $row . "|!|";
		}
		
		//upload string to temp text file then checks that it uploaded correctly. If so, attempts to rename temp file as perm file until it works. (This is to fix problem where occasionally the server wipes the db without uploading the new content to it.)
		$TXTDBhandle = fopen('temp' . $TXTDBURL,'w+');
		fwrite($TXTDBhandle,$TXTDBinsertColumn);
		while(!(filesize('temp' . $TXTDBURL) > 0)){
			fwrite($TXTDBhandle,$TXTDBinsertColumn);
		}
		fclose($TXTDBhandle);
		while(!(rename('temp' . $TXTDBURL,$TXTDBURL))){
		}
		
	} else {
		//Error Handling (File does not exist or permissions are too low.)
		echo('The file you have designated as a database,'. $TXTDBURL .', either does not exist or is unreadable. Check that the file exists and that permissions are set correctly.');
	}
}

function deleteColumn_txtdb($TXTDBURL,$TXTDBcolumnName){
	if(is_readable($TXTDBURL)){
		//Open file, Retrieve data from file, then close file.
		$TXTDBhandle = fopen($TXTDBURL,r);
		$TXTDBcontents = fread($TXTDBhandle, filesize($TXTDBURL));
		fclose($TXTDBhandle);
		
		//Parse database contents into arrays
		$TXTDBrows = explode('|!|', $TXTDBcontents);	//Break content into rows
		if($TXTDBrows[count($TXTDBrows)-1] == ''){
			unset($TXTDBrows[count($TXTDBrows)-1]);			//Delete final row which should be null
		}
		$TXTDBheaders = explode('(*)', $TXTDBrows[0]);	//First row is the headers, so get their values
		$counter = 0;
		foreach($TXTDBrows as $key => $row){
			if(!($key == 0)){		//Exclude the header row from being processed since we've already processed it seperately.
				$rowpieces = explode('(*)', $row);
				foreach($rowpieces as $cellkey => $cell){
					$TXTDB[$counter][$TXTDBheaders[$cellkey]] = $cell; //Put each cell in its correct place in the arraydb. db>rowbyUNID>header>cell
				}
				$counter++;
			}
		}
				
		//Go through database and delete selected column
		foreach($TXTDB as $key => $row){
			foreach($row as $header => $cell){
				if($header == $TXTDBcolumnName){
					unset($TXTDB[$key][$header]);
				}
			}
		}
		
		//Turn array back into string with TXTDB protocol
		$TXTDBupdatedtable = '';
		foreach($TXTDB[0] as $header =>$placeholder){	//Write headers into updated table
			$TXTDBupdatedtable .= $header . '(*)';
		}
		//Clear final (*) which is not part of the protocol for TXTDB and add |!|
		$TXTDBupdatedtable = substr($TXTDBupdatedtable, 0, -3);
		$TXTDBupdatedtable .= "|!|";
		foreach($TXTDB as $rowkey =>$rowcontent){
			foreach($rowcontent as $header =>$cellvalue){
				$TXTDBupdatedtable .= $cellvalue . '(*)';
			}
			//Clear final (*) which is not part of the protocol for TXTDB and add |!|
			$TXTDBupdatedtable = substr($TXTDBupdatedtable, 0, -3);
			$TXTDBupdatedtable .= "|!|";
		}
		
		//upload string to temp text file then checks that it uploaded correctly. If so, attempts to rename temp file as perm file until it works. (This is to fix problem where occasionally the server wipes the db without uploading the new content to it.)
		$TXTDBhandle = fopen('temp' . $TXTDBURL,'w+');
		fwrite($TXTDBhandle,$TXTDBupdatedtable);
		while(!(filesize('temp' . $TXTDBURL) > 0)){
			fwrite($TXTDBhandle,$TXTDBupdatedtable);
		}
		fclose($TXTDBhandle);
		while(!(rename('temp' . $TXTDBURL,$TXTDBURL))){
		}
		
	} else {
		//Error Handling (File does not exist or permissions are too low.)
		echo('The file you have designated as a database,'. $TXTDBURL .', either does not exist or is unreadable. Check that the file exists and that permissions are set correctly.');
	}
}
?>

<?php
?>