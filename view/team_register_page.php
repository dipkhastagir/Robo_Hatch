<?php
// team_register_page.php
include "config.php";

// Create teams table if not exists
$tableSql = "
CREATE TABLE IF NOT EXISTS teams (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    country VARCHAR(100) NOT NULL,
    project_focus VARCHAR(255) DEFAULT NULL,
    members_info TEXT NOT NULL,
    member_count INT(4) NOT NULL,
    verification_document TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->query($tableSql) !== TRUE) {
    die('Error creating table: ' . $conn->error);
}

$success = $error = "";

function clean($v) { return trim($v); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = clean($_POST['team_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $country = clean($_POST['country'] ?? '');
    $project_focus = clean($_POST['project_focus'] ?? '');
    $members_info = trim($_POST['members_info'] ?? '');
    $verification_document = clean($_POST['verification_document'] ?? '');

    // Count non-empty lines in members_info
    $lines = preg_split("/\r\n|\n|\r/", $members_info);
    $member_lines = array_filter(array_map('trim', $lines), function($l) { return $l !== ''; });
    $member_count = count($member_lines);

    // Validate required and format
    if ($team_name === '' || $email === '' || $password === '' || $country === '' || $members_info === '') {
        $error = "Team name, email, password, country and members details are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email address.";
    } elseif ($member_count < 10) {
        $error = "A team must have at least 10 members. Detected {$member_count} member(s). Please list at least 10 members (one per line).";
    } else {
        // Check email uniqueness
        $check = $conn->prepare("SELECT id FROM teams WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "Email already registered for another team. Use another email or log in.";
            $check->close();
        } else {
            $check->close();

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert team
            $stmt = $conn->prepare("
                INSERT INTO teams (team_name, email, password, country, project_focus, members_info, member_count, verification_document)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if ($stmt === false) {
                $error = "Database error: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param(
                    "ssssssis",
                    $team_name,
                    $email,
                    $hashed_password,
                    $country,
                    $project_focus,
                    $members_info,
                    $member_count,
                    $verification_document
                );

                if ($stmt->execute()) {
                    $success = "Team registration successful! You can now log in.";
                    echo "<script>alert('Team registration successful!'); window.location.href = 'login.php';</script>";
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
    <title>Team Registration - Robo Hatch</title>
    <link rel="stylesheet" href="../css/team_register_page.css">
</head>
<body>
    <div class="container">
        <header class="card-header">
            <h1>Team Registration</h1>
            <p class="subtitle">Teams must list at least <strong>10 members</strong> (one per line: Name — Field — Short skill/note).</p>
        </header>

        <form action="team_register_page.php" method="POST" autocomplete="off">
            <label for="team_name">Team Name</label>
            <input type="text" id="team_name" name="team_name" required>

            <label for="email">Contact Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="country">Country</label>
            <input type="text" id="country" name="country" required>

            <label for="project_focus">Project Focus / Specialization (optional)</label>
            <input type="text" id="project_focus" name="project_focus" placeholder="e.g., ROS, autonomous navigation">

            <label for="members_info">Members (one per line)</label>
            <textarea id="members_info" name="members_info" placeholder="John Doe — Mechatronics — SLAM research
Jane Smith — CS — ROS modules
..." required></textarea>
            <small class="help">List each member on its own line: <em>Name — Field — Short note</em></small>

            <label for="verification_document">Verification Document (link/description)</label>
            <textarea id="verification_document" name="verification_document" placeholder="Provide proof: institutional email, repo links, public project pages, etc."></textarea>

            <input type="submit" value="Register Team" class="btn">
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
