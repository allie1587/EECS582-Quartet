<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
    Revisions:
        3/1/2025  -- Jose, Stylizing Choices to page
        03/02/2025 -- Dark Mode Added
        03/09/2025 -- Jose -- Started Review Work
Purpose: Main Page to see the barbershops, Barbers, Cuts, and Availabilities
-->
<?php
// Start the session to remember user info
session_start();

// check to see if the user is logged in and give them a cute little welcome messagef
if (isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
    echo "<p class='welcome-message'>Welcome back, " . htmlspecialchars($_SESSION["user"], ENT_QUOTES, 'UTF-8') . "!</p>";
}
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
            background-color:rgba(36, 35, 35);
            color:white;
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

        /* Store info section */
        .store-info {
            display: flex;
            align-items: center;
            justify-content: center;
            background:rgba(36, 35, 35);
            padding: 20px;
            border-radius: 10px;
            margin: 20px;
        }
        .store-info img {
            width: 400px;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }
        .store-text {
            text-align: left;
        }

        /* Barber profiles */
        .barbers {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .barber-container { 
            background:rgba(36, 35, 35);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 320px;
        }
        .barber-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color:green;
        }
        .availability {
            font-weight: bold;
            color: green;
            margin-bottom: 10px;
        }
        .barber-images {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .barber-images img {
            width: 100%;
            height: auto;
            display: none;
            border-radius: 10px;
        }
        .barber-images img.active {
            display: block;
        }

        /* Arrows for scrolling */
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .arrow:hover {
            background: rgba(0, 0, 0, 0.7);
        }
        .arrow-left {
            left: 10px;
        }
        .arrow-right {
            right: 10px;
        }

        /* Reviews section */
        .reviews {
            background: rgba(36, 35, 35, 0.97);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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


    </style>
    <script>
        // Selects image from query and shows you the currently selected image
        function showImage(barberIndex, index) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            images.forEach(img => img.classList.remove("active"));
            images[index].classList.add("active");
        }
        //Scrolls to the next image by indexing foward through them
        function nextImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex + 1) % images.length;
            showImage(barberIndex, currentIndex);
        }
        //Scrolls to the previous image by indexing backwards through them
        function prevImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(barberIndex, currentIndex);
        }
        document.addEventListener("DOMContentLoaded", () => {
            for (let i = 1; i <= 3; i++) {
                showImage(i, 0);
            }
        });

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
            <button onclick="location.href='about.php'">About Us</button>
            <button onclick="location.href='feedback.php'">Contact us</button>

        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>Home</h1>
    <div class="db-box">
        <input type="text" id="dbInput" placeholder="Enter data">
        <button onclick="sendData()">Submit</button>
        <p id="dbOutput"></p>
    </div>

    <!--Section that holds image of barbershop, followed by the Sotre Information-->
    <div class="store-info">
        <img src="images/store.jpg" alt="Store Image">
        <div class="store-text">
            <p><strong>Location:</strong> 123 Main St, Cityville</p>
            <p><strong>Hours:</strong> Mon-Sat: 9 AM - 8 PM, Sun: Closed</p>
            <p><strong>Information:</strong> Our store offers top-notch haircuts and grooming services.</p>
        </div>
    </div>
    <!--Section where we can see the cuts different barbers have made-->
    <h2> Barbers</h2>
    <div class="barbers">
        <!--Contains the name of Barber, availability, and scrollable images using the fuctions we defined earlier-->
        <div class="barber-container">
            <div class="barber-name">John Doe</div>
            <p class="availability">Available: Tue, Fri, Sat 2:00PM-8:00PM</p>
            <div class="barber-images barber-1">
                <button class="arrow arrow-left" onclick="prevImage(1)">&#9664;</button>
                <img src="images/haircut1.jpg" alt="Haircut 1-1" class="active">
                <img src="images/haircut2.jpg" alt="Haircut 1-2">
                <img src="images/haircut3.jpg" alt="Haircut 1-3">
                <button class="arrow arrow-right" onclick="nextImage(1)">&#9654;</button>
            </div>
        </div>
        <!--Contains the name of Barber, availability, and scrollable images using the fuctions we defined earlier-->
        <div class="barber-container">
            <div class="barber-name">Jan Smith</div>
            <p class="availability">Available: Mon-Wed 9:00AM-8:00PM</p>
            <div class="barber-images barber-2">
                <button class="arrow arrow-left" onclick="prevImage(2)">&#9664;</button>
                <img src="images/haircut1.jpg" alt="Haircut 2-1" class="active">
                <img src="images/haircut2.jpg" alt="Haircut 2-2">
                <img src="images/haircut3.jpg" alt="Haircut 2-3">
                <button class="arrow arrow-right" onclick="nextImage(2)">&#9654;</button>
            </div>
        </div>
        <!--Contains the name of Barber, availability, and scrollable images using the fuctions we defined earlier-->
        <div class="barber-container">
            <div class="barber-name">Fred Bread</div>
            <p class="availability">Available: Mon-Sat 9:00AM-8:00PM</p>
            <div class="barber-images barber-3">
                <button class="arrow arrow-left" onclick="prevImage(3)">&#9664;</button>
                <img src="images/haircut1.jpg" alt="Haircut 3-1" class="active">
                <img src="images/haircut2.jpg" alt="Haircut 3-2">
                <img src="images/haircut3.jpg" alt="Haircut 3-3">
                <button class="arrow arrow-right" onclick="nextImage(3)">&#9654;</button>
            </div>
        </div>
        <div class="barber-container">
            <div class="barber-name">Billy Bob</div>
            <p class="availability">Available: Mon-Sat 9:00AM-8:00PM</p>
            <div class="barber-images barber-4">
                <button class="arrow arrow-left" onclick="prevImage(4)">&#9664;</button>
                <img src="images/haircut1.jpg" alt="Haircut 3-1" class="active">
                <img src="images/haircut2.jpg" alt="Haircut 3-2">
                <img src="images/haircut3.jpg" alt="Haircut 3-3">
                <button class="arrow arrow-right" onclick="nextImage(4)">&#9654;</button>
            </div>
        </div>
    </div>

    <!--Added section to work on the future for the reviews of the page-->
    <div class="reviews">
        <h2>Reviews</h2>
            <form action="reviews.php" method="POST">
                <!-- User information (required)-->
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="Anonymous" required><br><br>
                <label for="rating">Rating:</label><br>
                <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>
                <label for="comment">Comment:</label><br>
                <textarea type="text" id="comment" name="comment" required></textarea><br><br>
                <button type="submit">Send your Review!</button>
            </form>
    </div>
</body>
</html>
