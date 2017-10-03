<?php
	session_start();

 	//Include database configuration file
	include('config.php');
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		//fetchin users_profile info
		$stmt = $db->prepare("SELECT * FROM profile WHERE username = ?");
		$stmt->bind_param("s",$_SESSION['username']);
		$stmt->execute();
		$res = $stmt->get_result();
		$row = $res->fetch_assoc();
		$stmt->free_result();
		
	 }
	else
		header("Location: login.php");
?>
<!Doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8">
		<title><?php echo $row['name'] ?> | Profile</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
		<h1 align="center">Welcome <?php echo $row['name'] ?></h1>
		<div style="padding-top: 50px"></div>
		
		<div class="form-group col-md-6">
			<label>Name</label>
			<input type="text" class="form-control" maxlength="100" pattern="[A-Za-z\s]{10,100}" value="<?php echo $row['name'] ?>">
	    </div>
	 
	    <!--title-->
	    <div class="form-group col-md-6">
	    	<label>Email</label>
	    	<input type="email" class="form-control" maxlength="255" value="<?php echo $row['email'] ?>" >
	    </div>
	    <!--desc-->
	    <div class="form-group col-md-4">
			<label>Mobile</label>
			<input type="text" class="form-control" maxlength="12"  value="<?php echo $row['mobile'] ?>" >
		</div>
	    <!--keywords-->
	    <div class="form-group col-md-4">
			<label>Age</label>
			<input type="text" class="form-control"  value="<?php echo $row['age'] ?>">
		</div>
	    <!--IP Address:-->
	    <div class="form-group col-md-4">
	    	<label>Gender</label>
	    	<select class="form-control">
	    		<option value="" >Please select your Gender</option>
					<option value="Male" <?php echo ($row['gender'] == 'Male')?"selected":"" ?> >Male</option>
					<option value="Female" <?php echo ($row['gender'] == 'Female')?"selected":"" ?> >Female</option>
	    	</select>
	    </div>
	    <!--HTTP Code-->
	    <div class="form-group col-md-6">
	    	<label>Country</label>
	    	<input type="text" class="form-control" value="<?php echo $row['country'] ?>" >
	    </div>
	    <!--URL Load Time:-->
	    <div class="form-group col-md-6">
	    	<label>State</label>
	    	<input type="text" class="form-control" value="<?php echo $row['state'] ?>">
	    </div>
	    <!--Internal and External Link lists : -->
	    <div class="form-group col-md-12">
			<label>Address</label>
			<textarea class="form-control" style="resize: none" rows="5"><?php echo $row['address'] ?></textarea>
		</div>
		<div style="float: right">
			<a href="logout.php"><button class="btn btn-danger">Logout</button></a>
		</div>
	</div>
	<!--scripts-->
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
	</body>
</html>

