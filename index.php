<?php

session_start();


// ======================================================
// CHECK LOGIN
// ======================================================

// If the user is not logged in,
// send them to the Registration page.

if (!isset($_SESSION['user_id'])) {

    header("Location: pages/register.php");

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

    <title>Welcome - NOVA MART</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>


<!-- ================= HEADER ================= -->

<?php include "includes/header.php"; ?>


<!-- ================= HOME ================= -->

<section class="home-section">

    <div class="home-content">

        <div class="home-text">

            <h2>
                Welcome to NOVA MART
            </h2>

            <h3>
                Everything You Need,
                All in One Place.
            </h3>

            <p>
                Discover stylish products, useful gadgets,
                fashion essentials and everyday items
                at NOVA MART.
            </p>

            <a
                href="pages/products.php"
                class="shop-button"
            >
                Shop Now
            </a>

        </div>


        <div class="home-image">

            <img
                src="images/shopping.png"
                alt="Online Shopping"
            >

        </div>

    </div>

</section>


<!-- ================= FEATURES ================= -->

<section class="features">

    <div class="feature">

        <div class="feature-icon">
            🚚
        </div>

        <h3>
            Fast Delivery
        </h3>

        <p>
            Get your products delivered quickly.
        </p>

    </div>


    <div class="feature">

        <div class="feature-icon">
            🔒
        </div>

        <h3>
            Secure Shopping
        </h3>

        <p>
            Your shopping experience is secure.
        </p>

    </div>


    <div class="feature">

        <div class="feature-icon">
            ⭐
        </div>

        <h3>
            Quality Products
        </h3>

        <p>
            Discover products selected for you.
        </p>

    </div>

</section>


<!-- ================= FOOTER ================= -->

<footer>

    <p>
        &copy; <?= date('Y'); ?> NOVA MART.
        All Rights Reserved.
    </p>

</footer>


</body>

</html>