<?php

include_once("credentials.php"); // use credentials.php.sample

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

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

// check if checksum is correct
if($md5 != $data["Checksum"]) {
	error_log("Checksum failed: " . $md5 . " != " . $data["Checksum"]);
	die("Checksum failed.");
}

// Check connection
if($mysqli->connect_error) {
	error_log("Connection failed: " . $mysqli->connect_error);
  	die("Connection failed: " . $mysqli->connect_error);
}

// Construct SQL
$sql = "INSERT INTO gravio_data 
	(AreaName, LayerName, DataKind, PhysicalDeviceName, PhysicalDeviceId, DataId, Timestamp, Data)
VALUES 
	(?, ?, ?, ?, ?, ?, ?, ?)";
	
$stmt = $mysqli->prepare($sql);

$stmt->bind_param('ssssssss', 	$data["AreaName"],
								$data["LayerName"],
								$data["KindName"],
								$data["PhysicalDeviceName"],
								$data["PhysicalDeviceId"],
								$data["DataId"],
								date("Y-m-d H:i:s", $data["Timestamp"]),
								$data["Data"]);

if(!$stmt->execute()) {
	error_log("Error: " . $stmt->error);
}

$stmt->close();
