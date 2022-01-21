<?php

include_once("credentials.php"); // use credentials.php.sample

// check if the post is legit by comparing checksums
// if($_POST['checksum'] != md5(SALT.$_POST['timestamp'])) {
//	die("Checksum mismatch");
// }

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "connection successful.";