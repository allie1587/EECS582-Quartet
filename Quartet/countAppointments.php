<!-- 
    A program to connect to the database and return the count of the appointments for a certain date.
    Creation date: 2/27/2025
-->

<?php
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$weekday = $_GET['weekday'];

$query = "SELECT COUNT(*) AS count FROM Appointment_Availability 
          WHERE Available='Y' 
          AND (Weekday=? OR (Month=? AND Day=? AND Year=?))";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("siii", $weekday, $month, $day, $year);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["count" => $row['count']]);

$stmt->close();
$mysqli->close();
?>
