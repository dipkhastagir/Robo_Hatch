<?php
// Database configuration
$host = "localhost";
$user = "root";        // Default username for MySQL in XAMPP
$pass = "";            // Default password for MySQL in XAMPP (empty by default)
$dbname = "Robo_Hatch";     // The database name you want to use or create

// Create a connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If connection fails, show error
}

// Create database if it does not exist
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS `$dbname`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci"; // Corrected the character set and collation

// Execute the query to create the database
if (!$conn->query($sqlCreateDb)) {
    die("Failed to create DB: " . $conn->error); // If database creation fails, show error
}

// Select the database for future queries
if (!$conn->select_db($dbname)) {
    die("Cannot select DB: " . $conn->error); // If database selection fails, show error
}

// Set the character set to UTF-8mb4 for better encoding support (including emojis)
$conn->set_charset("utf8mb4");

// Connection successful and database selected
echo "Successfully connected to the database '$dbname'!";
?>
