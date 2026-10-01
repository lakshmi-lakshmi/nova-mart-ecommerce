<?php

session_start();

require_once "../includes/db.php";
require_once "../includes/products.php";


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit;

}


// =====================================================
// GET CART
// =====================================================

$cart = $_SESSION['cart'] ?? [];


// =====================================================
// CART MUST NOT BE EMPTY
// =====================================================

if (empty($cart) && empty($_SESSION['order_success'])) {

    header("Location: cart.php");

    exit;

}


// =====================================================
// VARIABLES
// =====================================================

$error_message = "";

$order_success = $_SESSION['order_success'] ?? "";


// Remove success message from session after reading it

if (!empty($_SESSION['order_success'])) {

    unset($_SESSION['order_success']);

}


// =====================================================
// CALCULATE TOTAL
// =====================================================

$total = 0;

foreach ($cart as $product_id => $quantity) {

    if (isset($products[$product_id])) {

        $total +=
            $products[$product_id]['price']
            * $quantity;

    }

}


// =====================================================
// PLACE ORDER
// =====================================================

if (isset($_POST['place_order'])) {

    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $phone = trim($_POST['phone']);
    $payment_method = trim($_POST['payment_method']);


    // Check delivery and payment details

    if (
        empty($address) ||
        empty($city) ||
        empty($pincode) ||
        empty($phone) ||
        empty($payment_method)
    ) {

        $error_message =
            "Please fill in all delivery and payment details.";

    } else {

        try {

            // =====================================================
            // START TRANSACTION
            // =====================================================

            $conn->beginTransaction();


            // =====================================================
            // INSERT ORDER
            // =====================================================

            $stmt = $conn->prepare("
                INSERT INTO orders
                (
                    user_id,
                    total_amount,
                    status,
                    Address,
                    City,
                    Pincode,
                    phone,
                    `Payment method`
                )
                VALUES (?, ?, 'Order Placed', ?, ?, ?, ?, ?)
            ");


            $stmt->execute([
                $_SESSION['user_id'],
                $total,
                $address,
                $city,
                $pincode,
                $phone,
                $payment_method
            ]);


            // Get newly created order ID

            $order_id = $conn->lastInsertId();


            // =====================================================
            // INSERT ORDER ITEMS
            // =====================================================

            $item_stmt = $conn->prepare("
                INSERT INTO order_items
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");


            foreach ($cart as $product_id => $quantity) {

                if (isset($products[$product_id])) {

                    $price =
                        $products[$product_id]['price'];


                    $item_stmt->execute([
                        $order_id,
                        $product_id,
                        $quantity,
                        $price
                    ]);

                }

            }


            // =====================================================
            // COMMIT TRANSACTION
            // =====================================================

            $conn->commit();


            // =====================================================
            // CLEAR CART
            // =====================================================

            unset($_SESSION['cart']);


            // =====================================================
            // SHOW SUCCESS MESSAGE
            // =====================================================

            $order_success =
                "Your order has been placed successfully!";


            // Reset cart variable

            $cart = [];


        } catch (PDOException $e) {

            // Rollback if something goes wrong

            if ($conn->inTransaction()) {

                $conn->rollBack();

            }


            $error_message =
                "Unable to place the order. Please try again.";

        }

    }

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

    <title>Checkout - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<?php include "../includes/header.php"; ?>


<main class="main-container">


    <h2>
        Checkout
    </h2>


    <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

    <?php if (!empty($error_message)): ?>

        <div class="error-message">

            <?= htmlspecialchars($error_message); ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         ORDER SUCCESS MESSAGE
    ====================================================== -->

    <?php if (!empty($order_success)): ?>

        <div class="order-success">

            <div class="order-success-icon">
                ✓
            </div>


            <h3>
                Your Order Has Been Placed!
            </h3>


            <p>
                <?= htmlspecialchars($order_success); ?>
            </p>


            <a
                href="orders.php"
                class="my-orders-button"
            >
                📦 My Orders
            </a>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         CHECKOUT FORM + SUMMARY

         Show these only before order is placed.
    ====================================================== -->

    <?php if (empty($order_success)): ?>


        <div class="checkout-container">


            <!-- =================================================
                 DELIVERY DETAILS
            ================================================== -->

            <div class="checkout-form">


                <h3>
                    Delivery Details
                </h3>


                <form method="POST">


                    <!-- ADDRESS -->

                    <label>
                        Address
                    </label>

                    <textarea
                        name="address"
                        rows="4"
                        placeholder="Enter your delivery address"
                        required
                    ></textarea>


                    <!-- CITY -->

                    <label>
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        placeholder="Enter your city"
                        required
                    >


                    <!-- PIN CODE -->

                    <label>
                        PIN Code
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        placeholder="Enter PIN code"
                        required
                    >


                    <!-- PHONE -->

                    <label>
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone"
                        placeholder="Enter phone number"
                        required
                    >


                    <!-- PAYMENT METHOD -->

                    <label>
                        Payment Method
                    </label>

                    <div class="payment-options">

                        <label>
                            <input
                                type="radio"
                                name="payment_method"
                                value="Cash on Delivery"
                                required
                            >
                            💵 Cash on Delivery
                        </label>


                        <label>
                            <input
                                type="radio"
                                name="payment_method"
                                value="UPI"
                            >
                            📱 UPI
                        </label>


                        <label>
                            <input
                                type="radio"
                                name="payment_method"
                                value="Credit/Debit Card"
                            >
                            💳 Credit/Debit Card
                        </label>

                    </div>


                    <!-- PLACE ORDER -->

                    <button
                        type="submit"
                        name="place_order"
                        class="checkout-button"
                    >
                        Place Order
                    </button>


                </form>


            </div>


            <!-- =================================================
                 ORDER SUMMARY
            ================================================== -->

            <div class="checkout-summary">


                <h3>
                    Order Summary
                </h3>


                <?php foreach ($cart as $product_id => $quantity): ?>


                    <?php if (isset($products[$product_id])): ?>


                        <?php

                        $product =
                            $products[$product_id];

                        $subtotal =
                            $product['price']
                            * $quantity;

                        ?>


                        <div class="checkout-item">


                            <!-- PRODUCT IMAGE -->

                            <img
                                src="../images/product<?= $product_id ?>.jpg"
                                alt="<?= htmlspecialchars($product['name']); ?>"
                            >


                            <!-- PRODUCT DETAILS -->

                            <div>


                                <h4>

                                    <?= htmlspecialchars(
                                        $product['name']
                                    ); ?>

                                </h4>


                                <p>

                                    Quantity:
                                    <?= $quantity; ?>

                                </p>


                                <p>

                                    ₹<?= number_format(
                                        $subtotal,
                                        2
                                    ); ?>

                                </p>


                            </div>


                        </div>


                    <?php endif; ?>


                <?php endforeach; ?>


                <hr>


                <h3 class="checkout-total">

                    <span>
                        Total:
                    </span>

                    <span>

                        ₹<?= number_format(
                            $total,
                            2
                        ); ?>

                    </span>

                </h3>


            </div>


        </div>


    <?php endif; ?>


</main>


<!-- =====================================================
     FOOTER
====================================================== -->

<footer>

    <p>

        &copy; <?= date('Y'); ?> NOVA MART.

        All Rights Reserved.

    </p>

</footer>


</body>

</html>