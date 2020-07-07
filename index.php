<?php

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
padding-bottom: 330px;
margin-bottom: -18px;
}
#shop {
background-color: #585656;
color: #ffffff;
height: 10em;
margin-bottom: -18px;
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
		<img src= "images/logo.png">
		
			<h1>1501830 Sports Shop</h1>
			<div id="links">
				<ul>
					<li><a href="#about">About</a></li>
					<li><a href="#shop">Shop</a></li>
					<li><a href="#subscribe">Subscribe</a></li>
					<li><a href="register.php">Register</a></li>
					<li><a href="login.php">Login</a></li>
				</ul>
			</div>
		</div>
		<div id="content">
			<div id="about">
      <h1>About</h1>
			<p>The website is a sports shop. User can order with or without being logged in to order things, when they log in they will have virtual money which will go down each time they make a purchase</p>
            <p>The admin can add more money to user's account</p>
			<p>Users can register for account, when this is done they will be given £50 virtual money</p>
			<p>Users will have different checkout depending on whether they have money</p>
            <p>Users can subscribe to newsletter, at bottom of each email sent will be an option to unsubscribe</p>
			<p>Users will get an email when any changes to their account is made</p>
			<p>PHP Mailer is being used for sending emails</p>
      <p>Tinymce is being for making the content of emails</p>
	  <p>File Manager is being used to be able to inset images into emails </p>
	  <p>Mals ecommerce is used for when total in shop order is more than 0, this is just to give the impression of actually taking an order, no card details are taken</p>
	  <p>Similar code for editing users as in honours project but do not think there was any other way to do it</p>
<p>Logo Design from <a href="https://www.freelogodesign.org/" target="_blank">Free logo design</a></p>

			</div>
      	<div id="shop">
				<h2>Shop Now</h2>
				<p>Want to purchase some incredible sporting good?</p>
				<p><a href="register.php">Create an account!</a></p>
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
						url: 'subscribe.php',
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
					url: 'subscribe.php',
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
