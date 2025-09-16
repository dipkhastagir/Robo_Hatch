<?php
// Start the session
session_start();

// Include the database connection file
include('config.php');

// Initialize variables
$error = "";
$success = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $role = "freelancer"; // Role is already set as freelancer
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $skills = $_POST["skills"];
    $hourly_rate = $_POST["hourly_rate"];
    $portfolio = $_POST["portfolio"];

    // Validation
    if (empty($username) || empty($password) || empty($email) || empty($skills) || empty($hourly_rate) || empty($portfolio)) {
        $error = "All fields are required!";
    } else {
        // Insert into the database (hashed password for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (role, username, password, email, skills, hourly_rate, portfolio)
                VALUES ('$role', '$username', '$hashed_password', '$email', '$skills', '$hourly_rate', '$portfolio')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful! You can now log in.";
            header("Location: login.php"); // Redirect to login page
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Freelancer Registration</title>
    <link rel="stylesheet" href="../css/freelancer_register_page.css">
</head>
<body>
    <!-- Registration Form -->
    <section class="register-container">
        <h1>Freelancer Registration</h1>
        <form method="POST" action="freelancer_register_page.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="skills">Skills:</label>
            <input type="text" name="skills" id="skills" placeholder="E.g., Project Planning, CAD Design" required>

            <label for="hourly_rate">Hourly Rate:</label>
            <input type="number" name="hourly_rate" id="hourly_rate" required>

            <label for="portfolio">Portfolio/Experience:</label>
            <textarea name="portfolio" id="portfolio" placeholder="Link to portfolio or previous work" required></textarea>

            <input type="submit" value="Register" class="btn">
        </form>

        <!-- Error/Success messages -->
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
    </section>
</body>
</html>
