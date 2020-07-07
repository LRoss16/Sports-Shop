<!--This page will allow the user to reset their password. 
-->
<?php
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');

if(!isset($_SESSION['loggedin'])){ //if login in session is not set
    header("Location: ../../index.php");
}

if($_SESSION['role'] =="admin") {
    header("Location: ../admin/index.php");
}

?>

<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Change Password</title>
   <meta http-equiv="refresh" content="900;url=../logout.php"/><!--Log out after 15 minuts of inactivity -->

 <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  
  <style>
  .edit {
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

			$error[] = 'Please enter your username.';

		}
		
		if($email ==''){

			$error[] = 'Please enter your email.';

		}
		
		if($currentPassword ==''){

			$error[] = 'Please enter your current password.';

		}

		if($password ==''){

			$error[] = 'Please enter your new password.';

		}
		
		if($confirmPassword ==''){

			$error[] = 'Please confirm the new password.';

		}
		
		if($password != $confirmPassword){

			$error[] = 'Passwords do not match.';

		}
		
				
		if(!isset($error)){
			
			if (password_verify ($currentPassword, $oldPassword)) { 
			
		 $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
		 
		 	$stmt = $dbconn->prepare('UPDATE users SET username = :username, password = :password WHERE username = :username') ;

			$stmt->execute(array(

						':username' => $username,

						':password' => $hashedpassword

					));
					
												

				header("Location: index.php");

				exit;

			} else {
           echo "password is incorrect";					
						
	}

  }
  }
  
  			//check for any errors

	if(isset($error)){

		foreach($error as $error){

			echo '<p class="error" align="center">'.$error.'</p>';

		}

	}
	
			try {



			$stmt = $dbconn->prepare('SELECT userID, username, email, password FROM users WHERE username = :username') ;

			$stmt->execute(array(':username' => $_GET['username']));

			$row = $stmt->fetch(); 



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}
	
	
	?>
  
  <div class = "edit">

<form action='' method='post' align = "center">
         <h2>Change Password</h2>
		 <p><a href ="index.php">Return to Account</a></p>
		 
		 <input type='hidden' name='oldPassword' value='<?php echo $row['password'];?>'>     


		<p><label>Username</label><br />

		<input type='text' name='username' readonly value='<?php echo $row['username'];?>'></p>
		
		<p><label>Email</label><br />

		<input type='email' name='email' readonly value='<?php echo $row['email'];?>'></p>
		
		<p><label>Current Password</label><br />

		<input type='password' name='currentPassword' required> </p>

		<p><label>Password</label><br />

		<input type='password' name='password' required> </p>
		
		<p><label>Confirm Password</label><br />

		<input type='password' name='confirmPassword' required> </p>


		<p><input type='submit' name='submit' value='Complete Changes'></p>
		

	</form>
  </div>

</body>

</html>
