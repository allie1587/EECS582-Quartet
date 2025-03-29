<?php
/*  
    retrieve_availability.php
    A program to connect to the database and return the appointments for a barber for a specific week.
    Authors: Brinley Hull
    Creation date: 3/29/2025
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
$week = $_GET['week'];
$barber = isset($_GET['barber']) ?  $_GET['barber'] : -1;

// check whether barber exists
$query = "SELECT COUNT(*) AS count FROM Barber_Information WHERE Username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $barber);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    die("Invalid barber value: " . htmlspecialchars($barber));
}

// Prepare SQL query
$query = "SELECT * FROM Appointment_Availability a 
          WHERE BarberID=? 
          AND Available='Y' 
          AND (Week=? OR (Weekday != -1
          AND NOT EXISTS (SELECT 1 FROM Appointment_Availability b
                WHERE a.barberID = b.barberID
                AND a.Time = b.Time
                AND b.Week = ?
                AND a.Weekday != WEEKDAY(STR_TO_DATE(CONCAT(b.Year, '-', b.Month, '-', b.Day), '%Y-%m-%d'))
                AND b.Available = 'N')))"; 

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters
$stmt->bind_param("sii", $barber, $week, $week, );
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
