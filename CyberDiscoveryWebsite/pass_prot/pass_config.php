<?php
//Edit these variables to reflect your website
	$web_domain = 'http://cyberdiscovery.latech.edu'; //Domain of topmost page of website
	$domain_address = 'http://cyberdiscovery.latech.edu';
	$website_name = 'CyberDiscovery'; //name of website
	$admin_email = 'htims@latech.edu'; //email address of person to approve users
	$website_main = "/index.php"; //Address of homepage
	$pageSetupIncludes ='../pagesetup.php'; //I store all of my repeated code in a php file and include it on all pages to create consistent headers and nav. This mimicks that style onto the pass_prot pages. Also there is a variable created called $webPage that holds the login, admin, and logout for the respective pages. This allows for custom styling on a specific page
	$path_to_pass_folder = '/'; //Folder pass_prot is in. please do NOT include trailing slash
	$session_prefix = 'cyberdisc'; //Prefix added to session values to ensure unique session names
?>
