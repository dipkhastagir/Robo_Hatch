<?php
session_start();
include "config.php";

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];  // 'freelancer' or 'company'

if (empty($username) || empty($password)) {
    echo "Username and password are required.";
    exit;
}

$sql = "SELECT * FROM freelancers WHERE username='$username' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'freelancer';
        header("Location: freelancer_dashboard.php");  // Redirect to freelancer dashboard
    } else {
        echo "Invalid credentials.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In | Robo Hatch</title>
    <link rel="stylesheet" href="../css/sign_in_page.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <div class="logo">Robo Hatch</div>
        <div class="header-buttons">
            <a href="register.php" class="btn">Register</a>
        </div>
    </header>

    <!-- Sign In Form -->
    <section class="signin-container">
        <div class="signin-box">
            <h1>Sign In</h1>
            <form method="post" action="sign_in_process.php">
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="">-- Choose Role --</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="company">Company</option>
                    <option value="team">Team</option>
                </select>

                <button type="submit" class="signin-btn">Sign In</button>

                <p class="redirect">
                    Don’t have an account? <a href="role_selection_page.php">Register here</a>
                </p>
            </form>
        </div>
    </section>

</body>
</html>
