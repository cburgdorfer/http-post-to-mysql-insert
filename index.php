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
	error_log("Checksum failed: ".$md5." != ".$data["Checksum"]);
	die("Checksum failed.");
}

// Check connection
if ($mysqli->connect_error) {
	error_log("Connection failed: " . $mysqli->connect_error);
  	die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO gravio_data 
	(Area, Layer, DataKind, LogicalDevice, SenderID, DataID, DateTime, Value)
VALUES 
	(?, ?, ?, ?, ?, ?, ?, ?)";
	
$stmt = $mysqli->prepare("INSERT INTO gravio_data (Area, Layer, DataKind, LogicalDevice, SenderID, DataID, DateTime, Value) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

error_log("line 48 test: " . $stmt->error);

$stmt->bind_param('ssssssss', 	$data["AreaName"],
								$data["LayerName"],
								$data["KindName"],
								$data["PhysicalDeviceName"],
								$data["PhysicalDeviceId"],
								$data["DataId"],
								$data["Timestamp"],
								$data["Value"]);


if ($stmt->execute() === true) {
  	error_log("New record created successfully:".$sql);
} else {
	error_log("Error: " . $stmt->error);
}

$stmt->close();