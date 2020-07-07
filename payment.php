<?php
// Starting session
//This page gets the data ready for sending through mals ecommerce
session_start();
require_once('includes/configure.php');
require('vendor/autoload.php');

$secret_key = "*********";
$product = $_SESSION['amount'] . " " . $_SESSION['product'];
$units = "0";
$description = substr(addslashes($product), 0, 10);
$hash = md5($secret_key . $description . $price . $units);

//place all variables into an array
$cart =array($_SESSION["customerName"], $_SESSION["customerEmail"], $_SESSION["number"],  $_SESSION["address"], $_SESSION["town"],  $_SESSION["postcode"],
$_SESSION["county"],  $_SESSION["product"], $_SESSION["price"], $_SESSION['amount'], $_SESSION['stockLeft'], $_SESSION['minusCredit'], $_SESSION['username']);


//encode array so the values can be passed through
   $orderCart = base64_encode(json_encode($cart)); 


?>



<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  font-family: Arial;
  font-size: 17px;
  padding: 8px;
}

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}

/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>

</head>
<body>
<div class="row">
  <div class="col-75">
    <div class="container">
       <form method="post" action="*******" >
      
        <div class="row">
          <div class="col-50">
            <h3>Sports Shop Order</h3>
            <label>Click continue to make payment</label>
          </div>
        </div>
		<input name="userid" type="hidden" value="******">
		<input name="product" type="hidden" value="<?php echo $product?>">
		<input name="price" type="hidden" value="<?php echo $price ?>">
		<input type="hidden" name="noqty" value="2"> 
		<input type="hidden" name="hash" value="<?php echo $hash ?>">
		 <input type="hidden" name="sd" value="<?php  echo $orderCart?>">
        <input type="submit" name="submit" value="Continue" class="btn">
		             
		
      </form>
    </div>
  </div>

</body>
</html>


