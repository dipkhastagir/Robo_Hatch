<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Robo Hatch | Services</title>
    <link rel="stylesheet" href="../css/services_page.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h2>Robo Hatch</h2>
        <div class="header-buttons">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Sign In</a>
        </div>
    </header>

    <!-- Services Section -->
    <section class="services-container">
        <h1>Our Services</h1>
        <p>
            At Robo Hatch, we provide end-to-end services for robotics development.
            Whether you are a freelancer or a company, our platform helps you collaborate
            and complete projects efficiently.
        </p>

        <!-- Services Grid -->
        <div class="services-grid">
            <div class="service-card">
                <img src="../images/planning.png" alt="Project Planning">
                <h2>Project Planning</h2>
                <p>Freelancers can create research and planning documentation for robotics projects.</p>
            </div>
            <div class="service-card">
                <img src="../images/cad.png" alt="3D Design & CAD">
                <h2>3D Design & CAD</h2>
                <p>Create detailed 3D models and CAD designs for robots and components.</p>
            </div>
            <div class="service-card">
                <img src="../images/prototyping.png" alt="Hardware Prototyping">
                <h2>Hardware Prototyping</h2>
                <p>Companies can build prototypes using designs and research documentation.</p>
            </div>
            <div class="service-card">
                <img src="../images/coding.png" alt="Software Development">
                <h2>Software Development</h2>
                <p>Freelancers can code software for robots, and companies can integrate it into systems.</p>
            </div>
            <div class="service-card">
                <img src="../images/testing.png" alt="Testing & Debugging">
                <h2>Testing & Debugging</h2>
                <p>Ensure quality and performance by thorough testing and debugging.</p>
            </div>
            <div class="service-card">
                <img src="../images/deployment.png" alt="Deployment & Maintenance">
                <h2>Deployment & Maintenance</h2>
                <p>Deploy completed robots and provide maintenance and R&D support.</p>
            </div>
        </div>

        <!-- Next Button -->
        <div class="next-section">
            <a href="contact.php" class="next-btn">Next → Contact Us</a>
        </div>
    </section>

</body>
</html>
