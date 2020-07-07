<?php 

require_once('../includes/configure.php');
require('../vendor/autoload.php');

	session_destroy();

header('Location: ../login.php');

?>