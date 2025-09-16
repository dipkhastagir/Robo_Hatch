<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Role Selection - Robo Hatch</title>
    <link rel="stylesheet" href="../css/role_selection_page.css">
</head>
<body>
    <!-- Role Selection Section -->
    <section class="role-selection">
        <div class="container">
            <h1>Select Your Role</h1>
            <p>Choose your role to continue with the registration process:</p>

            <!-- Freelancer Option -->
            <div class="role-option">
                <a href="freelancer_register.php">
                    <img src="../images/role_selection_1.png" alt="Freelancer" class="role-img">
                    <h3>Freelancer</h3>
                </a>
            </div>

            <!-- Company/Team Option -->
            <div class="role-option">
                <a href="company_register.php">
                    <img src="../images/role_selection_2.png" alt="Company/Team" class="role-img">
                    <h3>Company / Team</h3>
                </a>
            </div>
        </div>
    </section>
</body>
</html>
