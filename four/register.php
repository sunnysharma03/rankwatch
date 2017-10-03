<?php

 session_start();

 //Include database configuration file
 include('config.php');

	//if he is already loggedin
 if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	 	header("Location: profile.php");	
 }

if(isset($_POST['register'])){
	
	//getting hashed password
	$options = [
	'cost' => 11,
	];
	$link = password(10);
	$password_hash = password_hash($link, PASSWORD_BCRYPT, $options);
	
	$err = '';
	$firstname = test_input(ucname($_POST["first_name"]));
	$lastname = test_input(ucname($_POST["last_name"]));
	$email = test_input(mb_strtolower($_POST["email"]));
	$phone = test_input($_POST["phone"]);
	$age = test_input($_POST["age"]);
	$gender = test_input($_POST["gender"]);
	$password1 = test_input($_POST["password1"]);
	$password2 = test_input($_POST["password2"]);
	$state = test_input(ucfirst($_POST["statename"]));
	$country = test_input(ucfirst($_POST["countryname"]));
	$address =  mysqli_real_escape_string($db, test_input($_POST["address"]));
	
	//name
	if(isset($firstname) && !empty($firstname) && (strlen($firstname) >3) && (strlen($firstname) < 50)){
	}
	else
		$err = $err . "Only Alphabets are allowed in First Name </br>";
	
	//name
	if(isset($lastname) && !empty($lastname) && (strlen($lastname) >3) && (strlen($lastname) < 50)){
	}
	else
		$err = $err . "Only Alphabets are allowed in Last Name </br>";
	
	//email
	if(isset($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && (strlen($email) >10) && (strlen($email) < 255) ){
	}
	else
		$err = $err . "Invalid Email address </br>";
	
	//phone
	if(isset($phone) && !empty($phone) && preg_match("/^[0-9]*$/",$phone)){
	}
	else
		$err = $err . "Only Numbers are allowed in Phone </br>";
	
	//age
	if(isset($age) && !empty($age) && preg_match("/^[0-9]*$/",$age) && ($age < '99' && $age > '09')){
	}
	else
		$err = $err . "Only Numbers are allowed in Age </br>";
	
	//gender
	if(isset($gender) && !empty($gender) && ($gender == 'Male' || $gender == 'Female')){	
	}
	else
		$err = $err . "Only Male and Female options are allowed </br>";
	
	//state
	if(isset($state) && !empty($state) && (strlen($state) < 31) && preg_match("/^[a-zA-Z ]*$/",$state)){	
	}
	else
		$err = $err . "Only Available States are allowed </br>";
	
	//country
	if(isset($country) && !empty($country) && (strlen($country) < 151) && preg_match("/^[a-zA-Z ]*$/",$country)){	
	}
	else
		$err = $err . "Only Available Countries are allowed </br>";
	
	if(isset($password1) && !empty($password1) && (strlen($password1) > 8) && (strlen($password1) < 16) ){
	}
	else
		$err = $err . "There is a problem with password !";
	
	if(isset($password2) && !empty($password2) && ($password1 == $password2)){
	}
	else
		$err = $err . "Passwords Do not Match !";
	
	if($err == ''){
		$fullname = $firstname .' '.$lastname;
		//now check for email
		$count1 = 0;
		$stm = $db->prepare("SELECT username FROM login WHERE username = ? ");
		$stm->bind_param("s",$email);
		$stm->execute();
		$stm->store_result();
		$count1 = $stm->num_rows;
		$stm->free_result();
		
		if($count1 == 0){
			//lets go for create
			$flag = 0;
			if (($stmt = $db->prepare("INSERT INTO login(username,password,last_login,created) VALUES (?,?,'".date("Y-m-d")."','".date("Y-m-d")."')"))) {
				if ($stmt->bind_param("ss",$email,$password_hash)) {
					if ($stmt->execute()) {
						//now insert into profile table
						if (($stm = $db->prepare("INSERT INTO profile(username,name,email,mobile,age,gender,address,country,state) VALUES (?,?,?,?,?,?,?,?,?)"))) {
							if ($stm->bind_param("sssssssss",$email,$fullname,$email,$phone,$age,$gender,$address,$country,$state)) {
								if ($stm->execute()) {
										$_SESSION['register'] = true;
										$_SESSION['username'] = $email;
										$_SESSION['name'] = $fullname;
										header("Location: welcome.php");
								}
								else
									$flag = 1;
							}
							else
								$flag = 1;
						}
						else
							$flag = 1;
					}
					else
						$flag = 1;
				}
				else
					$flag = 1;
			}
			else
				$flag = 1;

			if($flag == 1){
				$err = $err . "Sorry Something Went Wrong While Processing Your Request. Please Contact Developer About this Issue !";
			}
		}
		else if($count1 != 0){
			$err = $err . "Email is already registered !";
		}
	}
	else if($err != ''){
		echo '<script type="text/javascript">setTimeout(function(){ swal("Error !" ,"'.$err.'", "error");}, 100);</script>';
		echo $firstname;
    }
	
}
//function to produce a random number password by using md5 algo
function password($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
  return $str;
}
 //function to do validation and triming data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//function to change the lowercase data to capitalize the first letter of every word
function ucname($string) {
    $string =ucwords(strtolower($string));

    foreach (array('-', '\'') as $delimiter) {
      if (strpos($string, $delimiter)!==false) {
        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
      }
    }
    return $string;
}

?>


<!Doctype html>
<html>
	<head>
		<link href="register.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<title>Register Page</title>
		<style>
		.errspan {
			float: right;
			margin-right: 8px;
			margin-top: -37px;
			position: relative;
			z-index: 3;
			}
		</style>
	</head>
	<body>
		
		
		<div class="container">

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form role="form" autocomplete="off" method="post" onSubmit="return checkRegistration()">
			<h2>Please Sign Up <small>It's free and always will be.</small></h2>
			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
                        <input type="text" maxlength="49" pattern="[A-Za-z]{4,49}" name="first_name" id="first_name" class="form-control input-lg" title="Enter only Alphabets" required placeholder="First Name" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" maxlength="49" pattern="[A-Za-z]{4,49}" name="last_name" id="last_name" class="form-control input-lg" title="Enter only Alphabets" required placeholder="Last Name">
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="text" name="phone" id="phone" class="form-control input-lg" placeholder="Phone Number" pattern="^\d{10}$" title="Enter 10 digit Phone number" required>
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" maxlength="255" required style="text-transform: lowercase;display: inline-block" onBlur="checkemail()" >
				<div id="email-loading" class="errspan" style="display: none">
					<i id="email-i"></i>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
                        <input type="numer" name="age" id="age" class="form-control input-lg" placeholder="Enter Age" min="09" step="01" max="99" required >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<select  class="form-control input-lg" required id="gender" name="gender">
							<option>Please Select Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
                        <input style="padding: 22px" class="form-control typeahead" type="text" placeholder="Start Enter Country Name" id="countryname" onChange="fetchstate()" name="countryname" maxlength="100" required >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<select class="form-control input-lg" id="statename" name="statename" required>
							<option>Select Country</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<textarea rows="2" maxlength="255" style="resize: none" placeholder="Enter Address" class="form-control input-lg" required id="address" name="address"></textarea>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password1" id="password1" maxlength="15" onFocus="navone()" required onChange="passwordone()" class="form-control input-lg" placeholder="Enter Password" tabindex="5">
					</div>
				<div class="form-row form-row-wide" id="rules" style="display: none">
					<label>Password must follow these rules :</label>
					<ul class="list-1">
						<li>Must contain 1 Uppercase</li>
						<li>Must contain 1 Lowercase</li>
						<li>Must contain 1 Numeric value</li>
						<li>Length must be between 8 - 15 characters</li>
					</ul>
				</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" required type="password" name="password2" id="password2" maxlength="15" onChange="passwordtwo()" class="form-control input-lg" placeholder="Confirm Password" tabindex="6">
					</div>
				</div>
			</div>
			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-md-6"><input type="submit" name="register" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
				<div class="col-xs-12 col-md-6"><a href="login.php" class="btn btn-success btn-block btn-lg">Sign In</a></div>
			</div>
		</form>
	</div>
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
		
		<!--typeahead-->
		<script src="typeahead.js"></script>

		<script>
			$(document).ready(function () {
				$('#countryname').typeahead({
					limit: 5,
					source: function (query, result) {
						$.ajax({
							url: "ajaxData.php",
							data: 'quer=' + query,            
							dataType: "json",
							type: "POST",
							success: function (data) {
								result($.map(data, function (item) {
									return item;
								}));
							}
						});
					}
				});
			});
			
			function fetchstate() {
				var country = $('#countryname').val();
				console.log(country);
				if(country){
					$.ajax({
						type:'POST',
						url:'ajaxData.php',
						data: 'countrys=' + country,
						success: function(html){
							console.log(html);
							$('#statename').html(html);
						}
					}); 
				}else{
					$('#statename').html('<option value="">Select Country First</option>');
				}
			}
			
			function checkemail() {
				var name = $("#email").val();
				if(name.length > 10 )
				{
					$('#email-loading').show();
					$("#email-i").addClass("fa fa-spinner fa-pulse fa-2x fa-fw");

					jQuery.ajax({
						url: "ajaxData.php",
						data:'email='+$("#email").val(),
						type: "POST",
						success:function(data){
							console.log(data);
							if(data == 1) {
								$("#email-i").removeClass();
								$("#email-i").addClass("fa fa-close fa-2x");
								$("#email-i").css("color", "red");

							}else{
								$("#email-i").removeClass();
								$("#email-i").addClass("fa fa-check fa-2x");
								$("#email-i").css("color", "green");
							}

						},
						error:function (){}
					});
				}
				else{
					$("#email-loading").hide();
					$("#email-i").removeClass();
					$("#email-i").css("color", "");
					if(name.length != 0)
					swal(
					  'Email Seems Incorrect !',
					  'The length of Email Must be more than 10 characters long ',
					  'warning'
					)
				}
		}
	function passwordone(){
		var pass = $("#password1").val();
		if(pass.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/)){
		}
		else if(pass.length != 0){
			swal(
			  'Password Seems Incorrect !',
			  'Password must be between 8 to 15 characters and contain at least one lowercase letter,     one uppercase letter, one numeric digit',
			  'warning'
			)
		}
	}
	
	$("#password1").focusout(function() {
		$('#rules').hide();
	})

	function passwordtwo(){
		var original = $("#password1").val();
		var varify = $("#password2").val();
		if(original != varify){
			swal(
				  'Password Match Seems Incorrect !',
				  'Password Do not Match !',
				  'warning'
				)
		}
	}
	
	function navone(){
		$('#rules').show();
	}
			
		</script>
		
	</body>
</html>