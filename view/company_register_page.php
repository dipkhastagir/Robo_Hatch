<?php
include "config.php";

// Ensure table
$conn->query("
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
");

$success = $error = "";
$allowed_types = [
    'Hardware Prototyping','Coding Integration','Testing & Debugging',
    'Deployment','Maintenance','Research & Development','Business'
];
function clean($v){ return trim($v); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = clean($_POST['company_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $company_type = clean($_POST['company_type'] ?? '');
    $verification_document = clean($_POST['verification_document'] ?? '');
    $country = clean($_POST['country'] ?? '');
    $additional_info = clean($_POST['additional_info'] ?? '');

    if ($company_name === '' || $email === '' || $password === '' || $company_type === '' || $verification_document === '' || $country === '') {
        $error = "Please fill in all required fields.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif (!in_array($company_type, $allowed_types, true)) {
        $error = "Invalid company type selected.";
    } else {
        $check = $conn->prepare("SELECT id FROM companies WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered. Use another email or log in.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO companies (company_name, email, password, company_type, verification_document, country, additional_info)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssssss", $company_name, $email, $hashed_password, $company_type, $verification_document, $country, $additional_info);

            if ($stmt->execute()) {
                echo "<script>alert('Company registration successful! You can now log in.'); window.location.href = 'sign_in_page.php';</script>";
                $stmt->close();
                $conn->close();
                exit;
            } else {
                $error = "Database error: " . htmlspecialchars($stmt->error);
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

            <label for="verification_document">Verification Document</label>
            <textarea id="verification_document" name="verification_document" required placeholder="e.g., official registration link, scanned doc, GitHub/org links"></textarea>

            <label for="country">Country</label>
            <input type="text" id="country" name="country" required>

            <label for="additional_info">Additional Info (optional)</label>
            <textarea id="additional_info" name="additional_info" placeholder="Short description about the company, capabilities, location, contacts"></textarea>

            <input type="submit" value="Register Company" class="btn">
        </form>

        <?php if (!empty($success)) { ?><p class="success"><?= htmlspecialchars($success) ?></p><?php } ?>
        <?php if (!empty($error)) { ?><p class="error"><?= htmlspecialchars($error) ?></p><?php } ?>
    </div>
</body>
</html>
