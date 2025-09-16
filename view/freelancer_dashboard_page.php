<?php
session_start();

// Example login simulation
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "Freelancer123"; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/freelancer_dashboard_page.css">
</head>
<body>
    <!-- Navbar -->
    <nav id="navbar">
        <div class="container">
            <h2 class="logo">Robo Hatch</h2>
            <ul class="nav-links">
                <li><a href="landing_page.php">Home</a></li>
                <li><a href="about_us.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact_us.php">Contact</a></li>
                <li><a href="sign_in_page.php">Sign In</a></li>
                <li><a href="register.php">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Main -->
    <section class="dashboard">
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
            <p>Here’s your personalized freelancer dashboard.</p>

            <div class="dashboard-grid">
                <div class="dash-card">
                    <h3>👤 Profile</h3>
                    <p>Manage your account details and portfolio.</p>
                </div>
                <div class="dash-card">
                    <h3>📂 My Projects</h3>
                    <p>View and manage ongoing projects.</p>
                </div>
                <div class="dash-card">
                    <h3>📝 New Bids</h3>
                    <p>Find new projects and place bids.</p>
                </div>
                <div class="dash-card">
                    <h3>💬 Messages</h3>
                    <p>Chat with clients and companies.</p>
                </div>
                <div class="dash-card">
                    <h3>💳 Payments</h3>
                    <p>Check your earnings and transactions.</p>
                </div>
                <div class="dash-card">
                    <h3>⚙️ Settings</h3>
                    <p>Customize your dashboard preferences.</p>
                </div>
                <div class="dash-card logout">
                    <h3>🚪 Logout</h3>
                    <p>Securely sign out from your account.</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
