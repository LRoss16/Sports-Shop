<!--This is the page that is called when a customer wants to subscribe to the newsletter
This page will check that the email entered is valid and is not already signed up for the newsletter. 
It sends a response back to the homepage based on the outcome.
Once a customer's email is stored in the SQL database, an email will be sent to them thanking them for doing so -->

<?php
require_once('includes/configure.php');
require('vendor/autoload.php');


$message  = "<html><body>";


   
$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
   
$message .= "<tr><td>";
   
$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
    
$message .= "<thead>
  <tr height='80'>
  <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#000000; font-size: 27px;' >Thank you for subscribing to our newsletter. Make sure to check your junk box in case we unfortunately end up there </th>
  </tr>
             </thead>";
    
$message .= "<tbody>
      

	    <tr height='20'>
       <td colspan='4' align='center' style='background-color:#f5f5f5;'>
    
        <p 'font-size:8px;'><a href='https://sheltered-reef-10750.herokuapp.com//unsubscribe.php'>Unsubscribe</a></p>
       
       
       </td>
       </tr>
      
      
              </tbody>";
    
$message .= "</table>";
   
$message .= "</td></tr>";
$message .= "</table>";
   
$message .= "</body></html>";

$email = $_POST['email'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';
 
 
$mail = new PHPMailer(TRUE);
if (isset($_POST['email'])) {
	$email = $_POST['email'];
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$query = $dbconn->prepare('SELECT email FROM subscribe WHERE email = ?');
		$query->bindValue(1, $email);
		$query->execute();

		if( $query->rowCount() > 0 ) { # If rows are found for query
		echo "You have already subscribed";
		}
	else {
			try {
				
				//insert into database
				$stmt = $dbconn->prepare('INSERT INTO subscribe (email) VALUES (:email)') ;
				$stmt->execute(array(
					':email' => $email
					
				));
				
				
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
     echo "Thank you for subscribing";	
try {
	

    $mail->setFrom('**********', 'Newsletter');
   $mail->addAddress('*********', 'Lewis');
   $mail->Subject = 'Newsletter';
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '*********';
   $mail->Password = '********';
   $mail->Port = 587;
   
       if ($mail->addReplyTo($_POST['email'])) {
        $mail->Subject = 'Subscription';
        //keeps it simple
        $mail->isHTML(true);
        // a simple message body
        $mail->Body = $_POST['email']. " has subscribed to the newsletter";

        //Send the message, check for errors
        if (!$mail->send()) {
            //The reason for failing to send will be in $mail->ErrorInfo
        
            $msg = 'Sorry, something went wrong. Please try again later.';
        } else {
            $msg = 'Thanks for subscribing!.';
			//header("Refresh:3; url=index.html#contact");
		//	echo "Your message was successfully sent";
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
  
 
}

		
catch (Exception $e)
{
  echo $e->errorMessage();
}

		} 
 
 } else {
		echo "Please enter a valid email...";
	}
	
	}
 
//}

 

 ?> 
   
   
