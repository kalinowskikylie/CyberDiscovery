<?php
	session_start();
    if(!($_SESSION['permissions'] >= 90)){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: pass_prot/login.php?error=perm');
		exit;
	}
	if(isset($_POST['edithead'])) {
					$editheadcontent = $_POST['editheadcontent'];
					$edithead = $_POST['edithead'];
					$edithead = str_replace('cell','',$edithead);
		
		$myFile = "tableheaders.txt";
		$fh = fopen($myFile, 'r');
		$headers = fread($fh, filesize($myFile));
		fclose($fh);

		$headersPieces = explode('(*)',$headers);
					$headersPieces[$edithead] = $editheadcontent;

		
		unset($writeheaders);
		
		foreach ($headersPieces as $h) {
			$writeheaders .= $h . '(*)';
		}
		$writeheaders = substr_replace($writeheaders,"",-3);
		
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $writeheaders);
		fclose($fh);
	}
?>