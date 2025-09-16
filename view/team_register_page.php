<?php
// Include database connection
include "config.php";

// ---------------- CREATE TABLE IF NOT EXISTS ----------------
$tableSql = "
CREATE TABLE IF NOT EXISTS teams (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    team_members TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->query($tableSql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// ---------------- HANDLE FORM SUBMISSION ----------------
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $team_name    = trim($_POST['team_name']);
    $password     = $_POST['password'];
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $country      = trim($_POST['country']);
    $team_members = trim($_POST['team_members']);

    // Validation
    if (empty($team_name) || empty($password) || empty($email) || empty($phone) || empty($country) || empty($team_members)) {
        $error = "Please fill in all required fields.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email must be valid.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM teams WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered. Use another email or log in.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO teams (team_name, password, email, phone, country, team_members) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $team_name, $hashed_password, $email, $phone, $country, $team_members);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'sign_in_page.php';</script>";
                $stmt->close();
                $conn->close();
                exit;
            } else {
                $error = "Error: " . htmlspecialchars($stmt->error);
                $stmt->close();
            }
        }
        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Registration - Robo Hatch</title>
    <link rel="stylesheet" href="../css/team_register_page.css">
</head>
<body>
<div class="container">
    <h1>Team Registration</h1>

    <form action="team_register_page.php" method="POST">
        <div class="form-group">
            <label for="team_name">Team Name:</label>
            <input type="text" name="team_name" id="team_name" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required>
        </div>

        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" name="country" id="country" required>
        </div>

        <div class="form-group">
            <label for="team_members">Team Members:</label>
            <textarea name="team_members" id="team_members" placeholder="John Doe - Role&#10;Jane Smith - Role" required></textarea>
        </div>

        <input type="submit" value="Register" class="btn">
    </form>

    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
</div>
</body>
</html>
