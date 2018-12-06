<?php
session_start();
session_destroy();
header('Location: http://cyberdiscovery.latech.edu/old/index.php?logout=success');
?>
