<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Defines character encoding for proper text rendering -->
    <meta charset="UTF-8">
    <!-- Ensures proper rendering and touch zooming on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the page displayed in the browser tab -->
    <title>Schedule</title>

    <!-- Internal CSS for styling the page -->
    <style>
        /* Applies styles to the entire body */
        body {
            text-align: center; /* Centers text content */
            font-family: Arial, sans-serif; /* Sets the font */
        }

        /* Styles the top navigation bar */
        .top-bar {
            background-color: green; /* Sets background color to green */
            padding: 10px; /* Adds padding around the content */
            display: flex; /* Uses flexbox for layout */
            justify-content: space-between; /* Spaces elements evenly */
            align-items: center; /* Centers elements vertically */
            color: white; /* Sets text color to white */
            height: 50px; /* Sets the height of the bar */
        }

        /* Styles the heading inside the top bar */
        .top-bar h1 {
            margin: 0; /* Removes default margin */
            padding-left: 20px; /* Adds left padding */
            font-size: 24px; /* Sets font size */
        }

        /* Styles the container for the login button */
        .login-container {
            display: flex; /* Uses flexbox for layout */
            align-items: center; /* Aligns items in the center */
            padding-right: 20px; /* Adds right padding */
        }

        /* Styles the login button */
        .login-button {
            width: 40px; /* Sets button width */
            height: 40px; /* Sets button height */
            border-radius: 50%; /* Makes the button circular */
            background-color: #007BFF; /* Sets button background color */
            color: white; /* Sets button text color */
            border: none; /* Removes border */
            font-size: 16px; /* Sets font size */
            cursor: pointer; /* Changes cursor to pointer on hover */
            margin-left: 10px; /* Adds margin to the left */
            display: flex; /* Uses flexbox for layout */
            align-items: center; /* Centers content vertically */
            justify-content: center; /* Centers content horizontally */
        }

        /* Styles the menu section */
        .menu {
            margin-top: 20px; /* Adds space above the menu */
        }

        /* Styles buttons inside the menu */
        .menu button {
            margin: 5px; /* Adds space between buttons */
            padding: 10px 20px; /* Adds padding inside buttons */
            font-size: 16px; /* Sets font size */
            cursor: pointer; /* Changes cursor to pointer on hover */
        }
    </style>
</head>
<body>
    <!-- The top navigation bar containing the barbershop name and login button -->
    <div class="top-bar">
        <h1>Quartet's Amazing Barbershop</h1>
        <!-- Login button container -->
        <div class="login-container">
            <span>Login</span>
            <!-- Clicking this button redirects to the login page -->
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>

    <!-- Page title -->
    <h1>Schedule</h1>

    <!-- Navigation menu with buttons linking to different pages -->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='page4.html'">Page 4</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>
</body>
</html>
