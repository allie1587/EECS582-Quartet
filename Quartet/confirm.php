<?php
// Creation date: 3/1/2025

session_start();

$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$day = $_SESSION["day"];
$month = $_SESSION["month"];
$year = $_SESSION["year"];
$time = $_SESSION["time"];
$barber = $_SESSION['appointment']["BarberID"];

$query = "INSERT INTO Confirmed_Appointments 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters
$stmt->bind_param("sssssssss", $barber, $month, $day, $year, $time, $fname, $lname, $email, $phone);
$stmt->execute();

$stmt->close();
$mysqli->close();

header("Location: index.php");

?>
