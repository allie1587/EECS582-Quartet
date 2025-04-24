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
$result = $mysqli->query("SELECT MAX(ReviewID) AS ReviewID FROM Reviews");
$row = $result->fetch_assoc();
$new_id = $row['ReviewID'] ? $row['ReviewID'] + 1 : 1;

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Applies styles to the entire body */
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color:rgba(59, 65, 59, 0.29); 
        }
        /* Top Bar at Top with Pages and Login */
        .top-bar {
            background-color: #006400; 
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 70px; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        /* Size of Letters on it's header */
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
            font-size: 28px;
        }
        /* Space for the login button on the right */
        .login-container {
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
        /* Login Button Format*/
        .login-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Style for the Menu taht will have the navigation buttons */
        .menu {
            display: flex;
            flex-grow: 1;
            justify-content: center;
            height: 100%;
        }
        /* Style of Navigation Buttons */
        .menu button {
            background-color: #006400; 
            color: white;
            border: none;
            padding: 20px 25px; 
            font-size: 18px;
            cursor: pointer;
            flex-grow: 1;
            text-align: center;
            font-family: 'Georgia', serif; 
        }
        /* Color gets darker when hovering the buttons */
        .menu button:hover {
            background-color: #004d00; 
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'index.php'; 
        }, 3000);
    </script>
    </script>
</head>
<body>
<div class="top-bar">
        <h1>Quartet's Barbershop</h1>
        <div class="menu">
            <button onclick="location.href='index.php'">Home</button>
            <button onclick="location.href='schedule.php'">Schedule</button>
            <button onclick="location.href='store.php'">Store</button>
            <button onclick="location.href='barbers.php'">Barbers</button>
            <button onclick="location.href='about.php'">About Us</button>
            <button onclick="location.href='feedback.php'">Contact us</button>

        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
</div>
    <h1>Review Status</h1>
    <p>Your Review was submitted correctly! Redirecting to Home Page...</p>

</body>
</html>
