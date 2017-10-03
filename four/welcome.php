<?php
	// Inialize session
	session_start();
	if (isset($_SESSION['register']) && $_SESSION['register'] == true) {
 	}
	else
		header("Location: login.php");
?>
<!Doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registration Successfull</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href ="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<style>

			body, html {
    height: 100%;
}
			.bg { 
					/* The image used */
				   background: #3a6186; /* fallback for old browsers */
				   background: -webkit-linear-gradient(to left, #3a6186 , #89253e); /* Chrome 10-25, Safari 5.1-6 */
				   background: linear-gradient(to left, #3a6186 , #89253e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
				   color : #fff;    

					/* Full height */
					height: 100%; 

					/* Center and scale the image nicely */
					background-position: center;
					background-repeat: no-repeat;
					background-size: cover;
				}
			#outPopUp {
			  
			  
			  position: absolute;
			  width: 800px;
			  height: 200px;
			  z-index: 15;
			  top: 50%;
			  left: 25%;
			  margin:0 auto;
				
			}
		</style>
		
	</head>
	<body>
	
	<section class="bg">
		<div class="container">
			<h1 align="center">Registration Successfull</h1>
			<br/>
			<div id="outPopUp">
				<h3>Hi <?php echo $_SESSION['name'] ?> your account has been created. Click here to login</h3>
				<a href="login.php"><button class="btn btn-primary success">Login</button></a>
			</div>
		</div>
		</div>
	</section>
	
	<!--scripts-->
		<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<?php
			// Unset all session variables
			session_unset();
			// Delete all session variables
			session_destroy();
		?>
	</body>
</html>
