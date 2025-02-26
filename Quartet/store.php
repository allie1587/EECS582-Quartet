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
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .top-bar {
            background-color: green;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 50px;
        }
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
            font-size: 24px;
        }
        .login-container {
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
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
        .menu {
            margin-top: 20px;
        }
        .menu button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
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
        <h1>Quartet's Amazing Barbershop</h1>
        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>Store</h1>
    <!--Menu with all possible pages-->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='page4.html'">Page 4</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>
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