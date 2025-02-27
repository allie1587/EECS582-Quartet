<!-- 
    week_schedule.php
    A page to hold the appointment calendar and scheduler.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/27/2025 -- Alexandra Stratton, add about barber page
    Creation date:
-->
<?php
$barbers = [
    [
        "name" => "Danny DeVito",
        "role" => "Owner, Barber",
        "photo" => "images/danny_DeVito.jpg",
        "services" => ["Classic Fades", "Razor Shaves", "Beard Grooming", "Custom Hair Designs"],
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
        "bio" => "bio",
        "contact" => [
            "instagram" => "https://instagram.com/dannydevito9",
            "phone" => "+1234567890",
            "email" => "danny@devitosbarbers.com"
        ]
    ],
    [
        "name" => "Pitbull",
        "role" => "Master Barber",
        "photo" => "images/pitbull.jpg",
        "services" => ["Shaved Head", "Beard Grooming"],
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
        "bio" => "bio",
        "contact" => [
            "instagram" => "https://instagram.com/pitbull",
            "phone" => "+9876543210",
            "email" => "pitbull@devitosbarbers.com"
        ]
    ],
    [
            "name" => "Guy Fieri",
            "role" => "Licensed Barber",
            "photo" => "images/guy_fieri.jpeg",
            "services" => ["Modern Styles", "Razor Shaves", "Beard Grooming"],
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
            "bio" => "bio.",
            "contact" => [
                "instagram" => "https://instagram.com/guyfieri",
                "phone" => "+9876543210",
                "email" => "guy@devitosbarbers.com"
            ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Name of Page-->
    <title>Barber Page</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
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
            color: white;
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
    <script>
        function showImage(barberIndex, index) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            images.forEach(img => img.classList.remove("active"));
            images[index].classList.add("active");
        }

        function nextImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex + 1) % images.length;
            showImage(barberIndex, currentIndex);
        }

        function prevImage(barberIndex) {
            let images = document.querySelectorAll(`.barber-${barberIndex} img`);
            if (images.length === 0) return;

            let currentIndex = Array.from(images).findIndex(img => img.classList.contains("active"));
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(barberIndex, currentIndex);
        }

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
        <h1>Quartet's Amazing Barbershop</h1>
        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--Menu with all possible pages-->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='barbers.php'">Barbers</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>
<h1>Barbers</h1>
<div class="barbers">
    <?php foreach ($barbers as $index => $barber): ?>
        <div class="barber-container">
            <img src="<?php echo $barber['photo']; ?>" alt="<?php echo $barber['name']; ?>" class="barber-photo">
            <div class="barber-name"><?php echo $barber['name']; ?></div>
            <div class="barber-role"><strong>Role: </strong><?php echo $barber['role']; ?></div>
            <div class="services"><strong>Services: </strong><?php echo implode(", ", $barber['services']); ?></div>
            
            <div class="hours">
                <h3>Availability</h3>
                <?php foreach ($barber['hours'] as $day => $hours): ?>
                    <p><strong><?php echo $day; ?></strong>: <?php echo $hours; ?></p>
                <?php endforeach; ?>
            </div>

            <h3>Portfolio</h3>
            <div class="gallery-container barber-<?php echo $index; ?>">
                <button class="arrow arrow-left" onclick="prevImage(<?php echo $index; ?>)">&#9664;</button>
                <?php foreach ($barber['gallery'] as $img_index => $image): ?>
                    <img src="<?php echo $image; ?>" class="<?php echo $img_index === 0 ? 'active' : ''; ?>">
                <?php endforeach; ?>
                <button class="arrow arrow-right" onclick="nextImage(<?php echo $index; ?>)">&#9654;</button>
            </div>

            <h3>About <?php echo $barber['name']; ?></h3>
            <p><?php echo $barber['bio']; ?></p>

            <div class="contact">
                <h3>Contact</h3>
                <p class=contact-info>Phone: </p>
                <a href="tel:<?php echo $barber['contact']['phone']; ?>"> <?php echo $barber['contact']['phone']; ?></a><br>
                <p class=contact-info>Email: </p>
                <a href="mailto:<?php echo $barber['contact']['email']; ?>"><?php echo $barber['contact']['email']; ?></a><br>
                <p class=contact-info>Social Media: </p>
                <a href="<?php echo $barber['contact']['instagram']; ?>" target="_blank">Instagram</a><br>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>