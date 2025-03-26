<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]));
}
$appointmentID = $_POST['appointmentID'];
$query = "SELECT * FROM Confirmed_Appointments WHERE AppointmentID=?";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Check if appointment ID is provided
if (!$appointmentID) {
    echo json_encode(["success" => false, "message" => "No appointment ID provided."]);
    exit();
}

// Bind parameters and save them
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["error" => "No appointment found for this id."]));
}

// Prepare SQL to fetch appointment details
$sql = "SELECT service, date, time, barber FROM appointments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointmentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $appointment = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "service" => $appointment['service'],
        "date" => $appointment['date'],
        "time" => $appointment['time'],
        "barber" => $appointment['barber']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Appointment not found."]);
}

$stmt->close();
$conn->close();
?>
