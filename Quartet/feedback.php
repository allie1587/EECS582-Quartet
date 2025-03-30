<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/02/2025
    Revision:
        03/09/2025 -- Jose -- Fixed mistake so text inputed is visible (white)
Purpose: Allows user to send comments that will reach the barbers email for feedback/questions purposes
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
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: rgba(18, 11, 11, 0.7);
            color:white;

        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .reviews {
            background: rgba(36, 35, 35, 0.97);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .average-rating {
            font-size: 20px;
            font-weight: bold;
            background: #c4454d;
            padding: 10px;
            border-radius: 5px;
        }
        .review-container {
            max-width: 600px;
            margin: 50px auto;
        }
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .review strong {
            display: block;
            font-size: 18px;
        }
        .rating {
            color: #c4454d;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: rgba(50, 50, 50, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            color:white;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: rgba(18, 11, 11, 0.7);
            color:white;
        }
        input{
            border: 1px solid #ccc;
            border-radius: 5px;
            background: rgba(18, 11, 11, 0.7);
            color:white;
        }
        .form-group {
            display: flex;
            align-items: center;
            gap: 10px; 
        }

        .form-group label {
            white-space: nowrap;
        }
        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stars {
            display: flex;
            cursor: pointer;
        }

        .star {
            font-size: 25px;
            color: black;
            transition: color 0.3s;
        }
    </style>
    </style>
    <script>
        function sendData() { //Sends input data to a PHP backend using
            let inputData = document.getElementById("dbInput").value;
            fetch("server.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ data: inputData })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("dbOutput").innerText = data.message;
            });
        }
        //Adds a listener that pays attention to every individual star (for rating)
        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll(".star");
            const ratingInput = document.getElementById("Rating");
            //For each start, when clicking on it it will assing that rating to the data and will fill the stars red
            stars.forEach(star => {
                star.addEventListener("click", function() {
                    let rating = this.getAttribute("data-value");
                    ratingInput.value = rating;

                    // For eaach star before (including the selcted one), change colors from black to red
                    stars.forEach(s => {
                        s.style.color = s.getAttribute("data-value") <= rating ? "red" : "black";
                    });
                });
            });
        });
    </script>
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
    <h1>Contact Us!</h1>
    <br><br>
    <div class="container">
        <div class="section">
            <p>Do you have any questions about our service? Please send us a message and we will respond as soon as possible!<p>
        </div>
        <form action="send_mail.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
            
            <button type="submit">Send</button>
        </form>
    </div>
    <p>Do you have any feedback for us? We love to hear your opinion in our services!<p>
    <div class="reviews">
        <h2>Reviews</h2>
        <form action="submit_review.php" method="POST">
            <div class="form-group">
                <label for="Name">Name:</label>
                <input type="text" id="Name" name="Name">
                <div class="rating-container">
                    <label for="Rating">Rating:</label>
                    <div class="stars">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="Rating" name="Rating" required>
                </div>
            </div>
            <br>
            <label for="Review">Add your Review Here!</label><br>
            <textarea type="text" id="Review" name="Review" required></textarea><br><br>
            <button type="submit">Send your Review!</button>
        </form>
    </div>
    <p>Also Feel Free to Contact us at: 111-111-1111 During Business Hours!<p>
    <br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br>



</body>