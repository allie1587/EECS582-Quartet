<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/30/2025
    Revisions:
Purpose: Main Pagefor Barbers to see client feedback, allows to send an email back to answer
--> 
<?php

//Connects to database to get the table information
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the questions from the table
$FeedQuery = "SELECT Email, Name, Comment FROM Questions";
$FeedResult = $mysqli->query($FeedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style/style1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Feedback</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="menu">
        <button onclick="location.href='dashboard.php'">Dashboard</button>
        <button onclick="location.href='checkouts.php'">Checkouts</button>
        <button onclick="location.href='calendar.php'">Calendar</button>
        <button onclick="location.href='clients.php'">Clients</button>
        <button onclick="location.href='customize.php'">Customize</button>
        <button onclick="location.href='testing.html'">TESTING</button>
        <button onclick="location.href='see_feedback.php'">Feedback</button>
    </div>
    <h2>Client Feedback</h2>

    <?php
    if ($FeedResult->num_rows > 0) {
        while ($row = $FeedResult->fetch_assoc()) {
            echo "<div style='border:1px solid black; padding:10px; margin-bottom:10px;'>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($row["Name"]) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row["Email"]) . "</p>";
            echo "<p><strong>Comment:</strong> " . nl2br(htmlspecialchars($row["Comment"])) . "</p>";
            ?>
            
            <form action="send_mail.php" method="POST">
                <input type="hidden" name="email" value="<?= htmlspecialchars($row['Email']) ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($row['Name']) ?>">
                <input type="hidden" name="question" value="<?= htmlspecialchars($row['Comment']) ?>">
                <label for="answer">Your Response:</label>
                <textarea name="comment" required rows="4" cols="50"></textarea>
                <br>
                <button type="submit">Send Response</button>
            </form>
            
            <?php
            echo "</div>";
        }
    } else {
        echo "<p>No feedback available.</p>";
    }

    // Close database connection
    $mysqli->close();
    ?>

</body>
</html>