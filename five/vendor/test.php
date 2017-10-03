<?php
require_once('autoload.php');
if(isset($_POST['submit'])){
	$username = test_input($_POST['username']);
	$password = test_input($_POST['password']);

	$user = new Bitbucket\API\User();
	  $user->getClient()->addListener(
		  new Bitbucket\API\Http\Listener\BasicAuthListener($username, $password)
	  );
	  // now you can access protected endpoints as $bb_user
	  $response = $user->get();

	}
//function to do validation and triming data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!Doctype Html>
<html>
	
	<head>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>BitBucket Profile</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href ="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	</head>
	<body>
	<div class="container" style="padding-top: 20px">
	<div align="center" style="padding-bottom: 20px">
		<h2>Enter Your Credentials to get Bitbucket Data !</h2>
	</div>
	
		<form autocomplete="off" method="post">
		  <div class="form-group">
			<label>Enter Username</label>
			<input type="email" maxlength="255" class="form-control" name="username" placeholder="Enter BitBucket Username" required>
		  </div>
		  <div class="form-group">
			<label>Enter Password</label>
			<input type="password" maxlength="50" class="form-control" name="password" placeholder="Enter BitBucket Password" required>
		  </div>
		  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
	
	<div class="container">
		<h1 align="center">Details of BitBucket Profile</h1>
		<div style="padding-top: 50px"></div>
		
	    <div class="form-group">
			<label>Buzz Data </label>
			<?php 
			 
			if(isset($response))
				var_dump($response);
			?>
			
		</div>
		
		<div class="form-group">
			<label>Only Profile Data </label>
			<?php 
			 
			if(isset($response)){
				if(!empty(end($response)))
					echo '<textarea class="form-control" rows="10" readonly>'.end($response).'</textarea>';
				else
					echo '<textarea class="form-control" style="resize:none" rows="10" readonly>Invalid Credentials</textarea>';
			}
			?>
			
		</div>
		
	</div>
	
	
	</body>
	
</html>
