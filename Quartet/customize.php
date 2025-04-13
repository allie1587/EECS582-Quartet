<!-- 
    customize.php
    A page for the barber to customize their site
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons, added form for barbers to add services, added form for barbers to set hours
    Creation date: 3/2/2025
-->

<?php
header("Location: dashboard.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db_connection.php");
$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check which form was submitted
    if (isset($_POST['name'])) {
        // Handle "Add Service" form submission
        $barber_id = 1; // Replace with barber's user name from session variable
        $name = $_POST['name'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];

        if (empty($name) || empty($price) || empty($duration)) {
            $error = "Please fill out all fields for the service.";
        } else {
            // Insert the service into the database
            $query = "INSERT INTO Barber_Services (barber_id, name, price, duration) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("isdi", $barber_id, $name, $price, $duration);
                if ($stmt->execute()) {
                    $success = "Service added successfully!";
                } else {
                    $error = "Error adding service: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Prepare failed: " . $conn->error;
            }
        }
    } elseif (isset($_POST['weekday'])) {
        // Handle "Set Hours" form submission
        $barber_id = 1; // Replace with barber's user name from session variable
        $weekday = $_POST['weekday'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $repeating = isset($_POST['repeating']) ? 'Y' : 'N';
        $month = $_POST['month'] ?? -1;
        $day = $_POST['day'] ?? -1;
        $year = $_POST['year'] ?? -1;
        $available = isset($_POST['available']) ? 1 : 0;

        if (empty($weekday) || empty($start_time) || empty($end_time)) {
            $error = "Please fill out all required fields for working hours.";
        } else {
            // Insert the working hours into the database
            $time = "$start_time-$end_time"; // Combine start and end time
            $query = "INSERT INTO Appointment_Availability (BarberID, Weekday, Time, Repeating, Month, Day, Year, Available) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("isssiiii", $barber_id, $weekday, $time, $repeating, $month, $day, $year, $available);
                if ($stmt->execute()) {
                    $success = "Working hours set successfully!";
                } else {
                    $error = "Error setting working hours: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Prepare failed: " . $conn->error;
            }
        }
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
    <link rel="stylesheet" href="style/style1.css">
</head>
<body>
    <div class="menu">
        <button onclick="location.href='dashboard.php'">Dashboard</button>
        <button onclick="location.href='checkouts.php'">Checkouts</button>
        <button onclick="location.href='calendar.php'">Calendar</button>
        <button onclick="location.href='clients.php'">Clients</button>
        <button onclick="location.href='customize.php'">Customize</button>
        <button onclick="location.href='see_feedback.php'">Feedback</button>

    </div>
    <button onclick="location.href='index.php'">Back to Customer Site</button>
    <form method="post" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>

    <h2>Customize your customer site</h2>

    <!-- Display error or success messages -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <h3>Add a New Service</h3>
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

    <h3>Set your hours</h3>
    <form method="POST">
        <label for="weekday">Weekday:</label>
        <select id="weekday" name="weekday" required>
            <option value="0">Sunday</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
        </select>
        <br><br>

        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required>
        <br><br>

        <label for="end_time">End Time:</label>
        <input type="time" id="end_time" name="end_time" required>
        <br><br>

        <label for="repeating">Repeating:</label>
        <input type="checkbox" id="repeating" name="repeating" value="Y">
        <br><br>

        <label for="month">Month (0-11, leave blank if repeating):</label>
        <input type="number" id="month" name="month" min="0" max="11">
        <br><br>

        <label for="day">Day (leave blank if repeating):</label>
        <input type="number" id="day" name="day" min="1" max="31">
        <br><br>

        <label for="year">Year (leave blank if repeating):</label>
        <input type="number" id="year" name="year" min="2023" max="2100">
        <br><br>

        <label for="available">Available:</label>
        <input type="checkbox" id="available" name="available" value="1" checked>
        <br><br>

        <button type="submit">Set Hours</button>
    </form>
</body>
</html>