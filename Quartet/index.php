<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Last modified: 02/16/2025
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
        body { /*Centers text and sets the font */
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .menu { /* Adds spacing for the menu*/
            margin-top: 20px;
        }
        .menu button { /*Styles the menu buttons */
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .top-bar { /*Creates a top navigation bar with a green background, white text, and flexible layout */
            background-color: green;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 50px;
        }
        .top-bar h1 { /*Styles the header text in the top bar */
            margin: 0;
            padding-left: 20px;
            font-size: 24px;
        }
        .login-container { /*Aligns login button and text */
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
        .login-button { /*Styles a circular login button */
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
        .db-box { /*Adds margin above the database input section */
            margin-top: 20px;
        }
        .store-info { /*Arranges store information in a flexbox */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }
        .store-info img { /*Sizes the store image and adds spacing */
            width: 400px;
            height: auto;
            margin-right: 20px;
        }
        .store-text { /*Aligns store text */
            text-align: left;
        }
        .barbers { /*Creates a flexbox for barber profiles */
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .barber-container { /*Organizes barber profile content */
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .barber-name { /*Styles barber names */
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barber-images { /*Centers barber image containers */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            max-width: 600px;
        }
        .barber-images img { /*Initially hides images */
            width: 300px;
            height: auto;
            display: none;
        }
    
        .availability { /*Highlights barber availability */
            font-weight: bold;
            color: green;
        }
        .barber-images img.active { /*Ensures only the selected image is visible */
            display: block;
        }
        .arrow { /*Styles navigation arrows for scrolling through barber images */
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border: none;
        }
        .arrow-left { /*Positions the left arrow */
            left: 0;
        }
        .arrow-right { /*Positions the right arrow */
            right: 0;
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
        <h1>Quartet's Amazing Barbershop</h1>
        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>Home</h1>
    <!--Menu with all possible pages-->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='page4.html'">Page 4</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>
    
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
            <div class="barber-name">Pedro</div>
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
            <div class="barber-name">Sebastian</div>
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
            <div class="barber-name">Jean Marque III, future King of Zambodia</div>
            <p class="availability">Available: Mon-Sat 9:00AM-8:00PM</p>
            <div class="barber-images barber-3">
                <button class="arrow arrow-left" onclick="prevImage(3)">&#9664;</button>
                <img src="images/haircut1.jpg" alt="Haircut 3-1" class="active">
                <img src="images/haircut2.jpg" alt="Haircut 3-2">
                <img src="images/haircut3.jpg" alt="Haircut 3-3">
                <button class="arrow arrow-right" onclick="nextImage(3)">&#9654;</button>
            </div>
        </div>
    </div>

    <!--Added section to work on the future for the reviews of the page-->
    <div class="reviews">
        <h2>Reviews</h2>
        <p>(Coming soon...)</p>
    </div>
</body>
</html>
