<link rel="stylesheet" type="text/css" media="screen,print" href="http://cyberdiscovery.latech.edu/main.css" /> 
</head>

<body>
     <div id="header_bg">
     </div>
     <div id="line_bg">
     </div>
     <div id="underline_bg">
     </div>
    
     <div id="page">
     <div id="wrapper">
     <div id="header">
          <img style="position:absolute;" src="http://cyberdiscovery.latech.edu/images/logo.png" alt="logo" height="99px"/>     
          <center>
          <h1>Cyber Discovery: <br> A GenCyber Camp</h1>
          </center>
     </div>

     <div id="content">
          <center>
          <div id="nav">
               <a href="http://cyberdiscovery.latech.edu/index.php">Home</a>|
			   <a href="http://cyberdiscovery.latech.edu/blog.php">Newsfeed</a>|
               <a href="http://cyberdiscovery.latech.edu/assignments.php">Activities</a>|
               <?php if(($_SESSION[$session_prefix .'permissions'] == '10') || ($_SESSION[$session_prefix .'permissions'] >= '60')){ ?>
					<a href="http://cyberdiscovery.latech.edu/submissions.php">Submissions</a>|
			   <?php } ?>
               <!--<a href="http://cyberdiscovery.latech.edu/account.php">Account</a>|-->
               <?php if($_SESSION[$session_prefix .'permissions'] >= '90'){ ?>
					<a href="http://cyberdiscovery.latech.edu/archives.php">Archives</a>|
			   <?php } ?>
               <?php if($_SESSION[$session_prefix .'permissions'] >= '80'){ ?>
					<a href="http://cyberdiscovery.latech.edu/pass_prot/admin.php">Admin</a>|
			   <?php } ?>
			   <?php if($_SESSION[$session_prefix .'permissions'] >= '10'){ ?>
					<a href="http://cyberdiscovery.latech.edu/pass_prot/logout.php">Logout</a>
			   <?php } else { ?>
               		<a href="http://cyberdiscovery.latech.edu/pass_prot/login.php">Login</a>
               <?php } ?>
          </div>
          </center>
