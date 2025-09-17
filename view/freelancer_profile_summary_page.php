<?php
session_start();
include "config.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch company data
$stmt = $conn->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Profile Summary</title>
    <link rel="stylesheet" href="../css/company_profile_summary_page.css">
</head>
<body>
<div class="profile-container">
    <h1>🏢 Company Profile Summary</h1>

    <div class="profile-card">
        <p><strong>Company Name:</strong> <?= htmlspecialchars($company['company_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($company['email']) ?></p>
        <p><strong>Company Type:</strong> <?= htmlspecialchars($company['company_type']) ?></p>
        <p><strong>Verification Document:</strong> 
            <a href="<?= htmlspecialchars($company['verification_document']) ?>" target="_blank">
                <?= htmlspecialchars($company['verification_document']) ?>
            </a>
        </p>
        <p><strong>Country:</strong> <?= htmlspecialchars($company['country']) ?></p>
        <p><strong>Additional Info:</strong> <?= htmlspecialchars($company['additional_info']) ?></p>
    </div>
</div>
</body>
</html>
