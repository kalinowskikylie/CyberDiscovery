<link rel="stylesheet" type="text/css" href="http://cyberdiscovery.latech.edu/cyberscience/cyber.css" />
</head>
<body>
	<div class="header">
		<div style="float:right;">
		<?php
			if (!($_SESSION['permissions'] > 1)){
				echo('<a href="/cyberdiscovery/cyberscience/pass_prot/login.php">Login</a>');
			} else {
				echo('Hello, '. $_SESSION['fName'] .'. <a href="/cyberdiscovery/cyberscience/pass_prot/logout.php">Logout?</a>');
			}
		?>
		</div>
		<div class="titler">Cyber Curriculum</div>
		<div style="width:5px;float:left;"></div>
		<div class="nav" <?php if($webPage !== 'index'){?>style="background-color:#CCC;"<?php }?>><a href="/cyberdiscovery/cyberscience/index.php">View Files</a></div> 
		<?php if($_SESSION['permissions'] >= 70){?><div class="nav" <?php if($webPage !== 'upload'){?>style="background-color:#CCC;"<?php }?>><a href="/cyberdiscovery/cyberscience/upload.php">Upload Files</a></div><?php }?>
		<?php if($_SESSION['permissions'] >= 90){?><div class="nav" <?php if($webPage !== 'admin'){?>style="background-color:#CCC;"<?php }?>><a href="/cyberdiscovery/cyberscience/pass_prot/admin.php">Admin</a></div><?php }?>
		<div style="clear:both;"></div>
	</div>