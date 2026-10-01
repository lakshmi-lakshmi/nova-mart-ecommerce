<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Profile - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body>

<?php include "../includes/header.php"; ?>


<div class="profile-container">

    <div class="profile-card">

        <div class="profile-icon">
            👤
        </div>

        <h2>
            My Profile
        </h2>


        <div class="profile-info">

            <div class="profile-row">

                <strong>Email</strong>

                <span>
                    <?= htmlspecialchars($_SESSION['user_email']); ?>
                </span>

            </div>

        </div>


        <div class="profile-links">

            <a href="orders.php">
                📦 My Orders
            </a>

            <a href="settings.php">
                ⚙️ Settings
            </a>

            <a href="logout.php">
                🚪 Logout
            </a>

        </div>

    </div>

</div>


</body>

</html>