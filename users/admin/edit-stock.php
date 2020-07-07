<!--This page will allow the admin to edit a product's price or stock amount
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


?>

<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Edit Product Details</title>
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

		

		if($product ==''){

			$error[] = 'Please enter the product name.';

		}
		
		if($price ==''){

			$error[] = 'Please enter the price of the product.';

		}

		if($stock ==''){

			$error[] = 'Please enter the stock amount.';

		}

		
				
		if(!isset($error)){
					 
		 	$stmt = $dbconn->prepare('UPDATE stock SET product = :product, price = :price, stock = :stock WHERE product = :product') ;

			$stmt->execute(array(

						':product' => $product,

						':price' => $price,
						
						':stock' => $stock


					));
					
					
					


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



			$stmt = $dbconn->prepare('SELECT stockID, product, price, stock FROM stock WHERE product = :product') ;

			$stmt->execute(array(':product' => $_GET['product']));

			$row = $stmt->fetch(); 



		} catch(PDOException $e) {

		    echo $e->getMessage();

		}
	
	
	?>
  
  <div class = "edit">

<form action='' method='post' align = "center">
         <h2>Edit Stock</h2>
		 <p><a href ="index.php">Return to admin</a></p>

		<p><label>Product</label><br />

		<input type='text' name='product' readonly value='<?php echo $row['product'];?>'></p>
		
		<p><label>Price</label><br />

		<input type='text' name='price' required onkeypress="return isPrice(event)"  value='<?php echo $row['price'];?>'></p>

		<p><label>Stock</label><br />

		<input type='int' name='stock' required onkeypress="return isNumber(event)" value='<?php echo $row['stock'];?>'> </p>


		<p><input type='submit' name='submit' value='Complete Changes'></p>
		

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
  //check only integers or decimals are being inputted into price field
  function isPrice(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))) {
        return false;
    }
    return true;
}

</script>

</body>

</html>
