<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Role Selection</title>
    <link rel="stylesheet" href="../css/role_selection_page.css">
</head>
<body>
    <div class="role-container">
        <h1>Select Your Role</h1>
        <p>Choose your role to continue with the registration process:</p>

        <div class="role-cards">
            <!-- Freelancer -->
            <a href="freelancer_register_page.php" class="role-card">
                <img src="../images/role_selection_1.png" alt="Freelancer">
                <h3>Freelancer</h3>
            </a>

            <!-- Team -->
            <a href="team_register_page.php" class="role-card">
                <img src="../images/role_selection_2.png" alt="Team">
                <h3>Team</h3>
            </a>

            <!-- Company -->
            <a href="company_register_page.php" class="role-card">
                <img src="../images/role_selection_3.png" alt="Company">
                <h3>Company</h3>
            </a>
        </div>
    </div>
</body>
</html>
