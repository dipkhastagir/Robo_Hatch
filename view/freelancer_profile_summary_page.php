<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') 
{
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM freelancers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Freelancer Profile Summary</title>
    <link rel="stylesheet" href="../css/freelancer_profile_summary_page.css">
</head>
<body>
<div class="profile-container">
    <h1>👤 Profile Summary</h1>

    <div class="profile-card">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p><strong>Freelancer Type:</strong> <?= htmlspecialchars($user['freelancer_type']) ?></p>
        <p><strong>Hourly Rate:</strong> $<?= htmlspecialchars($user['hourly_rate']) ?>/hr</p>
        <p><strong>Portfolio:</strong> 
            <a href="<?= htmlspecialchars($user['portfolio']) ?>" target="_blank">
                <?= htmlspecialchars($user['portfolio']) ?>
            </a>
        </p>
    </div>
</div>
</body>
</html>
