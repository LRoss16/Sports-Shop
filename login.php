<!--This page will allow users to login to their account
The site will check they have an account and that the password entered is correct
-->
<?php
session_start();
require_once('includes/configure.php');
require('vendor/autoload.php');

?>

<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Login</title>
  
  </head>
  
  <style>
  .login {
padding-top: 50px;
}

.error {
	padding: 0.75em;
	margin: 0.75em;
	border: 1px solid #990000;
	max-width: 400px;
	color: #990000;
	background-color: #FDF0EB;
	-moz-border-radius: 0.5em;
	-webkit-border-radius: 0.5em;
}
</style>
  
  <body>
  
  <?php 
  
  if(isset($_POST['submit'])){



		//collect form data

		extract($_POST);

		

		if($username ==''){

			$error[] = 'Please enter the username you would like.';

		}

		if($password ==''){

			$error[] = 'Please enter your password.';

		}
		
		if(!isset($error)){
						
			$query = $dbconn->prepare('SELECT username FROM users WHERE username = ?');

			$query->bindValue( 1, $username );

			$query->execute();



			if( $query->rowCount() == 0 ) { # If rows are found for query

			echo "There is no account with this username<br>";

		} else {
			

			$stmt = $dbconn->prepare('SELECT name, password, email, role, money, passwordchange FROM users WHERE username = :username') ;

			$stmt->execute(array(':username' => $username));

			$row = $stmt->fetch(); 

           $userPassword = $row['password'];
	     if (password_verify ($password, $userPassword)) {
			 
			 //assign session variables
			 $_SESSION['loggedin'] = true;
			 $_SESSION['username'] = $username;
			 $_SESSION['name'] = $row['name'];
		     $_SESSION['email'] = $row['email'];
			 $_SESSION['role'] = $row['role'];
			 $_SESSION['money'] = $row['money'];
			 $_SESSION['passwordchange'] = $row['passwordchange'];
		 
		   	header('Location: users/index.php');

				exit;
				
		 } else {
			 echo "password is incorrect";

		 }
		 


		}		
						
						
	}

  }
  
  
  			//check for any errors

	if(isset($error)){

		foreach($error as $error){

			echo '<p class="error" align="center">'.$error.'</p>';

		}

	}
	
	
	?>
  
  <div class = "login">

<form action='' method='post' align = "center">
         <h2>Login to your account</h2>
		 <p><a href ="index.php">Return to website</a></p>

		<p><label>Username</label><br />

		<input type='text' name='username' required ></p>

		<p><label>Password</label><br />

		<input type='password' name='password' required> </p>


		<p><input type='submit' name='submit' value='Login'></p>
		
		<p>New? <a href="register.php">Create an account</a></p>

	</form>
  </div>

</body>

</html>
