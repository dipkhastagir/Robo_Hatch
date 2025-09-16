<?php
// Include database connection
include "config.php"; 

// ------------------ Ensure Table Exists ------------------
$tableSql = "
CREATE TABLE IF NOT EXISTS freelancers (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL,
    portfolio TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->query($tableSql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// ------------------ Ensure 'phone' column exists ------------------
$columnsToAdd = [
    "phone" => "VARCHAR(20) NOT NULL AFTER email",
    "freelancer_type" => "VARCHAR(50) NOT NULL AFTER phone"
];

foreach ($columnsToAdd as $column => $definition) {
    $columnCheck = "SHOW COLUMNS FROM freelancers LIKE '$column'";
    $result = $conn->query($columnCheck);
    if ($result->num_rows == 0) {
        $alterSql = "ALTER TABLE freelancers ADD COLUMN $column $definition";
        if ($conn->query($alterSql) !== TRUE) {
            die("Error adding column '$column': " . $conn->error);
        }
    }
}

// ------------------ Handle Form Submission ------------------
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username        = trim($_POST['username']);
    $password        = $_POST['password'];
    $email           = trim($_POST['email']);
    $phone           = trim($_POST['phone']);
    $freelancer_type = $_POST['freelancer_type'];
    $hourly_rate     = trim($_POST['hourly_rate']);
    $portfolio       = trim($_POST['portfolio']);

    // Validation
    if (empty($username) || empty($password) || empty($email) || empty($phone) || empty($freelancer_type) || empty($hourly_rate) || empty($portfolio)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@') === false || strpos($email, '.') === false) {
        $error = "Email must be valid and contain '@' and '.'";
    } else {
        // Check username existence
        $check = $conn->prepare("SELECT id FROM freelancers WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already registered. Use another username.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO freelancers (username, password, email, phone, freelancer_type, hourly_rate, portfolio) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssds", $username, $hashed_password, $email, $phone, $freelancer_type, $hourly_rate, $portfolio);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'sign_in_page.php';</script>";
                $stmt->close();
                $conn->close();
                exit;
            } else {
                $error = "Error: " . htmlspecialchars($stmt->error);
            }
        }
        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Freelancer Registration - Robo Hatch</title>
    <link rel="stylesheet" href="../css/freelancer_register_page.css">
</head>
<body>
    <div class="container">
        <h1>Freelancer Registration</h1>

        <form action="freelancer_register_page.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="At least 8 characters" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="example@mail.com" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" placeholder="+1234567890" required>
            </div>

            <div class="form-group">
                <label for="freelancer_type">Freelancer Type:</label>
                <select id="freelancer_type" name="freelancer_type" required>
                    <option value="">-- Choose Type --</option>
                    <option value="planner">Project Planners and Researchers</option>
                    <option value="designer">3D Model Designers & CAD Engineers</option>
                    <option value="coding">Software Development</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hourly_rate">Hourly Rate:</label>
                <input type="number" name="hourly_rate" id="hourly_rate" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="portfolio">Portfolio Link:</label>
                <textarea name="portfolio" id="portfolio" required></textarea>
            </div>

            <input type="submit" value="Register" class="btn">
        </form>

        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
