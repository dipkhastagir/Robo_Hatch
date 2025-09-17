<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

// ✅ Fix for undefined company_name
$companyName = $_SESSION['company_name'] ?? ($_SESSION['username'] ?? 'Unknown Company');

// Fetch current company data
$stmt = $conn->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

// Allowed company types
$allowed_types = [
    'Hardware Prototyping',
    'Coding Integration',
    'Testing & Debugging',
    'Deployment',
    'Maintenance',
    'Research & Development',
    'Business'
];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_GET['section'] ?? '') === "update") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $company_type = trim($_POST['company_type']);
    $verification_document = trim($_POST['verification_document']);
    $country = trim($_POST['country']);
    $additional_info = trim($_POST['additional_info']);

    if ($email === "" || $company_type === "" || $verification_document === "" || $country === "") {
        $error = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($company_type, $allowed_types)) {
        $error = "Invalid company type selected.";
    } else {
        if ($password !== "") {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE companies 
                SET email = ?, password = ?, company_type = ?, verification_document = ?, country = ?, additional_info = ? 
                WHERE id = ?");
            $stmt->bind_param("ssssssi", $email, $hashed_password, $company_type, $verification_document, $country, $additional_info, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE companies 
                SET email = ?, company_type = ?, verification_document = ?, country = ?, additional_info = ? 
                WHERE id = ?");
            $stmt->bind_param("sssssi", $email, $company_type, $verification_document, $country, $additional_info, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $company['email'] = $email;
            $company['company_type'] = $company_type;
            $company['verification_document'] = $verification_document;
            $company['country'] = $country;
            $company['additional_info'] = $additional_info;
        } else {
            $error = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Dashboard - Robo Hatch</title>
    <link rel="stylesheet" href="../css/company_dashboard_page.css">
</head>
<body>
<div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Company Panel</h2>
        <ul>
            <li><a href="?section=profile">🏢 Profile Summary</a></li>
            <li><a href="?section=update">✏ Update Profile</a></li>
            <li><a href="?section=projects">📂 Project Management</a></li>
            <li><a href="?section=buy">🛒 Buy Ideas/Designs</a></li>
            <li><a href="?section=outsourcing">🤝 Outsourcing</a></li>
            <li><a href="?section=messages">💬 Messages</a></li>
            <li><a href="?section=expenses">💰 Expenses</a></li>
            <li><a href="?section=rnd">🔬 R&D & Maintenance</a></li>
            <li><a href="?section=settings">⚙ Settings</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php 
        $section = $_GET['section'] ?? 'profile';

        if ($section === 'profile') { ?>
            <h1>🏢 Company Profile</h1>
            <div class="content-box">
                <p><strong>Company Name:</strong> <?= htmlspecialchars($companyName) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($company['email']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($company['company_type']) ?></p>
                <p><strong>Verification:</strong> <?= htmlspecialchars($company['verification_document']) ?></p>
                <p><strong>Country:</strong> <?= htmlspecialchars($company['country']) ?></p>
                <p><strong>Additional Info:</strong> <?= htmlspecialchars($company['additional_info']) ?></p>
            </div>

        <?php } elseif ($section === 'update') { ?>
            <h1>✏ Update Profile</h1>
            <div class="content-box">
                <?php if ($success) echo "<p class='message success'>$success</p>"; ?>
                <?php if ($error) echo "<p class='message error'>$error</p>"; ?>

                <form method="post">
                    <label>Company Name (cannot be changed)</label>
                    <input type="text" value="<?= htmlspecialchars($companyName) ?>" disabled>

                    <label>Contact Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($company['email']) ?>" required>

                    <label>Password (leave blank if unchanged)</label>
                    <input type="password" name="password" placeholder="Enter new password">

                    <label>Company Type</label>
                    <select name="company_type" required>
                        <option value="">Select company type</option>
                        <?php foreach ($allowed_types as $type) { ?>
                            <option value="<?= $type ?>" <?= $company['company_type'] === $type ? 'selected' : '' ?>>
                                <?= $type ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>Verification Document</label>
                    <textarea name="verification_document" required><?= htmlspecialchars($company['verification_document']) ?></textarea>

                    <label>Country</label>
                    <input type="text" name="country" value="<?= htmlspecialchars($company['country']) ?>" required>

                    <label>Additional Info (optional)</label>
                    <textarea name="additional_info"><?= htmlspecialchars($company['additional_info']) ?></textarea>

                    <button type="submit">Update Profile</button>
                </form>
            </div>

        <?php } elseif ($section === 'projects') { ?>
            <h1>📂 Project Management</h1>
            <div class="content-box">
                <button>Create New Project</button>
                <p>No active projects yet.</p>
            </div>

        <?php } elseif ($section === 'buy') { ?>
            <h1>🛒 Buy Ideas/Designs</h1>
            <div class="content-box">
                <p>Search and purchase freelancer research, CAD designs, or code.</p>
            </div>

        <?php } elseif ($section === 'outsourcing') { ?>
            <h1>🤝 Outsourcing</h1>
            <div class="content-box">
                <p>No outsourcing contracts yet.</p>
            </div>

        <?php } elseif ($section === 'messages') { ?>
            <h1>💬 Messages</h1>
            <div class="content-box">
                <p>No messages yet.</p>
            </div>

        <?php } elseif ($section === 'expenses') { ?>
            <h1>💰 Expenses</h1>
            <div class="content-box">
                <p>Total Spent: $0.00</p>
            </div>

        <?php } elseif ($section === 'rnd') { ?>
            <h1>🔬 R&D & Maintenance</h1>
            <div class="content-box">
                <p>No active R&D tasks yet.</p>
            </div>

        <?php } elseif ($section === 'settings') { ?>
            <h1>⚙ Settings</h1>
            <div class="content-box">
                <form method="post">
                    <label>Change Password:</label>
                    <input type="password" name="new_password" placeholder="New Password">
                    <button type="submit">Update</button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
