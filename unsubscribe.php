<!--This page for allowing customers to unsubscribe from recieving newsletters-->
<?php 

session_start();
require_once('includes/configure.php');
require('vendor/autoload.php');

 ?>
<!DOCTYPE html>
<html>
<head>
<title>1501830 Sports Shop Unsubscribe</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<style>

.unsubscribe {
  margin: auto;
  width: 50%;
  padding: 10px;
}

#email {
	
  margin: auto;
  width: 50%;
  padding: 10px;	
}

#submit {
 margin: auto;
  width: 50%;
  padding: 10px;
	
}




</style>


<body>
<div class="unsubscribe" align="center" style="padding:128px 16px">
  <p>Please enter your email and confirm to unsubscribe</p>
  <input id="email" type="email" placeholder="Enter email here..."><br /><br />
	<button type="submit" id="submit">Submit</button>
	<div id="result" style="margin-top: 50px;"></div> 
	
<!--removeSubscription.php is called. This checks whether  the email is valid and is actually subscribed. 
If they are then customer will be removed from subscribed table and a message will appear informing them.
If they are not or the email is not valid a message will appear informing them -->	
	
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
						url: 'removeSubscription.php',
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
					url: 'removeSubscription.php',
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