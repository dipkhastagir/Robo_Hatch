<?php
$servername = "localhost";   // usually localhost
$username   = "root";        // your DB username
$password   = "";            // your DB password
$dbname     = "robo_hatch";  // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
