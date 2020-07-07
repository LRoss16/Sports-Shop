<!--This page will allow the admin to reset a user's password. The user will get an email stating this.
When they login for the first time afterwards they will have to change their password
-->
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

  <title>Edit User</title>
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

			$error[] = 'Please enter the username.';

		}
		
		if($email ==''){

			$error[] = 'Please enter the email.';

		}

		if($password ==''){

			$error[] = 'Please enter the password.';

		}
		
		if($confirmPassword ==''){

			$error[] = 'Please confirm the password.';

		}
		
		if($password != $confirmPassword){

			$error[] = 'Passwords do not match.';

		}
		
				
		if(!isset($error)){
			
		 $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
		 
		 	$stmt = $dbconn->prepare('UPDATE users SET username = :username, password = :password, passwordchange = :passwordchange WHERE username = :username') ;

			$stmt->execute(array(

						':username' => $username,

						':password' => $hashedpassword,

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
       <label>Your Password has been reset:</label>
	   <label>Username: ".$username."</label> <BR />
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
  $mail->setFrom('******', 'Account');
   $mail->AddAddress($email);
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '*******';
   $mail->Password = '******';
   $mail->Port = 587;


       if ($mail->addReplyTo($_POST['email'])) {
        $mail->Subject = 'Sports Shop Account';
        //keeps it simple
        $mail->isHTML(true);
        // a simple message body
          $mail->Body = $message;


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
	
   
   
   /* Enable SMTP debug output. */
   $mail->SMTPDebug = 4;
   
  // $mail->send();
  


				header("Location: index.php");

				exit;

		
						
						
	}

  }

  
  			//check for any errors

	if(isset($error)){

		foreach($error as $error){

			echo '<p class="error" align="center">'.$error.'</p>';

		}

	}
	
			try {



			$stmt = $dbconn->prepare('SELECT userID, username, email FROM users WHERE username = :username') ;

			$stmt->execute(array(':username' => $_GET['username']));

			$row = $stmt->fetch(); 



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}
	
	
	?>
  
  <div class = "edit">

<form action='' method='post' align = "center">
         <h2>Reset user password</h2>
		 <p><a href ="index.php">Return to admin</a></p>

		<p><label>Username</label><br />

		<input type='text' name='username' readonly value='<?php echo $row['username'];?>'></p>
		
		<p><label>Email</label><br />

		<input type='email' name='email' readonly value='<?php echo $row['email'];?>'></p>

		<p><label>Password</label><br />

		<input type='password' name='password' required> </p>
		
		<p><label>Confirm Password</label><br />

		<input type='password' name='confirmPassword' required> </p>


		<p><input type='submit' name='submit' value='Complete Changes'></p>
		

	</form>
  </div>

</body>

</html>
