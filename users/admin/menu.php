<?php
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');


?>

	<li><?php if ($_SESSION['username'] == 'admin') {?><a href='#users'>Users</a></li><?php } ?>

	<li><?php if ($_SESSION['username'] == 'admin') {?><a href='#stock'>Stock</a></li><?php } ?>

	<li><?php if ($_SESSION['username'] == 'admin') {?><a href='#subscribers'>Subscribers</a></li><?php } ?>

	<li><a href="../../../index.php" target="_blank">View Website</a></li>

	<li><a href='../logout.php'>Logout</a></li>

</ul>
