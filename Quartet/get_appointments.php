<?php
/*  
    get_appointments.php
    A program to connect to the database and return the count of the appointments for a certain date.
    Authors: Brinley Hull
    Creation date: 2/27/2025
    Revisions:
        3/16/2025 - Brinley, add filtering
        3/28/2025 - Brinley, remove confirmed appointments
*/
session_start(); //start the session

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response

//connect to the database
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
$query = "SELECT * FROM Appointment_Availability a 
          WHERE Available='Y' 
          AND (Weekday=? OR (Month=? AND Day=? AND Year=?))
          AND NOT EXISTS (SELECT 1 FROM Confirmed_Appointments c
                WHERE c.Barber_ID = a.Barber_ID 
                AND c.Time = a.Time 
                AND c.Month = ?
                AND c.Day = ?
                AND c.Year = ?)
          AND NOT EXISTS (SELECT 1 FROM Appointment_Availability b
                WHERE a.barberID = b.barberID
                AND a.Time = b.Time
                AND b.Month = ?
                AND b.Day = ?
                AND b.Year = ?
                AND b.Available = 'N')"; // AND NOT EXISTS statement removes confirmed or nonavailble appointments from list

// check for barber and time filtering
if ($barberID !== null) {
    $query .= " AND Barber_ID=?";
}
if ($time !== null) {
    $query .= " AND Time=?";
}

// order by time
$query .= " ORDER BY Time";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters
if ($barberID !== null && $time !== null) {
    $stmt->bind_param("iiiiiiiiiiss", $weekday, $month, $day, $year, $month, $day, $year, $month, $day, $year, $barberID, $time);
} else if ($barberID !== null) {
    $stmt->bind_param("iiiiiiiiiis", $weekday, $month, $day, $year, $month, $day, $year, $month, $day, $year, $barberID);
} else if ($time !== null) {
    $stmt->bind_param("iiiiiiiiiis", $weekday, $month, $day, $year, $month, $day, $year, $month, $day, $year, $time);
} else {
    $stmt->bind_param("iiiiiiiiii", $weekday, $month, $day, $year, $month, $day, $year, $month, $day, $year);
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
