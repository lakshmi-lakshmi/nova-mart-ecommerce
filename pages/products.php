<?php

session_start();

require_once "../includes/products.php";


/* =====================================================
   ADD TO CART
===================================================== */

if (isset($_POST['add_to_cart'])) {

    $product_id = (int) $_POST['product_id'];

    if (isset($products[$product_id])) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {

            $_SESSION['cart'][$product_id]++;

        } else {

            $_SESSION['cart'][$product_id] = 1;
        }
    }

    /*
       Stay on Products page
    */

    header("Location: products.php");
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

    <title>Products - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body>


<!-- ================= HEADER ================= -->

<?php include "../includes/header.php"; ?>


<!-- ================= PRODUCTS ================= -->

<main class="main-container">

    <h2>
        Our Products
    </h2>


    <div class="product-list">


        <?php if (!empty($products)): ?>


            <?php foreach ($products as $id => $product): ?>


                <div class="product">


                    <!-- PRODUCT IMAGE -->

                    <img
                        src="../images/<?= htmlspecialchars($product['image']); ?>"
                        alt="<?= htmlspecialchars($product['name']); ?>"
                        class="product-image"
                    >


                    <!-- PRODUCT NAME -->

                    <h3>
                        <?= htmlspecialchars($product['name']); ?>
                    </h3>


                    <!-- PRODUCT PRICE -->

                    <p>
                        ₹<?= number_format($product['price'], 2); ?>
                    </p>


                    <!-- PRODUCT DESCRIPTION -->

                    <p>
                        <?= htmlspecialchars($product['description']); ?>
                    </p>


                    <!-- ADD TO CART -->

                    <form
                        method="POST"
                        action="products.php"
                    >

                        <input
                            type="hidden"
                            name="product_id"
                            value="<?= $id; ?>"
                        >


                        <button
                            type="submit"
                            name="add_to_cart"
                            class="add-to-cart-button"
                        >

                            🛒 Add to Cart

                        </button>

                    </form>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <p>
                No products available.
            </p>


        <?php endif; ?>


    </div>

</main>


<!-- =====================================================
     GO TO CART BUTTON

     It appears only when something exists in the cart.
===================================================== -->

<?php if (!empty($_SESSION['cart'])): ?>

    <a
        href="cart.php"
        class="go-to-cart-button"
    >
        🛒 Go to Cart
    </a>

<?php endif; ?>


<!-- ================= FOOTER ================= -->

<footer>

    <p>
        &copy; <?= date('Y'); ?> NOVA MART.
        All Rights Reserved.
    </p>

</footer>


</body>

</html>