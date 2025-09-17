<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in_page.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Freelancer Dashboard - Robo Hatch</title>
    <link rel="stylesheet" href="../css/freelancer_dashboard_page.css">
</head>
<body>
<div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Robo Hatch</h2>
        <ul>
            <li><a href="freelancer_profile_summary_page.php" target="content">👤 Profile Summary</a></li>
            <li><a href="freelancer_profile_update_page.php" target="content">👤 Update Profile</a></li>
            <li><a href="freelancer_projects_page.php" target="content">📂 Project Management</a></li>
            <li><a href="freelancer_proposals_page.php" target="content">📑 Proposal System</a></li>
            <li><a href="freelancer_portfolio_page.php" target="content">🎨 Portfolio Updates</a></li>
            <li><a href="freelancer_messages_page.php" target="content">💬 Messaging</a></li>
            <li><a href="freelancer_earnings_page.php" target="content">💰 Earnings</a></li>
            <li><a href="freelancer_settings_page.php" target="content">⚙ Settings</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <iframe name="content" src="freelancer_profile_update_page.php"></iframe>
    </div>
</div>
</body>
</html>
