<!--This page will allow the admin to create an account for customers. They will be given a £50 welcome bonus that they can spend
and they will recive an email welcoming them 
Before creating an account it will check whether the email address or username has already been used-->
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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../mail/Exception.php';
require '../../mail/PHPMailer.php';
require '../../mail/SMTP.php';

?>


<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Register</title>
  <meta http-equiv="refresh" content="900;url=../logout.php"/><!--Log out after 15 minuts of inactivity -->

 <meta name="viewport" content="width=device-width, initial-scale=1">
  
  </head>
  
  <style>
  .register {
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

	//if form has been submitted process it

	if(isset($_POST['submit'])){



		//collect form data

		extract($_POST);

		
                       if($name==''){

			$error[] = 'Please enter your name.';

		}



		if($username ==''){

			$error[] = 'Please enter the username you would like.';

		}

		if($email==''){

			$error[] = 'Please enter your email address.';

		}


		if($password ==''){

			$error[] = 'Please enter your password.';

		}



		if($passwordConfirm ==''){

			$error[] = 'Please confirm the password.';

		}



		if($password != $passwordConfirm){

			$error[] = 'Passwords do not match.';

		}
		
		$money = 50;
		
				if(!isset($error)){



			$hashedpassword = password_hash($password, PASSWORD_BCRYPT);

			

			$query = $dbconn->prepare('SELECT username FROM users WHERE username = ?');

			$query->bindValue( 1, $username );

			$query->execute();



			if( $query->rowCount() > 0 ) { # If rows are found for query

			echo "This username already exists<br>";

		}


			$query = $dbconn->prepare('SELECT email FROM users WHERE email = ?');

			$query->bindValue( 1, $email );

			$query->execute();



			if( $query->rowCount() > 0 ) { # If rows are found for query

			echo "An account has already been made using this email address<br>";

			}



	else {



			try {



				//insert into database

  
				$stmt = $dbconn->prepare('INSERT INTO users (username,name,password,email,role, money, passwordchange) VALUES (:username, :name, :password, :email, :role, :money, :passwordchange)') ;

				$stmt->execute(array(

					':username' => $username,

					':name' => $name,
					
					':password' => $password,
					
					':email' => $email,

					':role' => "user",
					
					':money' => $money,
					
					':passwordchange' => 1

                  

				));

			
			//email account details to user

              $message  = "<html><body>";


   
$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
   
$message .= "<tr><td>";
   
$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
    
$message .= "<thead>
  <tr height='80'>
  <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#000000; font-size: 27px;' >Your Account</th>

  </tr>
             </thead>";
    
$message .= "<tbody>
       <tr height='80'>
       <td colspan='4' align='center' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-size: 18px; '>
       <label>Here is your account details:</label>
	   <label>Username: ".$username."</label> <BR />
	   <label>Credit: ".$money."</label> <BR />
	   <label>Your new password is: ". $_POST['password']."</label> <BR />
	   <label>You will need to change your password when you first login</label> <BR />
	   <label>Any issues do not hesitate to get in touch</label>


</td>
</tr>


       </tr>
	   
	    <tr height='20'>
       <td colspan='4' align='center' style='background-color:#f5f5f5;'>
      
       
       </td>
       </tr>
      
      
              </tbody>";
    
$message .= "</table>";
   
$message .= "</td></tr>";
$message .= "</table>";
   
$message .= "</body></html>";


 
 $mail = new PHPMailer(TRUE);
  $mail->setFrom('********', 'Account');
   $mail->addAddress('*********', 'Lewis');
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '******';
   $mail->Password = '*****';
   $mail->Port = 587;


       if ($mail->addReplyTo($_POST['email'])) {
        $mail->Subject = 'Sports Shop Account';
        //keeps it simple
        $mail->isHTML(true);
        // a simple message body
        $mail->Body = "Account details have been sent to: 
         <br> Their name is: $name
         <br> Their username is: $username 		 
	<br>Their email: $email";


        //Send the message, check for errors
        if (!$mail->send()) {
            //The reason for failing to send will be in $mail->ErrorInfo
        
            $msg = 'Sorry, something went wrong. Please try again later.';
        } else {
            $msg = 'Mail successfully sent';

        }
    } else {
        $msg = 'Invalid email address, message ignored.';
    }
	

			
			$mail->ClearAddresses();
			$mail->AddAddress($email);
			$mail->Body = $message;
			$mail->send();
   
   
   /* Enable SMTP debug output. */
   $mail->SMTPDebug = 4;
   
  // $mail->send();
  

					//redirect to index  page

				header("Location: index.php");

				exit;

			} catch(PDOException $e) {

			    echo $e->getMessage();

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

<div class = "register">

<form action='' method='post' align = "center">
         <h2>Add New Customer</h2>
		<p><a href="index.php">Return to Admin</a></p>
		<p><label>Name</label><br />

		<input type='text' name='name' required ></p>

		<p><label>Username</label><br />

		<input type='text' name='username' required ></p>

		<p><label>Email Address</label><br />

		<input type='email' name='email' required></p>

		<p><label>Password</label><br />

		<input type='password' name='password' required> </p>


		<p><label>Confirm Password</label><br />

		<input type='password' name='passwordConfirm' required> </p>

		<p><input type='submit' name='submit' value='Add User'></p>
		

	</form>
  </div>

</body>

</html>
