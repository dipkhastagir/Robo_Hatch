<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'team') 
{
    header("Location: sign_in_page.php");
    exit;
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Team Dashboard </title>
    <link rel="stylesheet" href="../css/team_dashboard_page.css">
</head>
<body>
<div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Team Panel</h2>
        <ul>
            <li><a href="team_profile_summary_page.php" target="content">👥 Profile Summary</a></li>
            <li><a href="team_profile_update_page.php" target="content">📋 Update Profile</a></li>
            <li><a href="team_profile_members_information_page.php" target="content">📋 Team Members Information</a></li>
            <li><a href="add_team_members_page.php" target="content">➕ Add Team Members</a></li>
            <li><a href="team_projects_page.php" target="content">📂 Collaboration</a></li>
            <li><a href="team_proposals_page.php" target="content">📑 Proposals</a></li>
            <li><a href="team_messages_page.php" target="content">💬 Messages</a></li>
            <li><a href="team_earnings_page.php" target="content">💰 Team Earnings</a></li>
            <li><a href="team_settings_page.php" target="content"> ⚙ Settings</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <iframe name="content" src="team_profile_summary_page.php"></iframe>
    </div>
</div>
</body>
</html>
