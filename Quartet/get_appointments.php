<?php
/* 
get_appointments.php 
A program to connect to the database and return the appointments for a certain date and with filters.
Authors: Brinley Hull, Alexandra Stratton, Kyle Moore, Ben Renner, Jose Leyba
Creation date: 2/27/2025
Revisions:
    3/16/2025 - Brinley, add filtering
*/
session_start(); //start the session to hold and gather session variables

// error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response

// connect to database
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]));
}

// Validate input and set each variable
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
$day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
$weekday = isset($_GET['weekday']) ? $_GET['weekday'] : 0;
$barberID = isset($_SESSION['barberFilter']) ? ($_SESSION['barberFilter'] == "" ? null : $_SESSION['barberFilter']) : null;
$time = isset($_SESSION['timeFilter']) ? ($_SESSION['timeFilter'] == "" ? null : $_SESSION['timeFilter']) : null;

// Prepare SQL query to get appointments holding specific criteria
$query = "SELECT * FROM Appointment_Availability 
          WHERE Available='Y' 
          AND (Weekday=? OR (Month=? AND Day=? AND Year=?))";

// add filtering to the query based on whether the filter is set
if ($barberID !== null) {
    $query .= " AND BarberID=?";
}
if ($time !== null) {
    $query .= " AND Time=?";
}

// add the final order by command to the query to list appointment times in order
$query .= " ORDER BY Time";

// check query
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters -- will need refining later to make it more efficient.
if ($barberID !== null && $time !== null) {
    $stmt->bind_param("iiiiss", $weekday, $month, $day, $year, $barberID, $time);
} else if ($barberID !== null) {
    $stmt->bind_param("iiiis", $weekday, $month, $day, $year, $barberID);
} else if ($time !== null) {
    $stmt->bind_param("iiiis", $weekday, $month, $day, $year, $time);
} else {
    $stmt->bind_param("iiii", $weekday, $month, $day, $year);
}

//send query to database
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die(json_encode(["error" => "SQL execution failed: " . $stmt->error]));
}

// Fetch all rows
$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

// Return JSON response
echo json_encode($appointments, JSON_PRETTY_PRINT);

//close connections
$stmt->close();
$mysqli->close();
?>
