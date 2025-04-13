<!--
testimonies.php
Purpose: Allows barbers to select the reviews to showcase in the Main Client Page
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 04/08/2025
    Revisions:

--> 

<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Adds or Removes the Review from the Testimonies Table depending on the pressed button
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewID = intval($_POST['review_id']);

    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO Testimonies (Testimony_ID, Name, Rating, Review)
                                SELECT Review_ID, Name, Rating, Review FROM Reviews
                                WHERE Review_ID = ? AND Review_ID NOT IN (SELECT Testimony_ID FROM Testimonies)");
        $stmt->bind_param("i", $reviewID);
        $stmt->execute();
    }

    if (isset($_POST['remove'])) {
        $stmt = $conn->prepare("DELETE FROM Testimonies WHERE Testimony_ID = ?");
        $stmt->bind_param("i", $reviewID);
        $stmt->execute();
    }
}

// Gets all the reviews from the Database
$reviewsQuery = "SELECT Review_ID, Name, Rating, Review FROM Reviews ORDER BY Review_ID DESC";
$reviewsResult = $conn->query($reviewsQuery);

// Stores the Testimonies_ID to see which reviews are already on the table
$testimoniesQuery = "SELECT Testimony_ID FROM Testimonies";
$testimoniesResult = $conn->query($testimoniesQuery);

$testimoniesIDs = [];
while ($row = $testimoniesResult->fetch_assoc()) {
    $testimoniesIDs[] = $row['Testimony_ID'];
}

$barber_id = $_SESSION['username'];
$sql = "SELECT Barber_Information.Role FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role == "Barber") {
    include("barber_header.php");
}
else {
    include("manager_header.php");
}?>
<head>
    <style>
        .reviews {
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
        form {
            margin-top: 10px;
        }
        button {
            margin-right: 10px;
        }
        .content-wrapper {
            transition: margin-left 0.3s ease;
            margin-left: 10px;
        }

        .sidebar-active .content-wrapper {
            margin-left: 300px; 
        }

        .sidebar-deactive .content-wrapper {
            margin-left: 10px; 
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
    <br><br>
        <div class="reviews">
            <h2>User Reviews</h2>            
            <div class="review-container">
                <?php
                //When there are any reviews
                if ($reviewsResult->num_rows > 0) {
                    //It will iterate through all the reviews, showing them to the barber
                    while ($row = $reviewsResult->fetch_assoc()) {
                        $reviewID = $row['Review_ID'];
                        $isInTestimonies = in_array($reviewID, $testimoniesIDs);

                        echo "<div class='review'>";
                        echo "<strong>" . htmlspecialchars($row['Name']) . "</strong>";
                        echo "<span class='rating'>Rating: " . htmlspecialchars($row['Rating']) . "/5</span>";
                        echo "<p>" . nl2br(htmlspecialchars($row['Review'])) . "</p>";

                        echo "<form method='post'>";
                        echo "<input type='hidden' name='review_id' value='" . $reviewID . "' />";
                                    
                        
                        //It will activate the curresponding button to add/remove to/from Testimonies table
                        echo "<button type='submit' name='add' " . ($isInTestimonies ? "disabled" : "") . ">Add to Testimonies</button>";
                        echo "<button type='submit' name='remove' " . (!$isInTestimonies ? "disabled" : "") . ">Remove from Testimonies</button>";
                        echo "</form>";

                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews available.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>