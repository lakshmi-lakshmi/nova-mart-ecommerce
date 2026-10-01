<?php

session_start();

require_once "../includes/products.php";

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

$cart = $_SESSION['cart'] ?? [];

$total = 0;


/*
|--------------------------------------------------------------------------
| REMOVE PRODUCT
|--------------------------------------------------------------------------
*/

if (isset($_POST['remove_from_cart'])) {

    $product_id = (int) $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    header("Location: cart.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE QUANTITY
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_cart'])) {

    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: cart.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| REFRESH CART
|--------------------------------------------------------------------------
*/

$cart = $_SESSION['cart'] ?? [];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cart - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body>

<?php include "../includes/header.php"; ?>


<main class="cart-page">

    <h2>🛒 Your Cart</h2>


    <?php if (empty($cart)): ?>

        <div class="empty-cart">

            <div class="empty-cart-icon">
                🛒
            </div>

            <h3>Your Cart is Empty</h3>

            <p>
                You haven't added any products yet.
            </p>

            <a
                href="products.php"
                class="shop-button"
            >
                Continue Shopping
            </a>

        </div>


    <?php else: ?>


        <div class="cart-layout">


            <!-- CART PRODUCTS -->

            <div class="cart-products">


                <?php foreach ($cart as $product_id => $quantity): ?>

                    <?php if (isset($products[$product_id])): ?>

                        <?php

                        $product = $products[$product_id];

                        $subtotal =
                            $product['price'] * $quantity;

                        $total += $subtotal;

                        ?>


                        <div class="cart-product-card">


                            <!-- IMAGE -->

                            <div class="cart-product-image">

                                <img
                                    src="../images/product<?= $product_id ?>.jpg"
                                    alt="<?= htmlspecialchars($product['name']); ?>"
                                >

                            </div>


                            <!-- DETAILS -->

                            <div class="cart-product-details">

                                <h3>
                                    <?= htmlspecialchars($product['name']); ?>
                                </h3>

                                <p class="cart-description">
                                    <?= htmlspecialchars($product['description']); ?>
                                </p>

                                <p class="cart-product-price">
                                    ₹<?= number_format($product['price'], 2); ?>
                                </p>


                                <!-- QUANTITY -->

                                <form
                                    method="POST"
                                    class="cart-quantity-form"
                                >

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?= $product_id; ?>"
                                    >

                                    <label>
                                        Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="quantity"
                                        value="<?= $quantity; ?>"
                                        min="1"
                                    >

                                    <button
                                        type="submit"
                                        name="update_cart"
                                    >
                                        Update
                                    </button>

                                </form>


                                <!-- REMOVE -->

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?= $product_id; ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="remove_from_cart"
                                        class="remove-button"
                                    >
                                        🗑 Remove
                                    </button>

                                </form>

                            </div>


                            <!-- SUBTOTAL -->

                            <div class="cart-product-subtotal">

                                <span>Subtotal</span>

                                <strong>
                                    ₹<?= number_format($subtotal, 2); ?>
                                </strong>

                            </div>


                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>


            </div>


            <!-- ORDER SUMMARY -->

            <div class="cart-summary-card">

                <h3>
                    Order Summary
                </h3>


                <div class="summary-row">

                    <span>
                        Items
                    </span>

                    <span>
                        <?= array_sum($cart); ?>
                    </span>

                </div>


                <div class="summary-line"></div>


                <div class="summary-total">

                    <span>
                        Total
                    </span>

                    <strong>
                        ₹<?= number_format($total, 2); ?>
                    </strong>

                </div>


                <a
                    href="checkout.php"
                    class="checkout-button"
                >
                    Proceed to Checkout
                </a>


                <a
                    href="products.php"
                    class="continue-shopping"
                >
                    ← Continue Shopping
                </a>

            </div>


        </div>


    <?php endif; ?>


</main>


<footer>

    <p>
        &copy; <?= date('Y'); ?>
        NOVA MART. All Rights Reserved.
    </p>

</footer>


</body>

</html>