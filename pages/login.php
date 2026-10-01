<?php

session_start();

require_once "../includes/db.php";

$error_message = "";
$success_message = "";

/* Registration success message */
if (isset($_SESSION['success_message'])) {

    $success_message = $_SESSION['success_message'];

    unset($_SESSION['success_message']);
}


// ======================================================
// LOGIN
// ======================================================

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];


    // Check empty fields

    if (empty($email) || empty($password)) {

        $error_message = "Please enter your email and password.";

    } else {

        // Prepare SQL query

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // Check user and password

        if ($user && password_verify($password, $user['password'])) {

            // Store user information in session

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['user_name'] = $user['name'];

            $_SESSION['user_email'] = $user['email'];


            // Go to HOME PAGE

            header("Location: ../index.php");

            exit;

        } else {

            $error_message = "Invalid email or password.";

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

    <title>Login - NOVA MART</title>


    <!-- =====================================================
         LOGIN PAGE CSS
    ====================================================== -->

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family: Arial, Helvetica, sans-serif;

            min-height: 100vh;

            background: #f5f6fa;

        }


        /* =================================================
           MAIN LOGIN PAGE
        ================================================= */

        .login-page {

            min-height: 100vh;

            width: 100%;

            display: flex;

            background: #f5f6fa;

        }


        /* =================================================
           LEFT SIDE
        ================================================= */

        .login-left {

            width: 55%;

            min-height: 100vh;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 60px;

            color: white;

        }


        .login-left-content {

            width: 100%;

            max-width: 500px;

        }


        .login-left-content h1 {

            font-size: 52px;

            margin-bottom: 20px;

            font-weight: 700;

        }


        .login-left-content p {

            font-size: 20px;

            line-height: 1.7;

            color: #f5f5f5;

        }


        /* =================================================
           RIGHT SIDE
        ================================================= */

        .login-right {

            width: 45%;

            min-height: 100vh;

            background: #ffffff;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 50px;

        }


        /* =================================================
           LOGIN BOX
        ================================================= */

        .login-box {

            width: 100%;

            max-width: 420px;

        }


        /* =================================================
           BACK LINK
        ================================================= */

        .back-link {

            display: block;

            text-align: right;

            margin-bottom: 30px;

            color: #555;

            text-decoration: none;

            font-size: 15px;

        }


        .back-link:hover {

            color: #667eea;

        }


        /* =================================================
           LOGIN TITLE
        ================================================= */

        .login-box h2 {

            font-size: 36px;

            color: #222;

            margin-bottom: 8px;

        }


        .login-subtitle {

            color: #777;

            font-size: 15px;

            margin-bottom: 25px;

        }


        /* =================================================
           ERROR MESSAGE
        ================================================= */

        .error-message {

            background: #ffe6e6;

            color: #c62828;

            border: 1px solid #ffcaca;

            padding: 12px 15px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

        }


        /* =================================================
           SUCCESS MESSAGE
        ================================================= */

        .success-message {

            background: #e8f8ee;

            color: #218838;

            border: 1px solid #b7e4c7;

            padding: 12px 15px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

        }


        /* =================================================
           LABELS
        ================================================= */

        .login-box label {

            display: block;

            margin-top: 18px;

            margin-bottom: 8px;

            color: #333;

            font-size: 14px;

            font-weight: 600;

        }


        /* =================================================
           INPUTS
        ================================================= */

        .login-box input {

            width: 100%;

            height: 48px;

            padding: 0 14px;

            border: 1px solid #ddd;

            border-radius: 8px;

            background: #fff;

            color: #333;

            font-size: 15px;

            outline: none;

            transition: 0.3s;

        }


        .login-box input::placeholder {

            color: #aaa;

        }


        .login-box input:focus {

            border-color: #667eea;

            box-shadow:
                0 0 0 3px
                rgba(102, 126, 234, 0.12);

        }


        /* =================================================
           LOGIN BUTTON
        ================================================= */

        .login-button {

            width: 100%;

            height: 48px;

            margin-top: 28px;

            border: none;

            border-radius: 8px;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            color: white;

            font-size: 16px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.3s;

        }


        .login-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(102, 126, 234, 0.25);

        }


        /* =================================================
           REGISTER
        ================================================= */

        .register-text {

            text-align: center;

            margin-top: 25px;

            color: #777;

            font-size: 14px;

        }


        .register-text a {

            color: #667eea;

            text-decoration: none;

            font-weight: 600;

        }


        .register-text a:hover {

            text-decoration: underline;

        }


        /* =================================================
           TABLET / MOBILE
        ================================================= */

        @media (max-width: 800px) {

            .login-page {

                flex-direction: column;

            }


            .login-left {

                width: 100%;

                min-height: 280px;

                padding: 40px 25px;

                text-align: center;

            }


            .login-left-content h1 {

                font-size: 38px;

            }


            .login-left-content p {

                font-size: 17px;

            }


            .login-right {

                width: 100%;

                min-height: auto;

                padding: 40px 25px;

            }

        }

    </style>

</head>


<body>


<div class="login-page">


    <!-- =================================================
         LEFT SIDE
    ================================================== -->

    <div class="login-left">

        <div class="login-left-content">

            <h1>
                Welcome Back!
            </h1>

            <p>
                Login to continue shopping
                at NOVA MART.
            </p>

        </div>

    </div>


    <!-- =================================================
         RIGHT SIDE
    ================================================== -->

    <div class="login-right">


        <div class="login-box">


            <!-- BACK -->

            <a
                href="../index.php"
                class="back-link"
            >

                ← Back

            </a>


            <!-- TITLE -->

            <h2>
                Login
            </h2>


            <p class="login-subtitle">

                Login to continue shopping

            </p>


            <!-- SUCCESS MESSAGE -->

            <?php if (!empty($success_message)): ?>

                <div class="success-message">

                    <?= htmlspecialchars($success_message); ?>

                </div>

            <?php endif; ?>


            <!-- ERROR MESSAGE -->

            <?php if (!empty($error_message)): ?>

                <div class="error-message">

                    <?= htmlspecialchars($error_message); ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 LOGIN FORM
            ================================================== -->

            <form
                method="POST"
                action=""
            >


                <!-- EMAIL -->

                <label for="email">

                    Email Address

                </label>


                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >


                <!-- PASSWORD -->

                <label for="password">

                    Password

                </label>


                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    name="login"
                    class="login-button"
                >

                    Login

                </button>


            </form>


            <!-- REGISTER -->

            <p class="register-text">

                Don't have an account?

                <a href="register.php">

                    Register

                </a>

            </p>


        </div>

    </div>


</div>


</body>

</html>