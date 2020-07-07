<?php
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');


?>

	<li><?php if ($_SESSION['role'] == 'user') {?><a href='#about'>About</a></li><?php } ?>

	<li><?php if ($_SESSION['role'] == 'user') {?><a href='#shop'>Buy Now</a></li><?php } ?>

	<li><?php if ($_SESSION['role'] == 'user') {?><a href='#subscribe'>Subscribe</a></li><?php } ?>
	
	<li><?php if ($_SESSION['role'] == 'user') {?><a href='changePassword.php?username=<?php echo $_SESSION['username'];?>'>Change Password</a></li><?php } ?>
	
	<li><a href='../logout.php'>Logout</a></li>

</ul>
