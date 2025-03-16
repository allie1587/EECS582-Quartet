<?php
/*
    confirm.php
    Sends confirmed appointment information with corresponding client and appointment details to the database.
    Authors: Ben Renner, Jose Leyba, Brinley Hull, Kyle Moore, Alexandra Stratton
    Other sources of code: ChatGPT
    Creation date: 3/1/2025
    Revisions:
        3/2/2025 - Brinley, commenting
        3/3/2025 - Added unique 7-digit integer AppointmentID
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

// Start session to be able to get session information
session_start();

// Connect to the database
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) { // Catch database connection failure error
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to generate a unique 7-digit integer
function generateUniqueAppointmentID($mysqli) {
    do {
        // Generate a random 7-digit number
        $appointmentID = mt_rand(1000000, 9999999); // mt_rand is faster and more random than rand()
        
        // Check if the ID already exists in the database
        $query = "SELECT AppointmentID FROM Confirmed_Appointments WHERE AppointmentID = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $appointmentID);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0; // If rows are found, the ID already exists
        $stmt->close();
    } while ($exists); // Repeat until a unique ID is generated

    return $appointmentID;
}

// Set corresponding variables from the form post from the confirm appointment page and from the previously-set session variables from schedule.php
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$day = $_SESSION["day"];
$month = $_SESSION["month"];
$year = $_SESSION["year"];
$time = $_SESSION["time"];
$barber = $_SESSION['appointment']["BarberID"];

// Generate a unique 7-digit appointment ID
$appointmentID = generateUniqueAppointmentID($mysqli);

// Prepare a query to insert a row into the confirmed appointments table in the database with the corresponding info
$query = "INSERT INTO Confirmed_Appointments (BarberID, Month, Day, Year, Time, First_name, Last_name, Email, Phone, AppointmentID)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the query 
$stmt = $mysqli->prepare($query);
if (!$stmt) { // If the query is not valid, throw error
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters to put them into the SQL query
$stmt->bind_param("sssssssssi", $barber, $month, $day, $year, $time, $fname, $lname, $email, $phone, $appointmentID);
$stmt->execute(); // Execute the SQL query

// Close the connections
$stmt->close();
$mysqli->close();

// Redirect to the home page
header("Location: index.php");
exit(); // Ensure no further code is executed after the redirect
?>