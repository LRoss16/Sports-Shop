<!-- this page will delete a product from the stock table -->
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
			

		$stmt = $dbconn->prepare('DELETE FROM stock WHERE product = :product') ;

		$stmt->execute(array(':product' => $_GET['product']));
		
		
		header('Location: index.php');
		exit;



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}







?>