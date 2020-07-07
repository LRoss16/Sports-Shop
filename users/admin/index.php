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
			
#users {
height: 10em;
background-color: #efefef;
padding-top: 30px;
color: #665544;
padding-bottom: 100px;
margin-bottom: -18px;
}
#stock {
background-color: #585656;
color: #ffffff;
height: 10em;
margin-bottom: -18px;
padding-bottom: 200px;
}

#subscribers {
height: 10em;
padding-top:40px;
padding-bottom: 100px;
background-color: #efefef;
color: #665544;
margin-bottom: -18px;

}

table {
	width: 100%;
}

	

		</style>
	</head>
	<body>
		<div id="header">
		<img src= "../../images/logo.png">
		
			<h1>1501830 Sports Shop</h1>
			<h1>Welcome <?php echo $_SESSION['username'];?></h1>
			<div id="links">
				<ul>
                <?php include('menu.php');?>
				</ul>
			</div>
		</div>
		<div id="content">
			<div id="users">
		<!--Users Table, this will display all the people who have an account -->
      <h1>Users</h1>
	<table align ="center">

	<tr>

		<th>Name</th>

		<th>Username</th>
		
	    <th>Store Credit</th>
		
	    <th>Password level</th>

		<th>Edit/Delete</th>

	</tr>

	<?php

		try {



			$stmt = $dbconn->query('SELECT userID, username, name, role, passwordchange, money FROM users');

			while($row = $stmt->fetch()){

				

				echo '<tr>';

				echo '<td>'.$row['name'].'</td>';

				echo '<td>'.$row['username'].'</td>';
				
				echo '<td>'.$row['money'].'</td>';
	
				echo '<td>'.$row['passwordchange'].'</td>';

				?>



				<td>
				
				<?php if ($row['role'] == "admin") {?><a href="edit-admin.php?username=<?php echo $row['username'];?>">Edit</a><?php } ?>

				<?php if ($row['role'] != "admin") {?><a href="edit-user.php?username=<?php echo $row['username'];?>">Edit User Password</a> |<?php } ?> 
				
				<?php if ($row['role'] != "admin") {?><a href="edit-credit.php?username=<?php echo $row['username'];?>">Edit User Store Credit</a> |<?php } ?> 

				<?php if ($row['role'] != "admin") {?><a href="delete-user.php?username=<?php echo $row['username'];?>"class="confirmation">delete</a><?php } ?> 

				</td>

				

				<?php 

				echo '</tr>';



			}

		} catch(PDOException $e) {

		    echo $e->getMessage();

		}

	?>

	</table>



	<p><a href='add-user.php'>Add User</a></p>

			</div>
      	<div id="stock">
		<h2>Stock</h2>
		<!--Stock Table, this will display all the products in stock -->
			<table align ="center">

	<tr>

		<th>Product</th>

		<th>Stock</th>
		
	    <th>Price</th>

		<th>Edit/Delete</th>

	</tr>

	<?php

		try {



			$stmt = $dbconn->query('SELECT stockID, product, price, stock FROM stock');

			while($row = $stmt->fetch()){

				

				echo '<tr>';

				echo '<td>'.$row['product'].'</td>';

				echo '<td>'.$row['stock'].'</td>';
				
			    echo '<td>'.$row['price'].'</td>';


				?>



				<td>

					<a href="edit-stock.php?product=<?php echo $row['product'];?>">Edit</a> | 

					<a href="delete-stock.php?product=<?php echo $row['product'];?>"class="confirmation">delete</a> 

				</td>

				

				<?php 

				echo '</tr>';



			}

		} catch(PDOException $e) {

		    echo $e->getMessage();

		}

	?>

	</table>



	<p><a href='add-stock.php'>Add Stock</a></p>
	
	</div>
			 <div id="subscribers">
				<h2>List of subscribers</h2>
				<!--Subscribers Table, this will display all the people who have subscribed to the newsletter-->
			<table align ="center">

	<tr>

		<th>Email</th>

		<th>Delete</th>

	</tr>

	<?php

		try {



			$stmt = $dbconn->query('SELECT subscribeID, email FROM subscribe');

			while($row = $stmt->fetch()){

				

				echo '<tr>';

				echo '<td>'.$row['email'].'</td>';


				?>



				<td>

					<a href="delete-subscriber.php?email=<?php echo $row['email'];?>"class="confirmation">delete</a> 

				</td>

				

				<?php 

				echo '</tr>';



			}

		} catch(PDOException $e) {

		    echo $e->getMessage();

		}

	?>

	</table>
	
	<p><a href='sendNewsletter.php'>Send Newsletter</a></p>
	</div>
</div>

		<div id="footer">
			<p>Lewis Ross 1501830 CM4025 Coursework</p>
		</div>
		
		
		<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete this?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
		
	</body>
</html>

