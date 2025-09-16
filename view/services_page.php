<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Services</title>
    <link rel="stylesheet" href="../css/services_page.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <h2>Robo Hatch</h2>
        <div class="header-buttons">
            <a href="role_selection_page.php" class="btn">Register</a>
            <a href="login.php" class="btn">Sign In</a>
        </div>
    </header>

    <!-- Services Section -->
    <section class="services">
        <h1>Our Services</h1>
        <div class="service-grid">
            <div class="service-card"><img src="../images/our_services_1.png" alt="Service 1"><h3>Project Planning</h3></div>
            <div class="service-card"><img src="../images/our_services_2.png" alt="Service 2"><h3>3D Model Designers & CAD Engineers</h3></div>
            <div class="service-card"><img src="../images/our_services_3.png" alt="Service 3"><h3>Hardware Prototyping</h3></div>
            <div class="service-card"><img src="../images/our_services_4.png" alt="Service 4"><h3>Software Development</h3></div>
            <div class="service-card"><img src="../images/our_services_5.png" alt="Service 5"><h3>Testing & Validation</h3></div>
            <div class="service-card"><img src="../images/our_services_6.png" alt="Service 6"><h3>Maintenance</h3></div>
            <div class="service-card"><img src="../images/our_services_7.png" alt="Service 7"><h3>Research & Development</h3></div>
            <div class="service-card"><img src="../images/our_services_8.png" alt="Service 8"><h3>Sales & Deployment</h3></div>
        </div>

        <!-- Next Button -->
        <div class="next-btn">
            <a href="contact_us_page.php">Next</a>
        </div>
    </section>

</body>
</html>
