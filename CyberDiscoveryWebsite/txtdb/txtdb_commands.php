<?php
//Retrieves Data from designated database then sorts it into arrays. Returns TXTDB object
function select_txtdb($TXTDBURL){  //$TXTDB is the url of the database to be queried.
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
				//Convert &cellsepar; and &cellsepar; back into their entities.
					$cell = str_replace('&cellsepar;','(*)',$cell);
					$cell = str_replace('&rowsepar;','|!|',$cell);
					$cell = str_replace('&commasepar;',',',$cell);
					$TXTDB[$counter][$TXTDBheaders[$cellkey]] = $cell; //Put each cell in its correct place in the arraydb. db>rowbyUNID>header>cell
				}
				$counter++;
			}
		}
		if(!is_array($TXTDB)){ //Check if TXTDB is not an array. This will be the case if there are only headers, no content in the database
			return false;
		}
	} else {
		//Error Handling (File does not exist or permissions are too low.)
		//check if file exists
		if(file_exists($TXTDBURL)){
			//Since the file exists then the error must be permissions based
			echo('The file you have designated as a database,'. $TXTDBURL .', is unreadable. Check that permissions are set correctly.');
		} else {
			//The file does not exist at the designated location
			echo('The file you have designated as a database,'. $TXTDBURL .', either does not exist or is not in the location designated. Check that the file exists and is located here.');
		}
	}	
	return $TXTDB;
}

//Select information from TXTDB object
function query_txtdb($TXTDBobject, $TXTDBcolumn = '*', $TXTDBwhered = '', $TXTDBorder = ''){
if(is_array($TXTDBobject)){
	//WHERE
	$TXTDBwhere = explode(',',$TXTDBwhered); // Seperate header and value for where
	$counter = 0;
	unset($TXTDB);
	foreach($TXTDBobject as $key => $row){ //This for each goes through every row and selects the rows that satisfy the where logic
		if(($row[$TXTDBwhere[0]] == $TXTDBwhere[1]) || $TXTDBwhere[0] == ''){
			$TXTDB[$counter] = $row;
			$counter++;
		}
	}
	if($TXTDB[0] == ''){
		return false;
	}
	
	//ORDER
	if(!($TXTDBorder == '')){
		$TXTDBorder = explode(',',$TXTDBorder); //Explode order listing by commas to check for ascend.
		foreach($TXTDB as $key => $row){	//Go through current database(after where) and make a temporary array of key-value pairings of the chosen order column
			$tempOrderArray[$key] = $row[$TXTDBorder[0]];
		}
		natcasesort($tempOrderArray);	//Sort temporary array containing only desired ordered column
		if($TXTDBorder[1] == 'ascend'){	//If order is supposed to be ascending, reverse array.
			$tempOrderArray = array_reverse($tempOrderArray,true);
		}
		$counter = 0;
		foreach($tempOrderArray as $key => $placeholder){
			$TXTDBordered[$counter] = $TXTDB[$key];
			$counter++;
		}
	} else {
		$TXTDBordered = $TXTDB;
	}
	
	//COLUMN
	$TXTDBcolumn = explode(',',$TXTDBcolumn);	//Make an array of all selected columns
	if(!($TXTDBcolumn[0] == '*')){	//narrows column selection if not a wildcard
		foreach($TXTDBordered as $key => $row){	//Go through each row
			foreach($TXTDBcolumn as $column){				//Go through each column choice
				if(array_key_exists($column,$row)){
					$TXTDBnew[$key][$column] = $TXTDBordered[$key][$column];
				}
			}
		}
	} else {
		$TXTDBnew = $TXTDBordered;
	}
	return $TXTDBnew;
}
}

function build_txtdb($TXTDBobjectSel){	
	//create html output from arrays
	$TXTDBtable = "<table class=\"tableTXTDB\">\n<tr class=\"headerTXTDB\">\n";
	foreach($TXTDBobjectSel[1] as $header =>$cell){
		$TXTDBtable .="<td>".$header."</td>\n";
	}
	$TXTDBtable .="</tr>\n";
	foreach($TXTDBobjectSel as $rownumber =>$rowcontent){
		if($rownumber%2 ==0){
			$TXTDBtable .="<tr class=\"evenrowTXTDB\">\n";
		} else {
			$TXTDBtable .="<tr class=\"oddrowTXTDB\">\n";
		}
		foreach($rowcontent as $header => $cell){
			$TXTDBtable .="<td>".$cell."</td>\n";
		}
		$TXTDBtable .="</tr>\n";
	}
	$TXTDBtable .="</table>";
	return $TXTDBtable;
}

function clean_txtdb($input){
	//clean data to be inserted into TXTDB (removes commas)
	return str_replace(',','&commasepar;',$input);
}
//---------------------------CRUD COMMANDS----------------------------------------------------------------

