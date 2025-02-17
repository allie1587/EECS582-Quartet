<!--
login.php
Description: Allows users to log in to their accounts
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Revisions: 
    02/16/2024 -- Brinley, adding session information
-->
<?php
// Start the session to remember user info
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Set the title of the page-->
    <title>Login Page</title>
    <!--Link to external CSS file-->
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <button onclick="location.href='index.php'">&#10132;</button>
    <!--Container for the login form-->
    <div class="login-container">
        <!--Login form-->
        <form id="loginForm" method="post">
            <!--Title for the form-->
            <h2 class="login-title">Login</h2>
            <!--Input field for username-->
            <div class="login-input">
                <input type="text" id="username" placeholder="Username" name="username" required >
            </div>
            <!--Input field for password-->
            <div class="login-input">
                <input type="password" id="password" placeholder="Password" required>
                <a href="#" class="login-forgot">Forgot password?</a>
            </div>
            <div class="remember-me">
                <input type="checkbox" id="rememberMe">
                <label for="rememberMe">Remember Me</label>
            </div>
            <!--Login button-->
            <button type="submit">Login</button>
            <!--Placeholder for error messages-->
            <p id="error-message" class="error-message"></p>
        </form>

        <?php
            // set session username
            $_SESSION["user"] = $_POST["username"];
        ?>
        <!--Link to registration page for new users-->
        <p class="login-switch">
            Don't have an account?
            <a href="register.html" class="login-register">Create Account</a>
        </p>
    </div>
    <script src ="scripts/login.js"></script>
</body>
</html>