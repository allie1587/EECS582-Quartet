<?php
/*
    confirm.php
    Sends confirmed appointment information with corresponding client and appointment details to the database.
    Authors: Ben Renner, Jose Leyba, Brinley Hull, Kyle Moore, Alexandra Stratton
    Other sources of code: ChatGPT
    Creation date: 3/1/2025
    Revisions:
        3/2/2025 - Brinley, commenting
    Preconditions:
        Acceptable inputs: 
            A form with method "post" that sends variable strings for variables fname, lname, email, and phone
            Previously set session information for day, month, year, time, and appointment
            Appointment session info is in the form of an array with a BarberID element
            All variables are in string form
        Unacceptable inputs:
            Null values
    Postconditions:
        None
    Error/exceptions:
        Connection failed -> database could not connect.
        SQL prepare failed -> the SQL query was not valid and could not be prepared.
    Side effects:
        Adds a row to the confirmed_appointments table in the database.
    Invariants: 
        None
    Known faults:
        None
*/

// start session to be able to get session information
session_start();

// connect to the database
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) { // catch database connection failure error
    die("Connection failed: " . $mysqli->connect_error);
}

// set corresponding variables from the form post from the confirm appointment page and from the previously-set session variables from schedule.php
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$day = $_SESSION["day"];
$month = $_SESSION["month"];
$year = $_SESSION["year"];
$time = $_SESSION["time"];
$barber = $_SESSION['appointment']["BarberID"];

// prepare a query to insert a row into the confirmed appointments table in the database with the corresponding info
$query = "INSERT INTO Confirmed_Appointments 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// prepare the query 
$stmt = $mysqli->prepare($query);
if (!$stmt) { // if the query is not valid, throw error
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters to put them into the SQL query
$stmt->bind_param("sssssssss", $barber, $month, $day, $year, $time, $fname, $lname, $email, $phone);
$stmt->execute(); // execute the SQL query

// close the connections
$stmt->close();
$mysqli->close();

// redirect to the home page
header("Location: index.php");

?>
