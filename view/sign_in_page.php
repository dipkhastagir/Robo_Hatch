<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    if ($username === '' || $password === '' || $role === '') {
        $error = "All fields are required.";
    } else {
        if ($role === 'freelancer') {
            $stmt = $conn->prepare("SELECT * FROM freelancers WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $username);
            $redirect = "freelancer_dashboard_page.php";
        } elseif ($role === 'company') {
            $stmt = $conn->prepare("SELECT * FROM companies WHERE email = ? OR company_name = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $username);
            $redirect = "company_dashboard_page.php";
        } elseif ($role === 'team') {
            $stmt = $conn->prepare("SELECT * FROM teams WHERE email = ? OR team_name = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $username);
            $redirect = "team_dashboard_page.php";
        } else {
            $stmt = null;
            $error = "Invalid role selected.";
        }

        if ($stmt && $error === "") {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['role']     = $role;

                    if ($role === 'freelancer') {
                        $_SESSION['username'] = $user['username'];
                    } elseif ($role === 'company') {
                        $_SESSION['username']     = $user['company_name'];
                        $_SESSION['company_name'] = $user['company_name']; // ✅ Fix added
                    } else { // team
                        $_SESSION['username'] = $user['team_name'];
                    }

                    header("Location: " . $redirect);
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User not found.";
            }
            $stmt->close();
        }
    }
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
    <!-- Header -->
    <header>
        <div class="logo">Robo Hatch</div>
        <div class="header-buttons">
            <a href="about_us_page.php" class="btn back-btn">Back</a>
            <a href="role_selection_page.php" class="btn">Register</a>
        </div>
    </header>

    <!-- Sign In Section -->
    <section class="signin-container">
        <div class="signin-box">
            <h1>Sign In</h1>
            
            <?php if (!empty($error)) { ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php } ?>

            <form method="post" action="">
                <label for="username">Username / Email</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="">Choose Role</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="company">Company</option>
                    <option value="team">Team</option>
                </select>

                <button type="submit" class="signin-btn">Sign In</button>
                <p class="redirect">Don't have an account? <a href="role_selection_page.php">Register here</a></p>
            </form>
        </div>
    </section>
</body>
</html>
