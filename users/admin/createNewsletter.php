<!--This page will create the newslettet that is send out to the subscribers. At the bottom of each email will be an option to unsubscribe -->

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






$subject = $_POST['subject'];



$body = $_POST['emailCont'];







$sql = "SELECT email FROM subscribe";



    foreach ($dbconn->query($sql) as $row) {



$message  = "<html><body>";





   

$message .= "<table width='100%' bgcolor='#f5f5f5' cellpadding='0' cellspacing='0' border='0'>";

   

$message .= "<tr><td>";

   

$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

    

$message .= "<thead>

  <tr height='80'>

  <th colspan='5' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#000000; font-size: 27px;' >$subject


  </tr>

             </thead>";

    

$message .= "<tbody>

      

  	    <tr height='80'>

       <td colspan='4' align='center' style='background-color:#f5f5f5;'>

	   

    

        <p 'font-size:8px;'>$body</p>

       

       

       </td>

       </tr>

	   <br />

	   

	    <tr height='20'>

       <td colspan='4' align='center' style='background-color:#f5f5f5;'>

    

        <p 'font-size:8px;'><a href='http://sheltered-reef-10750.herokuapp.com/unsubscribe.php'>Unsubscribe</a></p>

       

       

       </td>

       </tr>

      

      

              </tbody>";

    

$message .= "</table>";

   

$message .= "</td></tr>";

$message .= "</table>";

   

$message .= "</body></html>";





$mail = new PHPMailer(TRUE);





try {

	

   $mail->isHTML(true);

  $mail->SetFrom('donotreply@mydomain.com', 'Newsletter');

    $mail->AddAddress($row['email']);

   $mail->isSMTP();

   $mail->Host = 'smtp.gmail.com';

   $mail->SMTPAuth = TRUE;

   $mail->SMTPSecure = 'tls';

  $mail->Username = '***********';

   $mail->Password = '*********';

   $mail->Port = 587;



       if ($mail->addReplyTo('*********')) {

        $mail->Subject = "Sports Shop Newsletter";

		

		 //keeps it simple

        $mail->isHTML(true);

        // a simple message body



	$mail->Body = $message;



	



	           //Send the message, check for errors

        if (!$mail->send()) {

            //The reason for failing to send will be in $mail->ErrorInfo

        

            $msg = 'Sorry, something went wrong. Please try again later.';

        } else {

            $msg = 'The Newsletter was sucessfully sent<br>';

			header("Refresh:3; url=index.php");

			echo "The Newsletter was sucessfully sent<br>";



        }

    } else {

        $msg = 'Invalid email address, message ignored.';

    }

	

	





 

}catch (Exception $e)

{

  echo $e->errorMessage();

    $mail->smtp->reset();

}



 $mail->clearAddresses();





  

}











