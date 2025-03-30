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
    <link rel="stylesheet" href="style/styles.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 80px;
            color: black;
            background-color: #f4f4f4;
            line-height: 1.6;
        }

    </style>



    <!--Name of Page-->
    <title>Barbers</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->



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