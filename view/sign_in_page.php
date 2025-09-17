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
                        $_SESSION['company_name'] = $user['company_name']; // ✅ Added
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
