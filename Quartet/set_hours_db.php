<?php
/*
    set_hours_db.php
    Sends specific week barber hour information to the database.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/29/2025 - Brinley, add week to database.
        4/2/2025 - Brinley, refactoring
    Other sources of code: ChatGPT
    Creation date: 3/14/2025
    Preconditions:
        Acceptable inputs: 
        Unacceptable inputs:
            Null values
    Postconditions:
        None
    Error/exceptions:
    Side effects:
    Invariants: 
        None
    Known faults:
        None
*/

// start session to be able to get session information
session_start();

// connect to the database
require 'db_connection.php';

$daysOfWeek = range(0, 6); // initialize days of the week list
$times = range(8, 17); // make range of valid times
$barber = $_POST["barber"];
$week = $_SESSION["week"];

// Check whether barber exists
$query = "SELECT COUNT(*) AS count FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $barber);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    die("Invalid barber value: " . htmlspecialchars($barber));
}

$stmt->close();

$month = (int)$_SESSION["month"] - 1;
$startDate = $_SESSION["startDate"];
$year = $_SESSION["year"];
foreach ($times as $hour) { // create each row of times and checkboxees
    foreach ($daysOfWeek as $day) { //create variables for every day and time combo
        $varName = $day . '-' . $hour;
        $$varName = isset($_POST[$varName]) ? $_POST[$varName] : "unchecked"; // Dynamically create the variable
        
        // delete any previous rows to avoid duplicates
        $query = "DELETE FROM Appointment_Availability WHERE Barber_ID=? AND Time = ? AND Month = ? AND Day = ? AND Year = ?";

        // prepare the query 
        $stmt = $conn->prepare($query);
        if (!$stmt) { // if the query is not valid, throw error
        die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
        }

        $date = $day + (int)$startDate;
        $available = $$varName != "unchecked" ? "Y" : "N";

        // Bind parameters to put them into the SQL query
        $stmt->bind_param("sssss", $barber, $hour, $month, $date, $year);
        $stmt->execute(); // execute the SQL query

        // prepare a query to insert a row into the confirmed appointments table in the database with the corresponding info
        $query = "INSERT INTO Appointment_Availability 
        VALUES (?, -1, ?, -1, ?, ?, ?, ?, ?)";

        // prepare the query 
        $stmt = $conn->prepare($query);
        if (!$stmt) { // if the query is not valid, throw error
        die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
        }

        // Bind parameters to put them into the SQL query
        $stmt->bind_param("sssssss", $barber, $hour, $month, $date, $year, $week, $available);
        $stmt->execute(); // execute the SQL query

        $stmt->close();
        
    }
}

// close the connections
$conn->close();

// redirect to the home page
header("Location: set_hours.php");

?>
