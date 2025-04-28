<?php
/*
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/09/2025
    Revisions:
    03/13/2025 -- Jose Leyba -- Finished implementation, added working ReviewID's method

Purpose: Send reviews submited from the user to the database
    Preconditions:
        Acceptable inputs: 
            A form with method "post" that sends variable strings for variables name, rating, and review
            All variables are in string form
        Unacceptable inputs:
            Null values
    Postconditions:
        None
    Error/exceptions:
        Connection failed -> database could not connect.
        SQL prepare failed -> the SQL query was not valid and could not be prepared.
    Side effects:
        Adds a row to the reviews table in the database.
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

// Get latest ID from the table
$result = $mysqli->query("SELECT MAX(Review_ID) AS Review_ID FROM Reviews");
$row = $result->fetch_assoc();
$new_id = $row['Review_ID'] ? $row['Review_ID'] + 1 : 1;

// set corresponding variables from the form post from the confirm appointment page and from the previously-set session variables from schedule.php
$Name = isset($_POST['Name']) && !empty(trim($_POST['Name'])) ? trim($_POST['Name']) : "Anonymous";
$Rating = $_POST['Rating'];
$Review = $_POST['Review'];


// prepare a query to insert a row into the confirmed appointments table in the database with the corresponding info
$query = "INSERT INTO Reviews 
          VALUES (?, ?, ?, ?)";

// prepare the query 
$stmt = $mysqli->prepare($query);
if (!$stmt) { // if the query is not valid, throw error
    die(json_encode(["error" => "SQL prepare failed: " . $mysqli->error]));
}

// Bind parameters to put them into the SQL query
$stmt->bind_param("ssss", $new_id, $Name, $Rating, $Review);
$stmt->execute(); // execute the SQL query

// close the connections
$stmt->close();
$mysqli->close();

?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <script>
        setTimeout(function() {
            window.location.href = 'index.php'; 
        }, 3000);
    </script>
    </script>
</head>
<body>
    <h2>Review Status</h2>
    <p>Your Review was submitted correctly! Redirecting to Home Page...</p>

</body>
</html>
