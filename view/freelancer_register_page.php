<?php
// Include the database connection file
include "config.php"; 

// Table creation query (make sure the table is created if it does not exist)
$tableSql = "
CREATE TABLE IF NOT EXISTS freelancers (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    skills TEXT NOT NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    portfolio TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Execute the query to create the freelancers table
if ($conn->query($tableSql) !== TRUE) {
    die('Error creating table: ' . $conn->error);  // If there's an error creating the table
}

// Initialize success and error variables
$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $skills = $_POST['skills'];
    $hourly_rate = $_POST['hourly_rate'];
    $portfolio = $_POST['portfolio'];

    // Basic form validation
    if (empty($username) || empty($password) || empty($email) || empty($skills) || empty($hourly_rate) || empty($portfolio)) {
        $error = "All fields are required.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL to insert data into freelancers table
        $sql = "INSERT INTO freelancers (username, password, email, skills, hourly_rate, portfolio) 
                VALUES ('$username', '$hashed_password', '$email', '$skills', '$hourly_rate', '$portfolio')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful! You can now log in.";
            echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";  // Alert the user and redirect to login page
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Freelancer Registration - Robo Hatch</title>
    <link rel="stylesheet" href="../css/freelancer_register_page.css">
</head>
<body>

    <div class="container">
        <h1>Freelancer Registration</h1>

        <form action="freelancer_register_page.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="skills">Skills:</label>
            <input type="text" name="skills" id="skills" required>

            <label for="hourly_rate">Hourly Rate:</label>
            <input type="number" name="hourly_rate" id="hourly_rate" required>

            <label for="portfolio">Portfolio Link:</label>
            <textarea name="portfolio" id="portfolio" required></textarea>

            <input type="submit" value="Register" class="btn">
        </form>

        <!-- Display success or error message -->
        <?php if (!empty($success)) { echo "<p style='color:green;'>$success</p>"; } ?>
        <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </div>

</body>
</html>
