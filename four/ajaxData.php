<?php
session_start();
//Include database configuration file
include('config.php');

error_reporting(0);

// code to check email duplication

if(isset($_POST["email"]) && !empty($_POST["email"])){
    $email = test_input($_POST["email"]);
	$count = 0;
	$stm = $db->prepare("SELECT username FROM login WHERE username = ? ");
	$stm->bind_param("s",$email);
	$stm->execute();
	$stm->store_result();
	$count = $stm->num_rows;
	$stm->free_result();
	
    //Display result list
    if($count > 0){
        echo '1';
    }else{
        echo '0';
    }
}


if(isset($_POST['quer'])){
	$keyword = test_input($_POST['quer']);

	$sql = $db->prepare("SELECT DISTINCT name FROM countries WHERE name LIKE '%".$keyword."%' LIMIT 6 ");
	$sql->execute();
	$result = $sql->get_result();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$name[] = $row["name"];
		}
		echo json_encode($name);
	}
}

if(isset($_POST['countrys'])){
	$country = test_input($_POST['countrys']);
	//get country code
	
	$stm = $db->prepare("SELECT id FROM countries WHERE name = ? ");
	$stm->bind_param("s",$country);
	$stm->execute();
	$countrycode = $stm->get_result()->fetch_object()->id;
	$stm->free_result();
	
	$sql = $db->prepare("SELECT DISTINCT name FROM states WHERE country_id = ?");
	$sql->bind_param("s",$countrycode);
	$sql->execute();
	$result = $sql->get_result();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
		}
	}
	echo '<option value="">States are not available</option>';
}


//function to do validation and triming data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>