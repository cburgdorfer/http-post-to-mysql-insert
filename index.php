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
$data = json_decode($json, true);

// Check if request is legit
$md5 = md5($data["AreaName"].
	$data["Data"].
	$data["KindName"].
	$data["PhysicalDeviceName"].
	$data["PhysicalDeviceId"].
	$data["DataId"].
	$data["Timestamp"].
	$data["LayerName"].
	SALT);

if($md5 != $data["Checksum"]) {
	error_log("Checksum failed: ".$md5." \n\nmd5(".$data["AreaName"].
	$data["Data"].
	$data["KindName"].
	$data["PhysicalDeviceName"].
	$data["PhysicalDeviceId"].
	$data["DataId"].
	$data["Timestamp"].
	$data["LayerName"].
	SALT.") / ".$data["Checksum"] . "Checkstring from Gravio: \n\n".$data['Checkstring']);

		die("Checksum failed.");
}

// Check connection
if ($mysqli->connect_error) {
	error_log("Connection failed: " . $mysqli->connect_error);
  	die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO gravio_data 
	(Area, Layer, DataKind, LogicalDevice, SenderID, DataID, DateTime, Value, created)
VALUES 
	(?, ?, ?, ?, ?, ?, ?, ?, NOW())";
	
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssssssss', 	$data["Area"],
								$data["DataID"],
								$data["DataKind"],
								$data["DateTime"],
								$data["Layer"],
								$data["LogicalDevice"],
								$data["SenderID"],
								$data["Value"]);


if ($stmt->execute() === TRUE) {
  	error_log("New record created successfully:".$sql);
} else {
	error_log("Error: " . $sql . "<br>" . $mysqli->error);
}

$stmt->close();