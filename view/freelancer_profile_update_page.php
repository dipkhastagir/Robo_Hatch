<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') 
{
    header("Location: sign_in_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

$stmt = $conn->prepare("SELECT * FROM freelancers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $freelancer_type = trim($_POST['freelancer_type']);
    $hourly_rate = trim($_POST['hourly_rate']);
    $portfolio = trim($_POST['portfolio']);

    if ($email === "" || $phone === "" || $freelancer_type === "" || $hourly_rate === "" || $portfolio === "") 
    {
        $error = "All fields are required.";
    } 
    
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $error = "Invalid email address.";
    } 
    
    else 
    {
        $stmt = $conn->prepare("UPDATE freelancers SET email=?, phone=?, freelancer_type=?, hourly_rate=?, portfolio=? WHERE id=?");
        $stmt->bind_param("sssssi", $email, $phone, $freelancer_type, $hourly_rate, $portfolio, $user_id);
        if ($stmt->execute()) 
        {
            $success = "Profile updated successfully!";
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['freelancer_type'] = $freelancer_type;
            $user['hourly_rate'] = $hourly_rate;
            $user['portfolio'] = $portfolio;
        } 
        
        else 
        {
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
    <title>Update Freelancer Profile</title>
    <link rel="stylesheet" href="../css/freelancer_profile_update_page.css">
</head>
<body>
<div class="profile-box">
    <h1>Update Profile</h1>

    <?php if ($success) echo "<p class='msg success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='msg error'>$error</p>"; ?>

    <form method="post">
        <label>Username (cannot be changed)</label>
        <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label>Freelancer Type</label>
        <select name="freelancer_type" required>
            <option value="planner" <?= $user['freelancer_type']==='planner'?'selected':'' ?>>Project Planner</option>
            <option value="designer" <?= $user['freelancer_type']==='designer'?'selected':'' ?>>3D Designer</option>
            <option value="coding" <?= $user['freelancer_type']==='coding'?'selected':'' ?>>Software Developer</option>
        </select>

        <label>Hourly Rate ($)</label>
        <input type="number" name="hourly_rate" step="0.01" value="<?= htmlspecialchars($user['hourly_rate']) ?>" required>

        <label>Portfolio</label>
        <textarea name="portfolio" required><?= htmlspecialchars($user['portfolio']) ?></textarea>

        <button type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
