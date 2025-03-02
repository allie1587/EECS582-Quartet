<!-- 
    barber.php
    A page that holds the information about the barbers
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        02/27/2025 -- Alexandra Stratton, add about barber page
        02/28/2025 -- Alexandra Stratton, fixed barber information
        03/02/2025 -- Jose Leyba, Modifieid UI Looks/ Added Barber Images
    Sources:
        - https://www.freshkillsbarbershop.com/barber-bios
            -- Grabbed professional headshots for the hardcoded barbers
    Creation date: 2/27/2025
-->


<!-- Hardcode information about the "barbers" for video purposes -->

<?php
$barbers = [
    [
        "name" => "John Doe",
        "role" => "Owner, Barber",
        "photo" => "images/barber1.png",
        "services" => ["Haircut", "Beard Trim"],
        "hours" => [
            "Monday" => "9:00AM - 8:00PM",
            "Tuesday" => "9:00AM - 8:00PM",
            "Wednesday" => "9:00AM - 8:00PM",
            "Thursday" => "9:00AM - 8:00PM",
            "Friday" => "9:00AM - 8:00PM",
            "Saturday" => "9:00AM - 8:00PM",
            "Sunday" => "9:00AM - 8:00PM"
        ],
        "gallery" => ["images/haircut1.jpg", "images/haircut2.jpg", "images/haircut3.jpg"],
        "contact" => [
            "phone" => "6181234567",
            "email" => "jDoe@jmail.com"
        ]
    ],
    [
        "name" => "Jan Smith",
        "role" => "Owner, Barber",
        "photo" => "images/barber3.png",
        "services" => ["Shave" , "Haircut"],
        "hours" => [
            "Monday" => "9:00AM - 8:00PM",
            "Tuesday" => "9:00AM - 8:00PM",
            "Wednesday" => "9:00AM - 8:00PM",
            "Thursday" => "9:00AM - 8:00PM",
            "Friday" => "9:00AM - 8:00PM",
            "Saturday" => "9:00AM - 8:00PM",
            "Sunday" => "9:00AM - 8:00PM"
        ],
        "gallery" => ["images/haircut1.jpg", "images/haircut2.jpg", "images/haircut3.jpg"],
        "contact" => [
            "phone" => "9876543210",
            "email" => "jSmith@coldmail.com"
        ]
    ],
    [
            "name" => "Fred Bread",
            "role" => "Master Barber",
            "photo" => "images/barber2.png",
            "services" => ["Fade", "Beard Trim"],
            "hours" => [
                "Monday" => "9:00AM - 8:00PM",
                "Tuesday" => "9:00AM - 8:00PM",
                "Wednesday" => "9:00AM - 8:00PM",
                "Thursday" => "9:00AM - 8:00PM",
                "Friday" => "9:00AM - 8:00PM",
                "Saturday" => "9:00AM - 8:00PM",
                "Sunday" => "9:00AM - 8:00PM"
            ],
            "gallery" => ["images/haircut1.jpg", "images/haircut2.jpg", "images/haircut3.jpg"],
            "contact" => [
                "phone" => "1231230123",
                "email" => "fBread@yippie.com"
            ]
        
        ],
        [
                "name" => "Billy Bob",
                "role" => "Master Barber",
                "photo" => "images/barber4.png",
                "services" => ["Buzz Cut", "Hot Towel Shave"],
                "hours" => [
                    "Monday" => "9:00AM - 8:00PM",
                    "Tuesday" => "9:00AM - 8:00PM",
                    "Wednesday" => "9:00AM - 8:00PM",
                    "Thursday" => "9:00AM - 8:00PM",
                    "Friday" => "9:00AM - 8:00PM",
                    "Saturday" => "9:00AM - 8:00PM",
                    "Sunday" => "9:00AM - 8:00PM"
                ],
                "gallery" => ["images/haircut1.jpg", "images/haircut2.jpg", "images/haircut3.jpg"],
                "contact" => [
                    "phone" => "4566544567",
                    "email" => "bBob@inlook.com"
            ]
        ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Name of Page-->
    <title>Barber Page</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* Style for the entire page */
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        /* Styles for the top navigation bar */ 
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
            color: white;
        }
        /* Style for the login button */
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
        /* Style for the menu */
        .menu {
            margin-top: 20px;
        }
        .menu button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        /* Style for each barber profile */
        .barber-container{
            width: 320px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;

        }
        .barbers { 
            gap: 20px;
            background-color: #f4f4f4;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        
        .barber-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barber-role strong {
            font-weight: bold;
        }
        .services strong {
            font-weight: bold;
        }
        .barber-images {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            max-width: 600px;
        }
        .barber-images img { 
            width: 300px;
            height: auto;
            display: none;
        }
        .barber-photo {
            width: 250px; 
            height: 250px; 
            object-fit: cover; 
            
            margin-bottom: 10px;
        }
        /* Style for their portfolio images */
        .gallery-container { 
            display: flex;
            justify-content: center;
            position: relative;
            max-width: 300px;
            margin-top: 10px;
        }

        .gallery-container img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            display: none;
        }

        .gallery-container img.active {
            display: block;
        }

        /* Style the arrows */
        .arrow {
            background: none; 
            border: none; 
            font-size: 30px; 
            color: black; 
            cursor: pointer;  
            padding: 5px; 
        }
        .arrow:hover {
            color: #555; 
        }

        .arrow-left {
            left: -50px; 
        }

        .arrow-right {
            right: -50px; 
        }
        
        .availability { 
            font-weight: bold;
            color: green;
        }

        .contact-info {
            display: inline;
            font-weight: bold;
        }
        

    </style>
    <!-- JavaScript for handling barber image gallery -->
    <script>
        /* Function to show a specific image in the barber's portfolio */
        function showImage(barberIndex, index) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            images.forEach(img => img.classList.remove("active"));
            images[index].classList.add("active");
        }
        /* Function to show the next image in the barber's portfolio */
        function nextImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex + 1) % images.length;
            showImage(barberIndex, currentIndex);
        }
        /* Function to show the previous image in the barber's portfolio */
        function prevImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(barberIndex, currentIndex);
        }
        /* Ensures the first image is displayed when the page loads */
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.gallery-container').forEach((gallery, index) => {
                showImage(index, 0);
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
            <button onclick="location.href='about.php'">About Us</button>
        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
