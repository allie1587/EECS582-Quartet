<?php
/*
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 3/2/2025
    Revisions:
Purpose: Cancel Appointments in the SQL 
*/
session_start();

$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


$email = $_POST['email'];

// Selects all the appointments under that email to check if there is any
$query = "SELECT * FROM Confirmed_Appointments WHERE Email = ?";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters and save them
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["error" => "No appointment found for this email."]));
}
// Deletes the appointments from that email
$deleteQuery = "DELETE FROM Confirmed_Appointments WHERE Email = ?";
$deleteStmt = $mysqli->prepare($deleteQuery);
if (!$deleteStmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

//Let's you know if it worked or not
$deleteStmt->bind_param("s", $email);
if ($deleteStmt->execute()) {
    echo json_encode(["success" => "Appointment canceled successfully."]);
} else {
    echo json_encode(["error" => "Failed to cancel appointment: " . $deleteStmt->error]);
}



$stmt->close();
$deleteStmt->close();
$mysqli->close();
// Sends you back to home once done
header("Location: index.php");

?>
