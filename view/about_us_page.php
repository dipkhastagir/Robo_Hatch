<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us Page</title>
    <link rel="stylesheet" href="../css/about_us_page.css">
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

    <!-- About Section -->
    <section class="about-container">
        <div class="about-text">
            <h1>About Robo Hatch</h1>
            <p>
                Robo Hatch is a platform connecting freelancers and companies for robotics development.
                Freelancers can work as Project Planners, 3D Designers, or Software Developers, while
                companies handle prototyping, integration, testing, deployment, maintenance, and R&D.
                AI-driven recommendations help match the right talents with the right projects.
            </p>
            <a href="services_page.php" class="next-btn">Next</a>
        </div>
        <div class="about-image">
            <img src="../images/about_us.jpg" alt="About Robo Hatch">
        </div>
    </section>

</body>
</html>