<h1>Barbers</h1>
<div class="barbers">
    <!-- Loop through PHP array to display each barber's profile dynamically -->
    <?php foreach ($barbers as $index => $barber): ?>
        <div class="barber-container">
            <!-- Displays the barber's name, role, and services -->
            <img src="<?php echo $barber['photo']; ?>" alt="<?php echo $barber['name']; ?>" class="barber-photo">
            <div class="barber-name"><?php echo $barber['name']; ?></div>
            <div class="barber-role"><strong>Role: </strong><?php echo $barber['role']; ?></div>
            <div class="services"><strong>Services: </strong><?php echo implode(", ", $barber['services']); ?></div>
            <!-- Displays the barbers usually hours -->
            <div class="hours">
                <h3>Availability</h3>
                <?php foreach ($barber['hours'] as $day => $hours): ?>
                    <p><strong><?php echo $day; ?></strong>: <?php echo $hours; ?></p>
                <?php endforeach; ?>
            </div>
            <!-- Displays the barbers portfolio images -->
            <h3>Portfolio</h3>
            <div class="gallery-container barber-<?php echo $index; ?>">
                <button class="arrow arrow-left" onclick="prevImage(<?php echo $index; ?>)">&#9664;</button>
                <?php foreach ($barber['gallery'] as $img_index => $image): ?>
                    <img src="<?php echo $image; ?>" class="<?php echo $img_index === 0 ? 'active' : ''; ?>">
                <?php endforeach; ?>
                <button class="arrow arrow-right" onclick="nextImage(<?php echo $index; ?>)">&#9654;</button>
            </div>
            <!-- Displays the contact information of the barbers -->
            <div class="contact">
                <h3>Contact</h3>
                <p class=contact-info>Phone: </p>
                <a href="tel:<?php echo $barber['contact']['phone']; ?>"> <?php echo $barber['contact']['phone']; ?></a><br>
                <p class=contact-info>Email: </p>
                <a href="mailto:<?php echo $barber['contact']['email']; ?>"><?php echo $barber['contact']['email']; ?></a><br>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- Link to display past and upcoming appointments -->
<div class="user-appointments">
            <a href="#" onclick="openAppointmentsModal()">View Upcoming/Past Appointments</a>
        </div>
        <!-- Past and Upcoming Appointment popup -->
        <div id="appointment-modal" class="popup">
            <span class="close-btn" onclick="closeAppointmentsModal()">&times;</span>
            <h2>Your Appointments</h2>
            <h3>Upcoming Appointment</h3>
            <p>Date: March 10, 2025</p>
            <p>Time: 2:00 PM</p>
            <p>Barber: John Doe</p>

            <h3>Past Appointment</h3>
            <p>Date: February 15, 2025</p>
            <p>Time: 11:00 AM</p>
            <p>Barber: John Doe</p>
            </div>
        </div>
</div>
<style>
    /* Popup styling */
    .popup {
            display: none; /* Hidden by default */
            position: fixed;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1000;
        }

        /* Close button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
</style>
<script>
    // Open the appointment modal
    function openAppointmentsModal() {
        document.getElementById('appointment-modal').style.display = 'block';
    }
    //Close the appointment modal
    function closeAppointmentsModal() {
        document.getElementById('appointment-modal').style.display = 'none';
    }
</script>
</body>
</html>
