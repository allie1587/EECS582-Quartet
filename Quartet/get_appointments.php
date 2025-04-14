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
        4/12/2025 - Brinley, fix get appointment logic
        4/14/2025 - Brinley, updated filtering
*/
session_start(); //start the session

require 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response

// Validate input
$year = isset($_GET['year']) ? (int)$_GET['year'] : -1;
$month = isset($_GET['month']) ? (int)$_GET['month'] : -1;
$day = isset($_GET['day']) ? (int)$_GET['day'] : -1;
$weekday = isset($_GET['weekday']) ? $_GET['weekday'] : date('w', strtotime("$year-$month-$day"));
$barberID = $_SESSION['barberFilter'];
$service = $_SESSION['serviceFilter'];

$validBarbers = [];
if ($service !== "None") {
    $stmt = $conn->prepare("SELECT Barber_ID FROM Barber_Services WHERE Service_ID = ?");
    $stmt->bind_param("i", $service);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $validBarbers[] = $row['Barber_ID'];
    }
    $stmt->close();
}

// Prepare SQL query
$query = "SELECT * FROM Appointment_Availability a 
          WHERE Available='Y' 
          AND ((Weekday=? AND NOT EXISTS (SELECT 1 FROM Appointment_Availability b
                WHERE a.Barber_ID = b.Barber_ID
                AND a.Time = b.Time
                AND a.Minute = b.Minute
                AND b.Month = ?
                AND b.Day = ?
                AND b.Year = ?
                AND b.Available = 'N')) OR (Month=? AND Day=? AND Year=? 
                AND NOT EXISTS (SELECT 1 FROM Appointment_Availability d WHERE 
                                d.Barber_ID = a.Barber_ID
                                AND d.Time = a.Time 
                                AND d.Minute = a.Minute
                                AND d.Weekday = ?
                                AND d.Available = 'Y')))
          AND NOT EXISTS (SELECT 1 FROM Confirmed_Appointments c JOIN Services s ON c.Service_ID = s.Service_ID
                WHERE c.Barber_ID = a.Barber_ID 
                AND (c.Time*60 + c.Minute + s.Duration > a.Time*60 + a.Minute AND c.Time*60 + c.Minute <= a.Time*60 + a.Minute)
                AND c.Month = ?
                AND c.Day = ?
                AND c.Year = ?)"; // AND NOT EXISTS statement removes confirmed or nonavailble appointments from list

// Filter by one barber
$params = [$weekday, $month, $day, $year, $month, $day, $year, $weekday, $month, $day, $year];
$paramTypes = "iiiiiiiiiii";

$filterByBarber = $barberID !== "None";
$filterByService = $service !== "None";

// CASE 1: Filter by a specific barber only
if ($filterByBarber && !$filterByService) {
    $query .= " AND a.Barber_ID = ?";
    $params[] = $barberID;
    $paramTypes .= "s";

// CASE 2: Filter by barbers who offer the selected service
} elseif (!$filterByBarber && $filterByService) {
    if (empty($validBarbers)) {
        $query .= " AND 1=0";
    } else {
        $placeholders = implode(',', array_fill(0, count($validBarbers), '?'));
        $query .= " AND a.Barber_ID IN ($placeholders)";
        foreach ($validBarbers as $id) {
            $params[] = $id;
            $paramTypes .= "s";
        }
    }

// CASE 3: Filter by both barber and service (make sure this barber offers the service)
} elseif ($filterByBarber && $filterByService) {
    if (in_array($barberID, $validBarbers) && !empty($validBarbers)) {
        $query .= " AND a.Barber_ID = ?";
        $params[] = $barberID;
        $paramTypes .= "s";
    } else {
        // Barber doesn't offer this service, so make query return nothing
        $query .= " AND 1=0";
    }
}

$query .= " ORDER BY Time, Minute";

// Prepare and bind
$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
}

// Bind dynamically
$stmt->bind_param($paramTypes, ...$params);

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
