<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
    Revisions:
        3/1/2025  -- Jose, Stylizing Choices to page
        03/02/2025 -- Dark Mode Added
        03/09/2025 -- Jose -- Started Review Work
        3/13/2025 -- Jose -- Added implementation to see the previous reviews and average score of the baarbershop, started to change color scheme to red
        03/14/2025 -- Alexandra Stratton implementedd header.php
        03/14/2025 -- Alexandra Stratton Got rid of unnecessary information
        03/16/2025 -- Jose Leyba -- UI Changes, makes the Name and Rating on the same horizontal level, replaced number of ratings with a star system
Purpose: Main Page to see the barbershops, Barbers, Cuts, and Availabilities
--> 
<?php
// Start the session to remember user info
session_start();

// check to see if the user is logged in and give them a cute little welcome messagef
if (isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
    echo "<p class='welcome-message'>Welcome back, " . htmlspecialchars($_SESSION["user"], ENT_QUOTES, 'UTF-8') . "!</p>";
}

//Connects to database to get the Reviews table information
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the reviews from the table
$reviewsQuery = "SELECT Name, Rating, Review FROM Reviews ORDER BY ReviewID DESC";
$reviewsResult = $mysqli->query($reviewsQuery);

// Fetch the average rating
$avgRatingQuery = "SELECT AVG(Rating) AS avg_rating FROM Reviews";
$avgRatingResult = $mysqli->query($avgRatingQuery);
$avgRatingRow = $avgRatingResult->fetch_assoc();
$averageRating = $avgRatingRow['avg_rating'] ? number_format($avgRatingRow['avg_rating'], 2) : "N/A";

// Close the database connection
$mysqli->close();
?>

<?php
include('header.php');
?>
<head>
    <!--Name of Page-->
    <title>Home Page</title>
    <style>

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
       <!--Section that holds image of barbershop, followed by the Sotre Information-->
    <div class="store-info">
        <img src="images/store.jpg" alt="Store Image">
        <div class="store-text">
            <p><strong>Location:</strong> 123 Main St, Cityville</p>
            <p><strong>Hours:</strong> Mon-Sat: 9 AM - 8 PM, Sun: Closed</p>
            <p><strong>Information:</strong> Our store offers top-notch haircuts and grooming services.</p>
        </div>
    </div>
    

    <!--Added section to work on the future for the reviews of the page-->
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
            <br><br>
            <!--Shows the average rating to the users-->
            <div class="average-rating">
                Average Rating: <?php echo $averageRating; ?>
            </div>
            
            <div class="review-container">
                <h2>Reviews</h2>
                <!--Shows the reviews already stored in the database, if there is not then Print it to the screen-->
                <?php
                if ($reviewsResult->num_rows > 0) {
                    while ($row = $reviewsResult->fetch_assoc()) {
                        echo "<div class='review'>";
                        echo "<strong>" . htmlspecialchars($row['Name']) . "</strong>";
                        echo "<span class='rating'>Rating: " . htmlspecialchars($row['Rating']) . "/5</span>";
                        echo "<p>" . nl2br(htmlspecialchars($row['Review'])) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews available.</p>";
                }
                ?>
            </div>
    </div>

</body>
</html>
