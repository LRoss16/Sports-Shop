<!--This page will allow the users to buy products
-->
<?php
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');

if(!isset($_SESSION['loggedin'])){ //if login in session is not set
    header("Location: ../../index.php");
}

if($_SESSION['role'] =="admin") {
    header("Location: ../../index.php");
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../mail/Exception.php';
require '../../mail/PHPMailer.php';
require '../../mail/SMTP.php';
require('../../mc_table.php');


?>

<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Buy Products</title>
  <meta http-equiv="refresh" content="900;url=../logout.php"/><!--Log out after 15 minuts of inactivity -->

 <meta name="viewport" content="width=device-width, initial-scale=1">
  
  
  </head>
  
  <style>
  .purchase {
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

		

		if($product ==''){

			$error[] = 'Please enter the product name.';

		}
		
		if($price ==''){

			$error[] = 'Please enter the price of the product.';

		}

		if($amount ==''){

			$error[] = 'Please enter the amount you want.';

		}
		
		if($name ==''){

			$error[] = 'Please enter your name.';

		}
		
		if($email ==''){

			$error[] = 'Please enter your email address.';

		}
		
		if($number ==''){

			$error[] = 'Please enter your phone number.';

		}
		
		if($address ==''){

			$error[] = 'Please enter your address.';

		}
		
		if($town ==''){

		$error[] = 'Please enter your town.';

		}
		
		if($postcode ==''){

			$error[] = 'Please enter your postcode.';

		}
		
		if($county ==''){

			$error[] = 'Please enter your county.';

		}
		
		if($shopTotal ==''){

			$error[] = 'Please enter your shop total.';

		}
		
		if($minusCredit ==''){

			$error[] = 'Please enter the credit to be taken away';

		}
		
		if ($amount > $amountLeft) {
			$error[] = 'Unfortunately we do not have that amount in stock';
		}
		
		if ($stockLeft =='') {
			$error[] = 'Error please refresh and try again';
		}
		
		
$_SESSION["customerName"] = $name;
$_SESSION["customerEmail"] =  $email;
$_SESSION["number"] =  $number;
$_SESSION["address"] =  $address;
$_SESSION["town"] =  $town;
$_SESSION["postcode"] =  $postcode;
$_SESSION["county"] =  $county;
$_SESSION["product"] = $product;
$_SESSION["amount"] = $amount;
$_SESSION["price"] = $shopTotal;
$_SESSION["minusCredit"] = $minusCredit;
$_SESSION["stockLeft"] = $stockLeft;
$price = $shopTotal;		

$username = $_SESSION['username'];


				
		if(!isset($error)){
			
				if ($_SESSION["price"] != 0.00) {
                header("Location: ../../payment.php");

     }
	 
	 else {
	 
	 		$stmt = $dbconn->prepare('UPDATE stock SET stock = :stock WHERE product = :product') ;

			$stmt->execute(array(

						':product' => $product,

						':stock' => $stockLeft


					));
					
			$stmt = $dbconn->prepare('SELECT money FROM users WHERE username = :username') ;

			$stmt->execute(array(':username' => $username));

			$row = $stmt->fetch(); 

           $oldAmount = $row['money'];
		   
		   $newAmount = $oldAmount - $minusCredit;
					
			$stmt = $dbconn->prepare('UPDATE users SET money = :money WHERE username = :username') ;

			$stmt->execute(array(

						':username' => $username,

						':money' => $newAmount


					));
	 
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
	
	
	
   $mail->isHTML(true);
    $mail->setFrom('*******', 'Sports Order Form');
   $mail->addAddress('*****', 'Lewis');
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = TRUE;
   $mail->SMTPSecure = 'tls';
  $mail->Username = '*********';
   $mail->Password = '*********';
   $mail->Port = 587;
  $pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->Image('../../images/logo.png',10,6,30);
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
  		header("Location: index.php");

				exit;
  
}

catch (Exception $e)
{
  echo $e->errorMessage();
}
catch (\Exception $e)
{
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
	
			try {



			$stmt = $dbconn->prepare('SELECT stockID, product, price, stock FROM stock WHERE product = :product') ;

			$stmt->execute(array(':product' => $_GET['product']));

			$row = $stmt->fetch(); 
		


		} catch(PDOException $e) {

		    echo $e->getMessage();

		}
	
	
	?>
  
  <div class = "edit">

<form action='' method='post' align = "center">
         <h2>Purchase</h2>
		 <p><a href ="index.php">Return to website</a></p>
		 

		<input type='hidden' name='amountLeft' readonly value='<?php echo $row['stock'];?>'></p>

		<p><label>Name</label><br />

		<input type='text' name='name' required ></p>
		
		<p><label>Email</label><br />

		<input type='text' name='email' required ></p>
		
		<p><label>Number</label><br />

		<input type='text' name='number' required ></p>
		
		<p><label>Address</label><br />

		<input type='text' name='address' required ></p>
		
		<p><label>Town</label><br />

		<input type='text' name='town' required ></p>
		
		<p><label>Postcode</label><br />

		<input type='text' name='postcode' required ></p>
		
		<p><label>County</label><br />

		<input type='text' name='county'  ></p>
		
		<p><label>Product</label><br />

		<input type='text' name='product' readonly value='<?php echo $row['product'];?>'></p>
		
		<p><label>Price</label><br />

		<input type='hidden' name='price' readonly value='<?php echo $row['price'];?>'></p>

		<p><label>Amount</label><br />

		<input type="text" id="amount" name="amount" oninput="calc()" onkeypress="return isNumber(event)" />
	
		 <p>Price : <b>£<span name="totalPrice" id="totalPrice" size="8"></span></b></p>
         <input type ="hidden" value="" name="shopTotal" id="shopTotal" />
         <input type="text" value="" id="minusCredit" name="minusCredit"/>
		 <input type="hidden" value="" id="stockLeft" name="stockLeft"/>
		<p>Delivery is £4.99</p>

		<p><input type='submit' name='submit' value='Submit Order'></p>
		

	</form>
  </div>
  
    <script>
//check that only numbers are being inputted into stock field
function isNumber(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>


<script>

function calc() 
{
var amount =  document.getElementById("amount").value;
<?php 

$username = $_SESSION['username'];
			$stmt = $dbconn->prepare('SELECT money FROM users WHERE username = :username') ;

			$stmt->execute(array(':username' => $username));

			$content = $stmt->fetch(); 

           $money = $content['money'];

//calculate the discount
$amountCurrently = $row['stock'];
$price = $row['price'];
$deliv = 4.99;

$discountTotal = $money - $price;

$discount = min($discountTotal, $price);
echo $discount;
?>  

//calculate final cost
	

var totalBefore = <?php echo $price ?> * amount + <?php echo $deliv?>

var discount;
var totalAfter;

  if (totalBefore < <?php echo $money ?>)  {
  discount = <?php echo $discount ?> * amount + <?php echo $deliv?>;

  } 
  
  
    if (totalBefore > <?php echo $money?>) {
   var discountCalculate = <?php echo $price ?> * amount + <?php echo $deliv - $money?> ;
   discount = totalBefore - discountCalculate;
  }

	
var totalAfter = <?php echo $price ?> * amount + <?php echo $deliv?> - discount
var amountLeft = <?php echo $amountCurrently?> - amount


document.getElementById("totalPrice").innerHTML = totalAfter.toFixed(2)
document.getElementById("shopTotal").value = totalAfter.toFixed(2)
document.getElementById("minusCredit").value = discount.toFixed(2)
document.getElementById("stockLeft").value = amountLeft.toFixed(0)
}

</script>

</body>

</html>
