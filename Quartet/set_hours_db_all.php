<?php
/*
    set_hours_db_all.php
    Sends reoccurring barber hour information to the database.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
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
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) { // catch database connection failure error
    die("Connection failed: " . $mysqli->connect_error);
}

$daysOfWeek = range(0, 6); // initialize days of the week list
$times = range(8, 17); // make range of valid times
$barber = $_POST["barber"];

$query = "SELECT COUNT(*) AS count FROM Barber_Information WHERE Username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $barber);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    die("Invalid barber value: " . htmlspecialchars($barber));
}

$stmt->close();

foreach ($times as $hour) { // create each row of times and checkboxees
    foreach ($daysOfWeek as $day) { //create variables for every day and time combo
        $varName = $day . $hour;
        $$varName = isset($_POST[$varName]) ? $_POST[$varName] : "unchecked"; // Dynamically create the variable
        
        // delete any previous rows to avoid duplicates
        $query = "DELETE FROM Appointment_Availability WHERE BarberID=? AND Weekday = ? AND Time = ?";

        // prepare the query 
        $stmt = $mysqli->prepare($query);
        if (!$stmt) { // if the query is not valid, throw error
        die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
        }

        $available = $$varName != "unchecked" ? "Y" : "N";

        // Bind parameters to put them into the SQL query
        $stmt->bind_param("sss", $barber, $day, $time);
        $stmt->execute(); // execute the SQL query

        // prepare a query to insert a row into the confirmed appointments table in the database with the corresponding info
        $query = "INSERT INTO Appointment_Availability 
        VALUES (?, ?, ?, -1, -1, -1, -1, ?)";

        // prepare the query 
        $stmt = $mysqli->prepare($query);
        if (!$stmt) { // if the query is not valid, throw error
        die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
        }

        // Bind parameters to put them into the SQL query
        $stmt->bind_param("ssss", $barber, $day, $hour, $available);
        $stmt->execute(); // execute the SQL query

        $stmt->close();
        
    }
}

// close the connections
$mysqli->close();

// redirect to the home page
header("Location: set_hours.php");

?>
