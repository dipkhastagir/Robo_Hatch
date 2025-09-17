<?php
session_start();
include "config.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'team') {
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch team info
$stmt = $conn->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$team = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Profile Summary</title>
    <link rel="stylesheet" href="../css/team_profile_summary_page.css">
</head>
<body>
<div class="profile-container">
    <h1>👥 Team Profile Summary</h1>

    <div class="profile-box">
        <p><strong>Team Name:</strong> <?= htmlspecialchars($team['team_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($team['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($team['phone']) ?></p>
        <p><strong>Country:</strong> <?= htmlspecialchars($team['country']) ?></p>
        <p><strong>Members:</strong></p>
        <pre><?= htmlspecialchars($team['team_members']) ?></pre>
    </div>
</div>
</body>
</html>
