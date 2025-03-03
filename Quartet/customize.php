<!-- 
    customize.php
    A page for the barber to customize their site
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons, added form for barbers to add services
    Creation date: 3/2/2025
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db_connection.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barber_id = 1; // Replace with barber's user name from session variable
    $name = $_POST['name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    if (empty($name) || empty($price) || empty($duration)) {
        die("Please fill out all fields.");
    }
    // Insert the service into the database
    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO Barber_Services (barber_id, name, price, duration) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        // Bind parameters and execute the statement
        $stmt->bind_param("isdi", $barber_id, $name, $price, $duration);
        $stmt->execute();
        echo "Service added successfully!";
    } catch (Exception $e) {
        die("Error adding service: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barber Customize</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>Cusomize your customer site</h1>
    <div class="menu">
    <button onclick="location.href='dashboard.php'">Dashboard</button>
    <button onclick="location.href='checkouts.php'">Checkouts</button>
    <button onclick="location.href='calendar.php'">Calendar</button>
    <button onclick="location.href='clients.php'">Clients</button>
    <button onclick="location.href='customize.php'">Customize</button>
    </div>
    <button onclick="location.href='index.php'">Back to Customer Site</button>
    <form method="post" action="logout.php">
    <button type="submit" name="logout">Logout</button>
    </form>

    <h2>Add a New Service</h2>
    <form method="POST">
        <label for="name">Service Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>
        <label for="price">Price ($):</label>
        <input type="number" id="price" name="price" step="0.01" required>
        <br><br>
        <label for="duration">Duration (minutes):</label>
        <input type="number" id="duration" name="duration" required>
        <br><br>
        <button type="submit">Add Service</button>
    </form>
</body>
</html>