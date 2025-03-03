<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
    Revisions:
        3/1/2025  -- Jose, Stylizing Choices to page
Purpose: Main Page to see the barbershops, Barbers, Cuts, and Availabilities
-->
<?php
// Start the session to remember user info
session_start();

$reviews = [
    "Best barber in town! Always leaves me looking sharp.",
    "Great atmosphere and amazing cuts. Highly recommend!",
    "Professional and friendly service every time.",
];
$hours = [
    "Monday - Saturday: 9 AM - 8 PM"

];
$summary = "Welcome to our barbershop! With years of experience and a passion for perfecting every cut, we guarantee top-quality service in a relaxing atmosphere.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Name of Page-->
    <title>Home Page</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* Applies styles to the entire body */
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background: url('https://img.freepik.com/free-photo/client-doing-hair-cut-barber-shop-salon_1303-20824.jpg') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
        }

        /* Top Bar at Top with Pages and Login */
        .top-bar {
            background-color: #006400; 
            padding: 0;
            display: flex;  
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 70px; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        /* Size of Letters on it's header */
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
            font-size: 28px;
            color: white;
        }
        /* Space for the login button on the right */
        .login-container {
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
        /* Login Button Format*/
        .login-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Style for the Menu taht will have the navigation buttons */
        .menu {
            display: flex;
            flex-grow: 1;
            justify-content: center;
            height: 100%;
        }
        /* Style of Navigation Buttons */
        .menu button {
            background-color: #006400; 
            color: white;
            border: none;
            padding: 20px 25px; 
            font-size: 18px;
            cursor: pointer;
            flex-grow: 1;
            text-align: center;
            font-family: 'Georgia', serif; 
        }
        /* Color gets darker when hovering the buttons */
        .menu button:hover {
            background-color: #004d00; 
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: rgba(50, 50, 50, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        .section {
            margin-bottom: 20px;
        }
        h2 {
            color:white;
        }
        h1{
            color: white;
        }
    </style>
</head>
<body>
    <!--The green Bar at the top that has the name and button that takes you to the login page-->
    <div class="top-bar">
        <h1>Quartet's Barbershop</h1>
        <div class="menu">
            <button onclick="location.href='index.php'">Home</button>
            <button onclick="location.href='schedule.php'">Schedule</button>
            <button onclick="location.href='store.php'">Store</button>
            <button onclick="location.href='barbers.php'">Barbers</button>
            <button onclick="location.href='about.php'">About us</button>
            <button onclick="location.href='feedback.php'">Contact us</button>
        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>About Us</h1>
    <br><br>
    <div class="container">
        <div class="section">
            <h2>About the Barber</h2>
            <p><?php echo $summary; ?></p>
        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="section">
            <h2>Hours</h2>
            <ul>
                <?php foreach ($hours as $hour) { echo "<li>$hour</li>"; } ?>
            </ul>
        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="section">
            <h2>Customer Reviews</h2>
            <ul>
                <?php foreach ($reviews as $review) { echo "<li>$review</li>"; } ?>
            </ul>
        </div>
    </div>
    <br><br>
    <br><br>
    <br><br><br>

</body>