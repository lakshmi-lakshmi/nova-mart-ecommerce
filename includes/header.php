<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'pages') {

    $home_page = "../index.php";
    $products_page = "products.php";
    $cart_page = "cart.php";
    $profile_page = "profile.php";
    $login_page = "login.php";
    $register_page = "register.php";

} else {

    $home_page = "index.php";
    $products_page = "pages/products.php";
    $cart_page = "pages/cart.php";
    $profile_page = "pages/profile.php";
    $login_page = "pages/login.php";
    $register_page = "pages/register.php";
}

?>

<header>
    <div class="header-container">

        <h1>NOVA MART</h1>

        <nav>
            <a href="<?= $home_page ?>">Home</a>

            <a href="<?= $products_page ?>">Products</a>

            <a href="<?= $cart_page ?>">🛒 Cart</a>

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="<?= $profile_page ?>">
                    👤 <?= htmlspecialchars($_SESSION['user_name']); ?>
                </a>

            <?php else: ?>

                <a href="<?= $login_page ?>">Login</a>
                <a href="<?= $register_page ?>">Register</a>

            <?php endif; ?>
        </nav>

    </div>
</header>