<?php 
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');

if(!isset($_SESSION['loggedin'])){ //if login in session is not set
    header("Location: ../../index.php");
}

if($_SESSION['role'] !="admin") {
    header("Location: ../../index.php");
}
		try {


		$stmt = $dbconn->prepare('DELETE FROM subscribe WHERE email = :email') ;

		$stmt->execute(array(':email' => $_GET['email']));
		
		
		header('Location: index.php');

		exit;



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}







?>