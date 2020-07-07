<?php
session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');

if(!isset($_SESSION['loggedin'])){ //if login in session is not set
    header("Location: ../../index.php");
}

if($_SESSION['role'] =="admin") {
    header("Location: ../admin/index.php");
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>1501830 Coursework</title>
		<style type="text/css">


* {
font-family: Arial, Verdana, sans-serif;
text-align: center;

}


#content {
overflow: auto;
}


li {
display: inline;
padding: 0.5em;
}
#links {
background-color: #ffffff;
padding: 0.5em;
width: 100%;
height: 7em;
}
#footer {
  background-color: #ffffff;
  padding: 0.5em;
  color: #665544;

}				

#links a {
  color: #665544;
}
 a:hover {
background-color: #FFCCFF;
}	
			
#about {
height: 10em;
background-color: #efefef;
padding-top: 30px;
color: #665544;
padding-bottom: 240px;
margin-bottom: -18px;
}
#shop {
background-color: #585656;
color: #ffffff;
height: 10em;
margin-bottom: -18px;
padding-bottom: 300px;
}

#subscribe {
height: 10em;
padding-top:40px;
padding-bottom: 240px;
background-color: #efefef;
color: #665544;
margin-bottom: -18px;

}

	

		</style>
	</head>
	<body>
		<div id="header">
		<img src= "../../images/logo.png">
		
			<h1>1501830 Sports Shop</h1>
			<h1>Welcome <?php echo $_SESSION['username'];?></h1>
			<?php 
			$username = $_SESSION['username'];
			$stmt = $dbconn->prepare('SELECT username, money FROM users WHERE username = :username') ;
			$stmt->execute(array(':username' => $username));
			$row = $stmt->fetch(); 
			$money = $row['money'];		
			?>
			<h2>You have: £<?php echo $money;?> left in store credit </h2>
			<div id="links">
				<ul>
                <?php include('menu.php');?>
				</ul>
			</div>
		</div>
		<div id="content">
			<div id="about">
      <h1>About</h1>
<p>Welcome to your account. From here you will be able to make purchases and also subscribe to our newsletter if you have not already</p>
<p>You will be able to use what store credit you have to make purchases, as long as it covers the full amount</p>

			</div>
      	<div id="shop">
				<h2>Shop Now</h2>
				<p>We have loads of different items in stock</p>
				
	<table align ="center">

	<tr>

		<th>Product</th>

		<th>Stock</th>
		
	    <th>Price</th>

		<th>Buy</th>

	</tr>
			<?php

		try {



			$stmt = $dbconn->query('SELECT stockID, product, price, stock FROM stock WHERE stock > 0');

			while($row = $stmt->fetch()){

				

				echo '<tr>';

				echo '<td>'.$row['product'].'</td>';

				echo '<td>'.$row['stock'].'</td>';
				
			    echo '<td>'.$row['price'].'</td>';


				?>



				<td>

					<a href="shop.php?product=<?php echo $row['product'];?>">Buy</a>


				</td>

				

				<?php 

				echo '</tr>';



			}

		} catch(PDOException $e) {

		    echo $e->getMessage();

		}

	?>

	</table>

				
			</div>
       <div id="subscribe">
				<h2>Want to keep up-to-date with what is going on?</h2>
				<p>Subscribe to our newsletter!</p>
				<input id="email" name="email" type="email" placeholder="Enter email here..."><br /><br />
				<button type="submit" id="submit">Submit</button>
				<div id="result" style="margin-top: 50px;"></div> 
			</div>
			
			</div>

		<div id="footer">
			<p>Lewis Ross 1501830 CM4025 Coursework</p>
		</div>
		
<!--Script for allowing customers to subscribe to newsletter, a call to subscribe.php is made which allows this to happen
A response is then displayed based on the outcome-->	
		<script src="https://code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#email');
			$('#email').keypress(function(event) {
				var email = $('#email').val();
				var keyCode = event.keyCode;
				if (keyCode == 13) {
					$.ajax({
						type: 'POST',
						url: '../../subscribe.php',
						data: {email: email},
						success: function(data) {
							$('#result').hide();
							$('#result').html(data);
							$('#result').fadeIn();
						}
					});
				};
			});
			$('#submit').click(function () {
				var email = $('#email').val();
				$.ajax({
					type: 'POST',
					url: '../../subscribe.php',
					data: {email: email},
					success: function(data) {
						$('#result').hide();
						$('#result').html(data);
						$('#result').fadeIn();
					}
				});
			});
		});
	</script>
	</body>
</html>
