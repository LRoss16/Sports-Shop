<!--this page allows the admin to send out a newsletter to all the subscribers-->
<?php 

session_start();
require_once('../../includes/configure.php');
require('../../vendor/autoload.php');

if(!isset($_SESSION['loggedin'])){ //if login in session is not set
    header("Location: ../../index.php");
}

if($_SESSION['role'] !="admin") { //if user not admin, redirect
    header("Location: ../../index.php");
}

?>


<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">

  <title>Admin - Send Newsletter</title>

 <meta http-equiv="refresh" content="900;url=../logout.php"/><!--Log out after 15 minuts of inactivity -->

 <meta name="viewport" content="width=device-width, initial-scale=1">


    <script src="tinymce/tinymce.min.js"></script>

  <script src="filemanager/plugin.min.js"></script>

	<script src="https://code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>





<script>

tinymce.init({

    selector: "textarea",theme: "modern",width: 1500,height: 500,

	convert_urls: false,

      plugins: [

    'advlist autolink lists link image charmap print preview hr anchor pagebreak',

    'searchreplace wordcount visualblocks visualchars code fullscreen',

    'insertdatetime media nonbreaking save table contextmenu directionality',

    'emoticons template paste textcolor colorpicker textpattern imagetools responsivefilemanager'],



   toolbar1: "sizeselect | fontselect |  fontsizeselect |insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | editimage | imageoptions",

   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",

   image_advtab: true ,

   

   external_filemanager_path:"filemanager/",

   filemanager_title:"Responsive Filemanager" ,

   external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}

 });

</script>



 

  <style>

  .hidden{display:none;}


  .register {
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






</head>

<body>






	<?php



	//if form has been submitted process it

	if(isset($_POST['submit'])){


		//collect form data

		extract($_POST);



		if($subject ==''){

			$error[] = 'Please enter the title.';

		}





		if($emailCont ==''){

			$error[] = 'Please enter the content.';

		}



		if(!isset($error)){



			



		}



	}



	//check for any errors

	if(isset($error)){

		foreach($error as $error){

			echo '<p class="error">'.$error.'</p>';

		}

	}

	?>


	    <div class="Newsletter">


	<form action='createNewsletter.php' method='post' align="center">


	<h2>Send Newsletter</h2>

	<p>(Both subject and content must be filled out in order to send the newsletter)</p>
	
		<p><a href="index.php">Back to Admin</a></p>


		<p><label>Subject</label><br />

		<input type='text' name='subject' required value='<?php if(isset($error)){ echo $_POST['subject'];}?>'></p>

	

		<p><label>Content</label><br />

		<textarea name='emailCont' id="emailCont" cols='60' rows='10'><?php if(isset($error)){ echo $_POST['emailCont'];}?></textarea></p>

		 <input name="image" type="file" id="upload" class="hidden" onchange="">



		<p><input type='submit' name='submit' value='Submit'></p>



	</form>


	</div>

	</body>

	

	

	</html>