<?php

require_once('includes/configure.php');



if (isset($_POST['email'])) {

	$email = $_POST['email'];

	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$query = $dbconn->prepare('SELECT email FROM subscribe WHERE email = ?');

		$query->bindValue( 1, $email );

		$query->execute();



		if( $query->rowCount() > 0 ) { # If rows are found for query

				

		try {

			

		//Deleting a row using a prepared statement.

		$sql = 'DELETE FROM subscribe WHERE email = :email';

 

		//Prepare our DELETE statement.

		$statement = $dbconn->prepare($sql);

 

		//The make that we want to delete from our cars table.

		$emailToDelete = $email;

 

	//Bind our $makeToDelete variable to the paramater :make.

		$statement->bindValue(':email', $emailToDelete);

 

		//Execute our DELETE statement.

		$delete = $statement->execute();

	

	}catch(PDOException $e) {

			    echo $e->getMessage();

			}

			echo "You have successfully unsubscribed. Sorry to see you go!";

		}

else {



     echo "This email is not subscribed with us";	

	}

	

	} else {

		echo "Please enter a valid email...";

	}

	

}

	

	 

	 

	 ?>