<!-- this page will delete a user from the users table and then an email to them to form them of this -->
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

		try {
			
        $stmt = $dbconn->prepare('SELECT email FROM users WHERE username= :username') ;
		$stmt->execute(array(':username' => $_GET['username']));

			$row = $stmt->fetch(); 
			
			$email = $row['email'];


		$stmt = $dbconn->prepare('DELETE FROM users WHERE username = :username') ;

		$stmt->execute(array(':username' => $_GET['username']));
		

//email to inform user of deleted account

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
       <label>Your Account has been deleted</label> <BR />
	   <label>Sorry to see you go</label> <BR />
	   <label>If this was a mistake please get in touch ASAP</label> <BR />
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
  $mail->setFrom('*******', 'Account');
   $mail->AddAddress($email);
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '*******';
   $mail->Password = '******';
   $mail->Port = 587;


       if ($mail->addReplyTo('*******')) {
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
		
		
		header('Location: index.php');
		exit;



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}







?>