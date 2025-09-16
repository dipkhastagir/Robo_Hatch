<?php
session_start();
// Dummy login check (replace with real one)
if (!isset($_SESSION['freelancer_id'])) {
    $_SESSION['freelancer_id'] = 1; // demo ID
    $_SESSION['username'] = "DemoFreelancer";
}

// Get selected section
$section = isset($_GET['section']) ? $_GET['section'] : 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Freelancer Dashboard - Robo Hatch</title>
    <link rel="stylesheet" href="../css/freelancer_dashboard_page.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Robo Hatch</h2>
            <ul>
                <li><a href="?section=profile">👤 Profile Summary</a></li>
                <li><a href="?section=projects">📂 Project Management</a></li>
                <li><a href="?section=proposals">📑 Proposal System</a></li>
                <li><a href="?section=portfolio">🎨 Portfolio Updates</a></li>
                <li><a href="?section=messages">💬 Messaging & Notifications</a></li>
                <li><a href="?section=earnings">💰 Earnings Tracking</a></li>
                <li><a href="?section=settings">⚙ Settings & Support</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <?php if ($section === 'profile') { ?>
                <h1>Profile Summary</h1>
                <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!</p>
                <p>Email: demo@mail.com</p>
                <p>Phone: +1234567890</p>
                <p>Freelancer Type: Designer</p>
                <p>Hourly Rate: $20/hr</p>

            <?php } elseif ($section === 'projects') { ?>
                <h1>Project Management</h1>
                <p>No projects assigned yet.</p>

            <?php } elseif ($section === 'proposals') { ?>
                <h1>Proposal System</h1>
                <p>You have not submitted any proposals.</p>

            <?php } elseif ($section === 'portfolio') { ?>
                <h1>Portfolio Updates</h1>
                <form method="post">
                    <label>Portfolio Link:</label><br>
                    <input type="text" name="portfolio" placeholder="https://yourportfolio.com"><br><br>
                    <input type="submit" value="Update">
                </form>

            <?php } elseif ($section === 'messages') { ?>
                <h1>Messages & Notifications</h1>
                <p>No new messages.</p>

            <?php } elseif ($section === 'earnings') { ?>
                <h1>Earnings Tracking</h1>
                <p>Total Earnings: $0.00</p>
                <p>No transactions yet.</p>

            <?php } elseif ($section === 'settings') { ?>
                <h1>Settings & Support</h1>
                <form method="post">
                    <label>Change Password:</label><br>
                    <input type="password" name="new_password" placeholder="New Password"><br><br>
                    <input type="submit" value="Update">
                </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>
