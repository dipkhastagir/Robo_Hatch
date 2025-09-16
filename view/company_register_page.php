<?php
// company_register_page.php
// Database connection (adjust path if needed)
include "config.php";

// Create companies table if not exists
$tableSql = "
CREATE TABLE IF NOT EXISTS companies (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    company_type VARCHAR(80) NOT NULL,
    verification_document TEXT NOT NULL,
    country VARCHAR(100) NOT NULL,
    additional_info TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->query($tableSql) !== TRUE) {
    die('Error creating table: ' . $conn->error);
}

$success = $error = "";

// Allowed company types for validation
$allowed_types = [
    'Hardware Prototyping',
    'Coding Integration',
    'Testing & Debugging',
    'Deployment',
    'Maintenance',
    'Research & Development',
    'Business'
];

function clean($v) { return trim($v); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = clean($_POST['company_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $company_type = clean($_POST['company_type'] ?? '');
    $verification_document = clean($_POST['verification_document'] ?? '');
    $country = clean($_POST['country'] ?? '');
    $additional_info = clean($_POST['additional_info'] ?? '');

    // Basic validation
    if ($company_name === '' || $email === '' || $password === '' || $company_type === '' || $verification_document === '' || $country === '') {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email address.";
    } elseif (!in_array($company_type, $allowed_types, true)) {
        $error = "Invalid company type selected.";
    } else {
        // Check email uniqueness
        $check = $conn->prepare("SELECT id FROM companies WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "Email already registered. Use another email or log in.";
            $check->close();
        } else {
            $check->close();
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert record
            $stmt = $conn->prepare("
                INSERT INTO companies (company_name, email, password, company_type, verification_document, country, additional_info)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            if ($stmt === false) {
                $error = "Database error: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param(
                    "sssssss",
                    $company_name,
                    $email,
                    $hashed_password,
                    $company_type,
                    $verification_document,
                    $country,
                    $additional_info
                );

                if ($stmt->execute()) {
                    $success = "Company registration successful! You can now log in.";
                    echo "<script>alert('Company registration successful!'); window.location.href = 'login.php';</script>";
                    $stmt->close();
                    $conn->close();
                    exit;
                } else {
                    $error = "Error: " . htmlspecialchars($stmt->error);
                    $stmt->close();
                }
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Company Registration - Robo Hatch</title>
    <link rel="stylesheet" href="../css/company_register_page.css">
</head>
<body>
    <div class="container">
        <header class="card-header">
            <h1>Company Registration</h1>
            <p class="subtitle">Companies must provide valid verification to be approved by admin.</p>
        </header>

        <form action="company_register_page.php" method="POST" autocomplete="off">
            <label for="company_name">Company Name</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="email">Contact Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="company_type">Company Type</label>
            <select id="company_type" name="company_type" required>
                <option value="" disabled selected>Select company type</option>
                <option>Hardware Prototyping</option>
                <option>Coding Integration</option>
                <option>Testing &amp; Debugging</option>
                <option>Deployment</option>
                <option>Maintenance</option>
                <option>Research &amp; Development</option>
                <option>Business</option>
            </select>

            <label for="verification_document">Verification Document (link / description)</label>
            <textarea id="verification_document" name="verification_document" required placeholder="e.g., official registration link, institutional email proof, scanned doc link, GitHub/org links"></textarea>

            <label for="country">Country</label>
            <input type="text" id="country" name="country" required>

            <label for="additional_info">Additional Info (optional)</label>
            <textarea id="additional_info" name="additional_info" placeholder="Short description about the company, capabilities, location, contacts"></textarea>

            <input type="submit" value="Register Company" class="btn">
        </form>

        <?php if (!empty($success)) { ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php } ?>

        <?php if (!empty($error)) { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>
    </div>
</body>
</html>
