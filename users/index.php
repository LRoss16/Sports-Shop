<!--This page will redirect users to the appropriate page when they login
it will first check to make sure someone is actually logged in when trying to access this page
if they are not it will put them back to the homepage -->

<?php 
session_start();
require_once('../includes/configure.php');
require('../vendor/autoload.php');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	
if($_SESSION['role'] == "user" && $_SESSION['passwordchange'] == 1)  { header('Location: user/resetPassword.php'); 

} else {


if($_SESSION['role'] == "user")  { header('Location: user/index.php'); }

if($_SESSION['role'] == "admin")  { header('Location: admin/index.php'); }

}

}else {
	 header('Location: ../index.php');
}

?>