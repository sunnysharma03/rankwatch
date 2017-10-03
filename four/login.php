<?php

 session_start();

 //Include database configuration file
 include('config.php');

	//if he is already loggedin
 if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	 	header("Location: profile.php");	
 }

 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
  $err = '';
  $user = test_input($_POST["username"]);
  $username = str_replace(" ","",$user);
  $username = mb_strtolower($username);
  $pass = test_input($_POST["Password"]);
  $password = str_replace(" ","",$pass);
  
  $count = 0;
  
	
	 $stm = $db->prepare("SELECT username FROM login WHERE username = ? ");
	 $stm->bind_param("s",$username);
	 $stm->execute();
	 $stm->store_result();
	 $count = $stm->num_rows;
	 $stm->free_result();
	 
	if($count == 1){
	
		///now get password into a variable
		$stm = $db->prepare("SELECT password FROM login WHERE username = ? ");
		$stm->bind_param("s",$username);
		$stm->execute();
		
		$passworddb = $stm->get_result()->fetch_object()->password;
		$stm->free_result();
		
		if (password_verify($password, $passworddb)) {
			if (($stmt = $db->prepare("UPDATE login SET last_login = '".date("Y-m-d")."' WHERE username = ? "))) {
				if ($stmt->bind_param("s",$username)) {
					if ($stmt->execute()) {
						//now redirect it to the view section
						$_SESSION['loggedin'] = true;
						$_SESSION['username'] = $username;
						header("Location: profile.php");
					}
					else
						$flag = 1;
				}
				else
					$flag = 1;
			}
			else
				$flag = 1;

			if($flag == 1)
			{
				  $err = $err . "Sorry Something Went Wrong While Processing Your Request. Please Contact Developer About this Issue !";
			}
		}
		 else
			$err = $err . "<strong>Incorrect Credentials !</strong> Please Verify Your Access Credentials .";
	}
	else
		 $err = $err . "<strong>Incorrect Credentials !</strong> Please Verify Your Access Credentials .";
	if($err != ''){
		echo '<script type="text/javascript">setTimeout(function(){ swal("Error !" ,"'.$err.'", "error");}, 100);</script>';
    }
	mysqli_close($db);
  }
 //function to do validation and triming data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
ob_end_flush();

?>


<!Doctype html>
<html>
	<head>
		<link href="login.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<title>Login Page</title>
	</head>
	<body>
		<div class = "container">
			<div class="wrapper">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form-signin">       
					<h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
					  <hr class="colorgraph"><br>

					  <input type="email" class="form-control" placeholder="Username" required name="username" />
					  <input type="password" class="form-control" maxlength="20" required name="Password" placeholder="Password" />     		  

					  <button class="btn btn-lg btn-primary btn-block"  name="Submit" value="Login" type="Submit">Login</button> 
					  <br/>
					   <a href="register.php" class="btn btn-link" style="float:right;text-decoration: none">Create New Account</a>
				</form>			
			</div>
		</div>

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.min.css">

		<!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
		
	</body>
</html>