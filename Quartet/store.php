<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Last modified: 02/16/2025
Purpose: Store Page thaat will (later) allow users to see different products up to sale at the barbershop and their price
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
    <!--Name of Page-->
    <title>Store</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* Applies styles to the entire body */
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color:rgba(59, 65, 59, 0.29); 
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
        .store-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .product-container img {
            width: 100%;
            max-width: 200px;
            height: auto;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
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
            <button onclick="location.href='about.php'">About Us</button>
        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>Store</h1>
    <!--Menu with all possible pages-->

    <!--Styled grid 3x3 That shows in each space a different product available with a picture and it's name-->
    <div class="store-grid">
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 1">
            <div class="product-name">Product 1</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 2">
            <div class="product-name">Product 2</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 3">
            <div class="product-name">Product 3</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 4">
            <div class="product-name">Product 4</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 5">
            <div class="product-name">Product 5</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 6">
            <div class="product-name">Product 6</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 7">
            <div class="product-name">Product 7</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 8">
            <div class="product-name">Product 8</div>
        </div>
        <div class="product-container">
            <img src="images/product1.jpg" alt="Product 9">
            <div class="product-name">Product 9</div>
        </div>
    </div>
</body>
</html>