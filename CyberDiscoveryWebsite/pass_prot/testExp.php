<?php
echo 'testExp time: ' . date('Y-m-d H:i:s');
	
//Get configuration file	
	include('pass_config.php');
	include('../txtdb/txtdb_commands.php');	
	
	insert_txtdb('../db/sessions_graveyard.txt','UNID,camp_name,camp_start,camp_end','12345678,TestCamp1,0000000,1111111'); //Add camp to sessions_graveyard.txt
?>