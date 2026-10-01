<?php

require_once "../includes/db.php";

session_start();

$error_message = "";

if (isset($_POST['register'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check empty fields
    if (empty($email) || empty($password)) {

        $error_message = "Please enter your email and password.";

    } else {

        // Check if email already exists
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Existing account
            $error_message =
                "Account already exists. Please login to continue.";

        } else {

            // Hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "user";

            // Create new account
            $stmt = $conn->prepare(
                "INSERT INTO users (email, password, role)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $email,
                $hashed_password,
                $role
            ]);

            // Send success message to login page
            $_SESSION['success_message'] =
                "Registration successful! Please login to continue.";

            // Go to login page
            header("Location: login.php");
            exit;
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

    <title>Create Account</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .register-container {
            width: 420px;
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header .icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
        }

        h2 {
            color: #222;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eeeeee;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
        }

        .error-message {
            margin-top: 18px;
            padding: 12px;
            border-radius: 8px;
            background: #ffe8e8;
            color: #d63031;
            text-align: center;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #777;
        }

        .login-link a {
            color: #667eea;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {

            .register-container {
                width: 90%;
                padding: 30px 25px;
            }

        }

    </style>

</head>

<body>

    <div class="register-container">

        <div class="register-header">

            <div class="icon">👤</div>

            <h2>Create Account</h2>

            <p class="subtitle">
                Join us and start shopping today
            </p>

        </div>


        <form method="POST">

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

            </div>


            <button
                type="submit"
                name="register"
            >
                Create Account
            </button>

        </form>


        <?php if (!empty($error_message)): ?>

            <div class="error-message">

                <?= htmlspecialchars($error_message); ?>

            </div>

        <?php endif; ?>


        <div class="login-link">

            Already have an account?

            <a href="./login.php">
                Login
            </a>

        </div>


    </div>

</body>

</html>