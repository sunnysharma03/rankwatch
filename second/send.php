<?php

if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['head']) && !empty($_POST['head']) && isset($_POST['msg']) && !empty($_POST['msg']) ){
	
	$name = test_input($_POST['name']);
	$email = test_input($_POST['email']);
	$head = test_input($_POST['head']);
	$msg = test_input($_POST['msg']);
	
	
	require 'PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  					  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'Enter your Gmail username';        // SMTP username
	$mail->Password = 'Enter you gmail Password';         // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom('Gmail Email Address', 'Testing Mail');
	$mail->addAddress($email,$name);     			 	  // Add a recipient
	
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $head;
	$mail->Body    = $msg;
	
	$mail->AltBody = $msg;

	if(!$mail->send()) {
		echo 'Sorry';
	}
	else
		echo 'Send';
	
}
else
	echo 'Please Fill all Details';


//function to do validation and triming data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>