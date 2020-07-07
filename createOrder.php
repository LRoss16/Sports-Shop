<!--This page will create the order, it will take the order details and put them into a pdf and xml forms. The admin will be sent a pdf and xml copy of the order
and the customer will be sent a pdf copy -->
<?php
// Starting session
session_start();
require_once('includes/configure.php');
require('vendor/autoload.php');



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';
require('mc_table.php');

//decode array
 $array = json_decode(base64_decode($_POST['sd'], true));

$name = $array[0];
$email = $array[1];
$number = $array[2];
$address = $array[3];
$town =  $array[4];
$postcode = $array[5];
$county = $array[6];
$product = $array[7];
$price = $array[8];
$amount = $array[9];
$stockLeft = $array[10];
$minusCredit = $array[11];
$username = $array[12];



/* create a dom document with encoding utf8 */
     $doc = new DOMDocument();
	 $doc->formatOutput = true;


	$root = $doc->createElement('OrderDetails');
	$root = $doc->appendChild($root);
	
	$ele1 = $doc->createElement('Name');
	$ele1->nodeValue=$name;
	$root->appendChild($ele1);
	
	$ele2 = $doc->createElement('Email');
	$ele2->nodeValue=$email;
	$root->appendChild($ele2);

	$ele3 = $doc->createElement('Number');
	$ele3->nodeValue=$number;
	$root->appendChild($ele3);
 
	$ele4 = $doc->createElement('Address');
	$ele4->nodeValue=$address;
	$root->appendChild($ele4);
		
	$ele5 = $doc->createElement('Town');
	$ele5->nodeValue=$town;
	$root->appendChild($ele5);
	
	$ele6 = $doc->createElement('Postcode');
	$ele6->nodeValue=$postcode;
	$root->appendChild($ele6);
	
	$ele7 = $doc->createElement('County');
	$ele7->nodeValue=$county;
	$root->appendChild($ele7);
	
	$ele8 = $doc->createElement('productOrdered');
	$ele8->nodeValue=$product;
	$root->appendChild($ele8);

	$ele9 = $doc->createElement('Price');
	$ele9->nodeValue=$price;
	$root->appendChild($ele9); 

$message  = "<html><body>";

$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
   
$message .= "<tr><td>";
   
$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
    
$message .= "<thead>
  <tr height='80'>
  <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#000000; font-size: 20px;' >Thank you for your order!</th>
  </tr>
             </thead>";
    
$message .= "<tbody>

	    <tr height='80'>
       <td colspan='4' align='center' style='background-color:#f5f5f5;'>
    
        <p 'font-size:8px;'>Here is an attached pdf copy of it</p>
       
       
       </td>
       </tr>

      
      
              </tbody>";
    
$message .= "</table>";
   
$message .= "</td></tr>";
$message .= "</table>";
   
$message .= "</body></html>";

$mail = new PHPMailer(TRUE);


try {
	
		    $stmt = $dbconn->prepare('UPDATE stock SET stock = :stock WHERE product = :product') ;

			$stmt->execute(array(

						':product' => $product,

						':stock' => $stockLeft


					));
					
			$state = $dbconn->prepare('UPDATE users SET money = :money WHERE username = :username') ;

			$state->execute(array(

						':username' => $username,

						':money' => 0.00


					));
	
   $mail->isHTML(true);
    $mail->setFrom('*******', 'Sports Order Form');
   $mail->addAddress('******', 'Lewis');
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '******';
   $mail->Password = '******';
   $mail->Port = 587;
  $pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->Image('images/logo.png',10,6,30);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(80);
	$pdf->Cell(30,10,'1501830 Sports Order','C');
	 $pdf->Ln(20);
   $pdf->SetFont('Arial','B',16);
   
   
$pdf->Cell(40,10, 'Name:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $name);

$pdf->Ln();

 $pdf->Cell(40,10, 'Email:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $email);

$pdf->Ln();

 $pdf->Cell(40,10, 'Number:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $number);

$pdf->Ln();

 $pdf->Cell(40,10, 'Address:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $address);

$pdf->Ln();

 $pdf->Cell(40,10, 'Town:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $town);

$pdf->Ln();

 $pdf->Cell(40,10, 'Postcode:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $postcode);

$pdf->Ln();

 $pdf->Cell(40,10, 'County:');
$pdf->Cell(50); 
$pdf->Cell(40,10, $county);

$pdf->Ln();

 $pdf->Cell(40,10, 'Product and amount:');
$pdf->Cell(60);
$pdf->Cell(40,10, $amount); 
$pdf->Cell(40,10, $product);

$pdf->Ln();


 $pdf->Cell(40,10, 'Price:');
$pdf->Cell(60); 
$pdf->Cell(40,10, $price);

   
   
   
     $pdfdoc = $pdf->Output('', 'S'); 
       if ($mail->addReplyTo($email)) {
        $mail->Subject = '1501830 Sports Order';
		
		 //keeps it simple
        $mail->isHTML(true);
        // a simple message body
	$mail->Body = "A new order has been submitted";


	$mail->addStringAttachment($pdfdoc, 'sportsOrder.pdf');
	 $mail->addStringAttachment($doc->saveXML(), "sportsOrder.xml");


	
        //Send the message, check for errors
        if (!$mail->send()) {
            //The reason for failing to send will be in $mail->ErrorInfo
        
            $msg = 'Sorry, something went wrong. Please try again later.';
        } else {
           // $msg = 'Message sent! Thanks for contacting us.';
        }
    } else {
        $msg = 'Invalid email address, message ignored.';
    }
	
	
	
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			$mail->AddAddress($email);
			$mail->Body = $message;
			$mail->addStringAttachment($pdfdoc, 'sportsOrder.pdf');
			$mail->send();

   
   
   /* Enable SMTP debug output. */
  $mail->SMTPDebug = 4;
   
  // $mail->send();
  
  
}

catch (Exception $e)
{
  echo $e->errorMessage();
}
catch (\Exception $e)
{
   echo $e->getMessage();
   
 }


