<?php

session_start();

require_once "../includes/db.php";
require_once "../includes/products.php";


// ======================================================
// CHECK LOGIN
// ======================================================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit;

}


// ======================================================
// GET USER ORDERS
// ======================================================

$stmt = $conn->prepare(
    "SELECT *
     FROM orders
     WHERE user_id = ?
     ORDER BY order_date DESC"
);

$stmt->execute([
    $_SESSION['user_id']
]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Orders - NOVA MART</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<!-- ======================================================
     HEADER
====================================================== -->

<?php include "../includes/header.php"; ?>


<!-- ======================================================
     ORDERS
====================================================== -->

<div class="orders-container">


    <h2>
        My Orders
    </h2>


    <!-- ==================================================
         ORDER SUCCESS MESSAGE
    ================================================== -->

    <?php if (isset($_GET['success'])): ?>

        <div class="order-success">

            ✓ Your order has been placed successfully!

        </div>

    <?php endif; ?>


    <!-- ==================================================
         NO ORDERS
    ================================================== -->

    <?php if (empty($orders)): ?>


        <div class="no-orders">

            <div class="no-orders-icon">
                📦
            </div>


            <h3>
                No Orders Yet
            </h3>


            <p>
                You haven't placed any orders yet.
            </p>


            <br>


            <a
                href="products.php"
                class="shop-button"
            >

                Start Shopping

            </a>

        </div>


    <?php else: ?>


        <!-- ==================================================
             DISPLAY ORDERS
        ================================================== -->

        <?php foreach ($orders as $order): ?>


            <div class="order-card">


                <!-- ORDER HEADER -->

                <div class="order-header">


                    <div>

                        <h3>

                            Order #<?= $order['id']; ?>

                        </h3>


                        <p>

                            Date:

                            <?= date(
                                "d M Y, h:i A",
                                strtotime(
                                    $order['order_date']
                                )
                            ); ?>

                        </p>

                    </div>


                    <span class="order-status">

                        <?= htmlspecialchars(
                            $order['status']
                        ); ?>

                    </span>


                </div>


                <!-- ==================================================
                     ORDER ITEMS
                ================================================== -->

                <div class="order-items">


                    <?php


                    $item_stmt = $conn->prepare(
                        "SELECT *
                         FROM order_items
                         WHERE order_id = ?"
                    );


                    $item_stmt->execute([
                        $order['id']
                    ]);


                    $items =
                        $item_stmt->fetchAll(
                            PDO::FETCH_ASSOC
                        );


                    ?>


                    <?php foreach ($items as $item): ?>


                        <?php

                        $product_id =
                            $item['product_id'];


                        if (
                            !isset(
                                $products[$product_id]
                            )
                        ) {

                            continue;

                        }


                        $product =
                            $products[$product_id];


                        $subtotal =
                            $item['price']
                            * $item['quantity'];

                        ?>


                        <div class="order-item">


                            <img
                                src="../images/product<?= $product_id; ?>.jpg"
                                alt="<?= htmlspecialchars(
                                    $product['name']
                                ); ?>"
                            >


                            <div class="order-item-info">


                                <h4>

                                    <?= htmlspecialchars(
                                        $product['name']
                                    ); ?>

                                </h4>


                                <p>

                                    ₹<?= number_format(
                                        $item['price'],
                                        2
                                    ); ?>

                                    ×

                                    <?= $item['quantity']; ?>

                                </p>


                            </div>


                            <strong>

                                ₹<?= number_format(
                                    $subtotal,
                                    2
                                ); ?>

                            </strong>


                        </div>


                    <?php endforeach; ?>


                </div>


                <!-- ORDER FOOTER -->

                <div class="order-footer">


                    <span>
                        Order Total
                    </span>


                    <strong>

                        ₹<?= number_format(
                            $order['total_amount'],
                            2
                        ); ?>

                    </strong>


                </div>


            </div>


        <?php endforeach; ?>


    <?php endif; ?>


</div>


<!-- ======================================================
     FOOTER
====================================================== -->

<footer>

    <p>

        &copy; <?= date('Y'); ?>

        NOVA MART.

        All Rights Reserved.

    </p>

</footer>


</body>

</html>