//Add an entry to a database
function insert_txtdb($TXTDBURL,$TXTDBcolumnName, $TXTDBvalues){
	//clean given values
	//Sanatize string before input into txtdb. (html and txtdb entities are scrubbed)
	$TXTDBvalues = str_replace('(*)','&cellsepar;',$TXTDBvalues);
	$TXTDBvalues = str_replace('|!|','&rowsepar;',$TXTDBvalues);

	//Open file, Retrieve data from file, then close file.
	$TXTDBhandle = fopen($TXTDBURL,r);
	$TXTDBcontents = fread($TXTDBhandle, filesize($TXTDBURL));
	fclose($TXTDBhandle);
	
	//Parse database contents into arrays
	$TXTDBrows = explode('|!|', $TXTDBcontents);	//Break content into rows
	$TXTDBheaders = explode('(*)', $TXTDBrows[0]);	//First row is the headers, so get their values
	
	//Start writing $TXTDBnewrow to be inserted into the database 
	$TXTDBnewrow = "";
	
	//Pair parameter headers and values as arrays
	$TXTDBcolumnName = explode(',',$TXTDBcolumnName);
	$TXTDBvalues = explode(',',$TXTDBvalues);
	
	//Loop through headers, creating a row with the corresponding values to headers
	foreach($TXTDBheaders as $header){
		if(in_array($header, $TXTDBcolumnName)){
			$tempvaluekey = array_search($header, $TXTDBcolumnName);
			$TXTDBnewrow .=  $TXTDBvalues[$tempvaluekey]."(*)";
			unset($tempvaluekey);
		} else {
			$TXTDBnewrow .= "(*)";
		}
	}
	
	//Clear final (*) which is not part of the protocol for TXTDB and add |!|
	$TXTDBnewrow = substr($TXTDBnewrow, 0, -3);
	$TXTDBnewrow .= "|!|";
//	echo 'insert value: ' . $TXTDBnewrow . '<br/>';
//	echo 'fopen handle: ' . $TXTDBhandle;
	if(!($TXTDBnewrow == '' || $TXTDBnewrow == '|!|' || $TXTDBnewrow == '|!|(*)')){	//Check that row to be uploaded is not completely empty.
		//Add new row to end of database
		$TXTDBhandle = fopen($TXTDBURL,a);
		fwrite($TXTDBhandle,$TXTDBnewrow);
		fclose($TXTDBhandle);
	}
	chmod($TXTDBURL,0770);
}

//Update rows in database
function update_txtdb($TXTDBURL,$TXTDBwhere,$TXTDBcolumnName,$TXTDBvalues){
	//clean given values
	//Sanatize string before input into txtdb. (html and txtdb entities are scrubbed)
	$TXTDBvalues = str_replace('(*)','&cellsepar;',$TXTDBvalues);
	$TXTDBvalues = str_replace('|!|','&rowsepar;',$TXTDBvalues);
	
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
		if(!is_array($TXTDB)){ //Check if TXTDB is not an array. This will be the case if there are only headers, no content in the database
			return false;
		}
	} else {
		//Error Handling (File does not exist or permissions are too low.)
		echo('The file you have designated as a database,'. $TXTDBURL .', either does not exist or is unreadable. Check that the file exists and that permissions are set correctly.');
	}
	//WHERE
	$TXTDBwhere = explode(',',$TXTDBwhere); // Seperate header and value for where
	foreach($TXTDB as $key => $row){ //This for each goes through every row and selects the rows that satisfy the where logic
		if(($row[$TXTDBwhere[0]] == $TXTDBwhere[1]) || $TXTDBwhere[0] == ''){
			$TXTDBfiltered[$key] = $row;
		}
	}
	
	//Pair parameter headers and values as arrays
	$TXTDBcolumnName = explode(',',$TXTDBcolumnName);
	$TXTDBvalues = explode(',',$TXTDBvalues);
	
	//Loop through rows then headers, updating a row with the corresponding values to headers
	foreach($TXTDBfiltered as $row => $rowcontent){
		foreach($rowcontent as $header => $cellvalue){
			if(in_array($header, $TXTDBcolumnName)){
				$tempvaluekey = array_search($header, $TXTDBcolumnName);
				$TXTDBfiltered[$row][$header]=  $TXTDBvalues[$tempvaluekey];
				unset($tempvaluekey);
			}
		}
	}
	
	//Combine $TXTDBfiltered and $TXTDB
	foreach($TXTDBfiltered as $rownumber => $rowcontent){
		$TXTDB[$rownumber] = $rowcontent;
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
	$templocation = str_replace('.txt','_temp.txt',$TXTDBURL);
	$TXTDBhandle = fopen($templocation,'w');
	fwrite($TXTDBhandle,$TXTDBupdatedtable);
	while(!(filesize($templocation) > 0)){
		fwrite($TXTDBhandle,$TXTDBupdatedtable);
	}
	fclose($TXTDBhandle);
	while(!(rename($templocation,$TXTDBURL))){
	}
	chmod($TXTDBURL,0770);
}

//Delete rows in database
function delete_txtdb($TXTDBURL,$TXTDBwhered){
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
					$TXTDB[$counter][$TXTDBheaders[$cellkey]] = $cell; //Put each cell in its correct place in the arraydb. db>row>header>cell
				}
				$counter++;
			}
		}
		if(!is_array($TXTDB)){ //Check if TXTDB is not an array. This will be the case if there are only headers, no content in the database
			return false;
		}
	} else {
		//Error Handling (File does not exist or permissions are too low.)
		echo('The file you have designated as a database,'. $TXTDBURL .', either does not exist or is unreadable. Check that the file exists and that permissions are set correctly.');
	}
	//WHERE
	$TXTDBwhere = explode(',',$TXTDBwhered); // Seperate header and value for where
	foreach($TXTDB as $key => $row){ //This for each goes through every row and selects the rows that satisfy the where logic then deletes it
		if(($row[$TXTDBwhere[0]] == $TXTDBwhere[1])){
			unset($TXTDB[$key]);
		}
	}
	
	//Turn array back into string with TXTDB protocol
	$TXTDBupdatedtable = '';
	foreach($TXTDBheaders as $header){	//Write headers into updated table
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
	$templocation = str_replace('.txt','_temp.txt',$TXTDBURL);
	$TXTDBhandle = fopen($templocation,'w');
	fwrite($TXTDBhandle,$TXTDBupdatedtable);
	while(!(filesize($templocation) > 0)){
		fwrite($TXTDBhandle,$TXTDBupdatedtable);
	}
	fclose($TXTDBhandle);
	while(!(rename($templocation,$TXTDBURL))){
	}
	chmod($TXTDBURL,0770);
}
?>