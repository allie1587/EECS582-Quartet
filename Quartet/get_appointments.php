<?php
/*// A program to connect to the database and return the count of the appointments for a certain date.
// Creation date: 2/27/2025
// Revisions:
    3/16/2025 - Brinley, add filtering
*/
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response

$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]));
}

// Validate input
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
$day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
$weekday = isset($_GET['weekday']) ? $_GET['weekday'] : 0;
$barberID = isset($_SESSION['barberFilter']) ? ($_SESSION['barberFilter'] == "" ? null : $_SESSION['barberFilter']) : null;
$time = isset($_SESSION['timeFilter']) ? ($_SESSION['timeFilter'] == "" ? null : $_SESSION['timeFilter']) : null;

// Prepare SQL query
$query = "SELECT * FROM Appointment_Availability 
          WHERE Available='Y' 
          AND (Weekday=? OR (Month=? AND Day=? AND Year=?))";

if ($barberID !== null) {
    $query .= " AND BarberID=?";
}
if ($time !== null) {
    $query .= " AND Time=?";
}

$query .= " ORDER BY Time";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters
if ($barberID !== null && $time !== null) {
    $stmt->bind_param("iiiiss", $weekday, $month, $day, $year, $barberID, $time);
} else if ($barberID !== null) {
    $stmt->bind_param("iiiis", $weekday, $month, $day, $year, $barberID);
} else if ($time !== null) {
    $stmt->bind_param("iiiis", $weekday, $month, $day, $year, $time);
} else {
    $stmt->bind_param("iiii", $weekday, $month, $day, $year);
}
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

$stmt->close();
$mysqli->close();
?>
