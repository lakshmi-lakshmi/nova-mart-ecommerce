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

    <title>Settings - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<header>

    <div class="header-container">

        <h1>
            NOVA MART
        </h1>

        <nav>

            <a href="../index.php">
                Home
            </a>

            <a href="products.php">
                Products
            </a>

            <a href="cart.php">
                🛒 Cart
            </a>

            <a href="orders.php">
                📦 Orders
            </a>

            <a href="profile.php">
                👤 Profile
            </a>

        </nav>

    </div>

</header>


<div class="settings-container">


    <div class="settings-card">


        <h2>
            ⚙ Settings
        </h2>


        <p>
            Customize your NOVA MART experience.
        </p>


        <div class="setting-section">


            <h3>
                Appearance
            </h3>


            <label class="theme-option">

                <input
                    type="radio"
                    name="theme"
                    value="light"
                    checked
                >

                ☀️ Light Theme

            </label>


            <label class="theme-option">

                <input
                    type="radio"
                    name="theme"
                    value="dark"
                >

                🌙 Dark Theme

            </label>


        </div>


        <a
            href="profile.php"
            class="shop-button"
        >

            ← Back to Profile

        </a>


    </div>

</div>


<script>

const themeOptions =
    document.querySelectorAll(
        'input[name="theme"]'
    );


function applyTheme(theme) {

    if (theme === "dark") {

        document.body.classList.add("dark-theme");

    } else {

        document.body.classList.remove("dark-theme");

    }

    localStorage.setItem(
        "novaTheme",
        theme
    );
}


const savedTheme =
    localStorage.getItem("novaTheme");


if (savedTheme) {

    document.querySelector(
        `input[value="${savedTheme}"]`
    ).checked = true;

    applyTheme(savedTheme);

}


themeOptions.forEach(function(option) {

    option.addEventListener(
        "change",
        function() {

            applyTheme(this.value);

        }
    );

});

</script>


<footer>

    <p>

        &copy; <?= date('Y'); ?>

        NOVA MART.

        All Rights Reserved.

    </p>

</footer>


</body>

</html>