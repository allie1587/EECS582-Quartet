<?php
// Connect to the database
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $mysqli->connect_error]));
}

// Get the appointment ID from the request
if (!isset($_POST['appointmentID']) || empty($_POST['appointmentID'])) {
    die(json_encode(["error" => "No appointment ID provided."]));
}

$appointmentID = intval($_POST['appointmentID']); // Ensure it's an integer

// Fetch appointment details
$query = "SELECT Month, Day, Year, Time, BarberID FROM Confirmed_Appointments WHERE AppointmentID = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}
//Bind values
$stmt->bind_param("i", $appointmentID);
$stmt->execute();
$stmt->bind_result($month, $day, $year, $time, $barberID);

//Display 
if ($stmt->fetch()) { //if appointment is found
    echo "<p>📅 <b>Date:</b> $month/$day/$year</p>";
    echo "<p>⏰ <b>Time:</b> $time</p>";
    echo "<p>💈 <b>Barber ID:</b> $barberID</p>";
    echo "<script>document.getElementById('hiddenAppointmentID').value = $appointmentID;</script>";
} else { //no appointment found
    echo "<p style='color: red;'>❌ No appointment found.</p>";
}

$stmt->close();
$mysqli->close();
?>
