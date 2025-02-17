<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Last modified: 02/16/2025
Purpose: Creating a new account
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
    <title>Register Page</title>
    <!--Link to external CSS file -->
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <!--Container for the registration form-->
    <div class="register-container">
        <!--Registration form-->
        <form id="registerForm" method="post">
            <!--Title for the form-->
            <h2 class="register-title">Create new account</h2>
            <!--Input field for first name-->
            <div class="register-input">
                <input type="text" id="fname" placeholder="First Name" required>
            </div>
            <!--Input field for last name-->
            <div class="register-input">
                <input type="text" id="lname" placeholder="Last Name" required>
            </div>
            <!--Input field for username-->
            <div class="register-input">
                <input type="text" id="username" placeholder="Username" name="username" required>
            </div>
            <!--Input field for password-->
            <div class="register-input">
                <input type="password" id="password" placeholder="Password" required>
            </div>
            <!--Input field for confirming password-->
            <div class="register-input">
                <input type="password" id="password" placeholder="Confirm Password" required>
            </div>
            <!--Submit button to sign up-->
            <button type="submit">Sign Up</button>
            <!--Placeholder for error messages-->
            <p id="error-message" class="error-message"></p>
        </form>

        <?php
            // set session username
            $_SESSION["user"] = $_POST["username"];

            if (isset($_SESSION["user"])) {
                header("Location: index.php");
            }
            
        ?>

        <!--Link to the login page for existing users-->
        <p class="login-switch">
            Already have an account?
            <a href="login.php" class="register-login">Log in</a>
        </p>
    </div>
</body>
</html>