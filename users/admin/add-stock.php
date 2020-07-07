<!--This page will allow the admin to add new products for the store. 
Before creating the product it will check whether the product has already been set up-->
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

  <title>Add Stock</title>
  <meta http-equiv="refresh" content="900;url=../logout.php"/><!--Log out after 15 minuts of inactivity -->

 <meta name="viewport" content="width=device-width, initial-scale=1">
  
  </head>
  
  <style>
  .stock {
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



		if($product ==''){

			$error[] = 'Please enter the product name.';

		}

		if($price==''){

			$error[] = 'Please enter the product price.';

		}


		if($stock ==''){

			$error[] = 'Please enter the amount of stock there is.';

		}


		
		if(!isset($error)){



			

			$query = $dbconn->prepare('SELECT product FROM stock WHERE product = ?');

			$query->bindValue( 1, $product );

			$query->execute();



			if( $query->rowCount() > 0 ) { # If rows are found for query

			echo "This product already exists<br>";

		}


	else {



			try {



				//insert into database

  
				$stmt = $dbconn->prepare('INSERT INTO stock (product,price,stock) VALUES (:product, :price, :stock)') ;

				$stmt->execute(array(

					':product' => $product,

					':price' => $price,
					
					':stock' => $stock
                  

				));

			

  

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

<div class = "stock">

<form action='' method='post' align = "center">
         <h2>Add New Product</h2>
		<p><a href="index.php">Return to Admin</a></p>
		<p><label>Product</label><br />

		<input type='text' name='product' required ></p>

		<p><label>Price</label><br />

		<input type='text' name='price' required onkeypress="return isPrice(event)" ></p>

		<p><label>Stock</label><br />

		<input type='int' name='stock'required onkeypress="return isNumber(event)" ></p>	

		<p><input type='submit' name='submit' value='Add Product'></p>
		

	</form>
  </div>
  
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
</body>

</html>
