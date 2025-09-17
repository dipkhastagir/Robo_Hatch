<?php
session_start();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Contact Us | Robo Hatch</title>
    <link rel="stylesheet" href="../css/contact_us_page.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <div class="logo">Robo Hatch</div>
        <div class="header-buttons">
            <a href="role_selection_page.php" class="btn">Register</a>
            <a href="sign_in_page.php" class="btn">Sign In</a>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="contact-container">
        <div class="contact-text">
            <h1>Contact Us</h1>
            <p>
                At Robo Hatch, we are committed to empowering innovation in robotics. 
                Whether you are a freelancer, a company, or an enthusiast, 
                our platform provides opportunities to collaborate, design, 
                and bring your robotics ideas to life.
            </p>
            <p>
                For any inquiries, partnership opportunities, or support, 
                feel free to reach out to us. Together, let’s build the future of robotics.
            </p>

            <div class="company-info">
                <p><strong>📞 Phone:</strong> +880 1234-567890</p>
                <p><strong>📧 Email:</strong> contact@robohatch.com</p>
                <p><strong>🏢 Address:</strong> Robo Hatch HQ, Road 15, Banani, Dhaka, Bangladesh</p>
                <p><strong>⏰ Office Hours:</strong> Sun - Thu (9:00 AM - 6:00 PM)</p>
            </div>

            <div class="contact-buttons">
                <a href="about_us_page.php" class="back-btn">Back</a>
                <a href="role_selection_page.php" class="next-btn">Next</a>
            </div>
        </div>

        <div class="contact-image">
            <img src="../images/contact_us.png" alt="Robo Hatch Company">
        </div>
    </section>

</body>
</html>
