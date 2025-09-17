<?php
session_start();
include "config.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'team') {
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

// Fetch current team info
$stmt = $conn->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$team = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $country  = trim($_POST['country']);
    $members  = trim($_POST['team_members']);

    if ($email === "" || $phone === "" || $country === "" || $members === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("UPDATE teams SET email = ?, phone = ?, country = ?, team_members = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $email, $phone, $country, $members, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $team['email'] = $email;
            $team['phone'] = $phone;
            $team['country'] = $country;
            $team['team_members'] = $members;
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
    <title>Update Team Profile</title>
    <link rel="stylesheet" href="../css/team_profile_update_page.css">
</head>
<body>
<div class="profile-container">
    <h1>Update Team Profile</h1>

    <?php if ($success) echo "<p class='message success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='message error'>$error</p>"; ?>

    <form method="post">
        <label>Team Name (cannot be changed)</label>
        <input type="text" value="<?= htmlspecialchars($team['team_name']) ?>" disabled>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($team['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($team['phone']) ?>" required>

        <label>Country</label>
        <input type="text" name="country" value="<?= htmlspecialchars($team['country']) ?>" required>

        <label>Team Members</label>
        <textarea name="team_members" required><?= htmlspecialchars($team['team_members']) ?></textarea>

        <button type="submit">Update Profile</button>
    </form>
</div>
</body>
</html>
