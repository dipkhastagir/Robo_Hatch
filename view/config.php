<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Robo_Hatch"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Error message if connection fails
}
?>
