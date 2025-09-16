<?php
session_start();
include "config.php";

$error = "";

// If form submitted → process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    if ($username === '' || $password === '' || $role === '') {
        $error = "All fields are required.";
    } else {
        if ($role === 'freelancer') {
            $table = "freelancers";
            $redirect = "freelancer_dashboard_page.php";
            $identifier = "username";
        } elseif ($role === 'company') {
            $table = "companies";
            $redirect = "company_dashboard_page.php";
            $identifier = "email";
        } elseif ($role === 'team') {
            $table = "teams";
            $redirect = "team_dashboard_page.php";
            $identifier = "email";
        } else {
            $error = "Invalid role selected.";
        }

        if ($error === "") {
            $stmt = $conn->prepare("SELECT * FROM $table WHERE $identifier = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['role']     = $role;
                    $_SESSION['username'] = $role === 'freelancer' ? $user['username'] : $user['email'];

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
    <header>
        <div class="logo">Robo Hatch</div>
        <div class="header-buttons">
            <a href="role_selection_page.php" class="btn">Register</a>
        </div>
    </header>

    <section class="signin-container">
        <div class="signin-box">
            <h1>Sign In</h1>

            <?php if (!empty($error)) { ?>
                <p style="color:red; font-weight:600;"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <form method="post" action="">
                <label for="username">Username / Email</label>
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
