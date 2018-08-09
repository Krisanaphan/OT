<?php
	// Create connection
	$conn = new mysqli("localhost", "root", "12345678", "otworker_test");

	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 
	// echo "Connected successfully";
?>
