<!-- 
    barber.php
    A page that holds the information about the barbers
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        02/27/2025 -- Alexandra Stratton, add about barber page
        02/28/2025 -- Alexandra Stratton, fixed barber information
        03/02/2025 -- Jose Leyba, Modifieid UI Looks/ Added Barber Images
        03/29/2025 -- Alexandra Strattion -- Unhardcode
    Sources:
        - https://www.freshkillsbarbershop.com/barber-bios
            -- Grabbed professional headshots for the hardcoded barbers
    Creation date: 2/27/2025
-->


<!-- Hardcode information about the "barbers" for video purposes -->

<?php
// Connects to the database
require 'db_connection.php';
// Initalize a barbers list
$barbers = [];
$sql = "SELECT * FROM Barber_Information";
$barber_result = $conn->query($sql);
// When there are barbers retrieve more information
if ($barber_result->num_rows > 0) {
    // Loop through all the possible barbers
    while ($barber = $barber_result->fetch_assoc()) {
        // Get the barbers Username which is equivalent to the barber_id in other tables
        $username = $barber['Username'];
        // Retrieve that barbers services for Barber_Services
        $services = [];
        $stmt = $conn->prepare("SELECT name FROM Barber_Services WHERE barber_id = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $services_result = $stmt->get_result();
        while ($service = $services_result->fetch_assoc()) {
            $services[] = $service['name'];
        }
        $barber['services'] = $services;


        // Retrieves the barbers usual weekly availability from Usual_Hours
        $availability = [];
        $stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM Barber_Availability WHERE barber_id = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $hours_result = $stmt->get_result();
        while ($hour = $hours_result->fetch_assoc()) {
            $day = $hour['day_of_week'];
            $start = date("g:i a", strtotime($hour['start_time']));
            $end = date("g:i a", strtotime($hour['end_time']));
            $availability[$day] = "$start - $end";
        }
        $barber['availability'] = $availability;


        // Retrieves the barbers portfolio from Barber_Gallery
        $gallery = [];
        $stmt = $conn->prepare("SELECT image FROM Barber_Gallery WHERE barber_id = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $gallery_result = $stmt->get_result();
        while ($image = $gallery_result->fetch_assoc()) {
            $gallery[] = $image['image'];
        }
        $barber['gallery'] = $gallery;

        $barbers[] = $barber;
    }
}
?>
<!-- Includes the header -->
<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <!--Name of Page-->
    <title>Barbers</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* General Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            /* Adjust to match your site’s header */
            color: white;
            padding: 15px;
            text-align: center;
            z-index: 1000;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 80px;
            color: black;;
            line-height: 1.6;
        }

        /* Main Container */
        .barbers {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Barber Card */
        .barber-container {
            width: 320px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            color: color;
        }
        /* Barber Name */
        .barber-name {
            font-size: 24px;
            font-weight: 700;
            margin: 15px 0;
            text-align: center;
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            color: black;

        }

        /* Barber Photo */
        .barber-photo {
            width: 280px;
            height: 280px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        /* Services Section */
        .services {
            font-size: 16px;
            margin: 15px 0;
            padding: 10px;
            border-radius: 8px;
        }

        .services strong {
            color: black;
            display: block;
            margin-bottom: 5px;
        }

        /* Availability Section */
        .hours {
            margin: 15px 0;
            text-align: left;
            width: 100%;
            padding: 15px;
            border-radius: 10px;
        }

        .hours h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: black;
            text-align: center;
        }

        .hours p {
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
        }

        .hours strong {
            color: black;
            font-weight: 500;
        }
        /* Style for their portfolio images */
        .gallery-container {
            text-align: center;
            color: black;
            max-width: 300px;
            margin-top: 10px auto;
            gap: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-container img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            text-align: center;
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
            color: rgba(36, 35, 35);
            cursor: pointer;
            padding: -10px;
        }

        .arrow:hover {
            color: rgba(36, 35, 35);
        }

        .arrow-left {
            left: -50px;
        }

        .arrow-right {
            right: -50px;
        }


        /* Contact Section */
        .contact {
            margin-top: 15px;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
        }

        .contact>p:first-child {
            color: black;
            font-weight: 600;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .contact-info {
            margin: 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .contact-info a {
            color: black;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: black;
        }

        .contact-info i {
            color: black;
        }

        /* Social Media */
        .social-media {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-media a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: transform 0.3s ease, opacity 0.3s ease;
            text-decoration: none;
            /* Removes underline */
        }

        .social-media a:hover {
            transform: scale(1.1) translateY(-3px);
            opacity: 0.9;
        }

        .fa-facebook {
            background: #3b5998;
        }

        .fa-instagram {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .fa-tiktok {
            background: #000;
        }

        .fa-envelope,
        .fa-phone {
            color: red;
            margin-right: 5px;
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
    <div class="barbers">
        <!-- Loop through PHP array to display each barber's profile dynamically -->
        <?php foreach ($barbers as $index => $barber): ?>
            <div class="barber-container">
                <!-- Displays the picture of the barber -->
                <?php if (!empty($barber['Photo'])): ?>
                    <img src="<?php echo htmlspecialchars($barber['Photo']); ?>" alt="<?php echo htmlspecialchars($barber['First_Name'] . ' ' . $barber['Last_Name']); ?>" class="barber-photo">
                <?php endif; ?>
                <!-- Displays the name of that barber -->
                <div class="barber-name"><?php echo htmlspecialchars($barber['First_Name'] . ' ' . $barber['Last_Name']); ?></div>
                <!-- Displays the services that barber offers -->
                <?php if (!empty($barber['services'])): ?>
                    <div class="services"><strong>Services: </strong><?php echo htmlspecialchars(implode(", ", $barber['services'])); ?></div>
                <?php else: ?>
                    <div class="services"><strong>Services: </strong>None</div>
                <?php endif; ?>

                <!-- Displays the usual weekly availability of that barber -->
                <div class="hours">
                    <h3>Availability</h3>
                    <?php
                    $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                    foreach ($daysOfWeek as $day): ?>
                        <p>
                            <strong><?php echo htmlspecialchars($day); ?>:</strong>
                            <?php echo isset($barber['availability'][$day]) ? htmlspecialchars($barber['availability'][$day]) : "None"; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
                <!-- Displays the portfolio of that barber -->
                <?php if (!empty($barber['gallery'])): ?>
                    <h3>Portfolio</h3>
                    <div class="gallery-container barber-<?php echo $index; ?>">
                        <button class="arrow arrow-left" onclick="prevImage(<?php echo $index; ?>)">&#9664;</button>
                        <?php foreach ($barber['gallery'] as $img_index => $image): ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" class="<?php echo $img_index === 0 ? 'active' : ''; ?>">
                        <?php endforeach; ?>
                        <button class="arrow arrow-right" onclick="nextImage(<?php echo $index; ?>)">&#9654;</button>
                    </div>
                <?php endif; ?>
                <!-- Displays all the contact information of that barber -->
                <div class="contact">
                    <?php if (!empty($barber['Email']) || !empty($barber['Phone_Number']) || !empty($barber['Facebook']) || !empty($barber['Instagram']) || !empty($barber['TikTok'])): ?>
                        <p><strong>Contact Info</strong></p>
                    <?php endif; ?>

                    <?php if (!empty($barber['Email'])): ?>
                        <p class="contact-info">
                            <i class="fa fa-envelope"></i>
                            <a href="mailto:<?php echo htmlspecialchars($barber['Email']); ?>"><?php echo htmlspecialchars($barber['Email']); ?></a>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($barber['Phone_Number'])): ?>
                        <p class="contact-info">
                            <i class="fa fa-phone"></i>
                            <a href="tel:<?php echo htmlspecialchars($barber['Phone_Number']); ?>"><?php echo htmlspecialchars($barber['Phone_Number']); ?></a>
                        </p>
                    <?php endif; ?>
                    <div class="social-media">
                        <?php if (!empty($barber['Facebook'])): ?>
                            <a href="https://www.facebook.com/<?php echo htmlspecialchars($barber['Facebook']); ?>" target="_blank" class="fa-brands fa-facebook"></a>
                        <?php endif; ?>

                        <?php if (!empty($barber['Instagram'])): ?>
                            <a href="https://www.instagram.com/<?php echo htmlspecialchars($barber['Instagram']); ?>" target="_blank" class="fa-brands fa-instagram"></a>
                        <?php endif; ?>

                        <?php if (!empty($barber['TikTok'])): ?>
                            <a href="https://www.tiktok.com/@<?php echo htmlspecialchars($barber['TikTok']); ?>" target="_blank" class="fa-brands fa-tiktok"></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <br><br>
    </div>
</body>