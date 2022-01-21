<?php

include_once("credentials.php"); // use credentials.php.sample

// check if the post is legit by comparing checksums
// if($_POST['checksum'] != md5(SALT.$_POST['timestamp'])) {
//	die("Checksum mismatch");
// }

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Get the JSON contents
$json = file_get_contents('php://input');

// decode the json data
$data = json_decode($json);

// Check if request is legit
if(md5($data["Area"].
	$data["DataID"].
	$data["DataKind"].
	$data["DateTime"].
	$data["Layer"].
	$data["LogicalDevice"].
	$data["SenderID"].
	$data["Value"].
	SALT) != $data["Checksum"]) {
		die("Checksum failed.");
}

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO gravio_data 
	(Area, Layer, DataKind, LogicalDevice, SenderID, DataID, DateTime, Value, created)
VALUES 
	(?, ?, ?, ?, ?, ?, ?, ?, NOW())";
	
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssssssss", 	$data["Area"],
								$data["DataID"],
								$data["DataKind"],
								$data["DateTime"],
								$data["Layer"],
								$data["LogicalDevice"],
								$data["SenderID"],
								$data["Value"]);


if ($stmt->execute(); === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}

$mysqli->close();