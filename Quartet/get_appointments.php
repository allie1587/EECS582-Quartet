<?php
/*  
    get_appointments.php
    A program to connect to the database and return the count of the appointments for a certain date.
    Authors: Brinley Hull
    Creation date: 2/27/2025
    Revisions:
        3/16/2025 - Brinley, add filtering
        3/28/2025 - Brinley, remove confirmed appointments
        4/2/2025 - Brinley, refactoring
        4/10/2025 - Brinley, hide overlapping appointment times
*/
session_start(); //start the session

require 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response

// Validate input
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
$day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
$weekday = isset($_GET['weekday']) ? $_GET['weekday'] : date('w', strtotime("$year-$month-$day"));
$barberID = isset($_SESSION['barberFilter']) ? ($_SESSION['barberFilter'] == "" ? null : $_SESSION['barberFilter']) : null;
$time = isset($_SESSION['timeFilter']) ? ($_SESSION['timeFilter'] == "" ? null : $_SESSION['timeFilter']) : null;

// Prepare SQL query
$query = "SELECT * FROM Appointment_Availability a 
          WHERE Available='Y' 
          AND (Weekday=? OR (Month=? AND Day=? AND Year=? 
                AND NOT EXISTS (SELECT 1 FROM Appointment_Availability d WHERE 
                                d.Barber_ID = a.Barber_ID
                                AND d.Time = a.Time 
                                AND d.Minute = a.Minute
                                AND d.Weekday = ?)))
          AND NOT EXISTS (SELECT 1 FROM Confirmed_Appointments c JOIN Services s ON c.Service_ID = s.Service_ID
                WHERE c.Barber_ID = a.Barber_ID 
                AND (c.Time*60 + c.Minute + s.Duration > a.Time*60 + a.Minute)
                AND c.Month = ?
                AND c.Day = ?
                AND c.Year = ?)
          AND NOT EXISTS (SELECT 1 FROM Appointment_Availability b
                WHERE a.Barber_ID = b.Barber_ID
                AND a.Time = b.Time
                AND a.Minute = b.Minute
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

$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
}

// Bind parameters
if ($barberID !== null && $time !== null) {
    $stmt->bind_param("iiiiiiiiiiiss", $weekday, $month, $day, $year, $weekday, $month, $day, $year, $month, $day, $year, $barberID, $time);
} else if ($barberID !== null) {
    $stmt->bind_param("iiiiiiiiiiis", $weekday, $month, $day, $year, $weekday, $month, $day, $year, $month, $day, $year, $barberID);
} else if ($time !== null) {
    $stmt->bind_param("iiiiiiiiiiis", $weekday, $month, $day, $year, $weekday, $month, $day, $year, $month, $day, $year, $time);
} else {
    $stmt->bind_param("iiiiiiiiiii", $weekday, $month, $day, $year, $weekday, $month, $day, $year, $month, $day, $year);
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
$conn->close();
?>
