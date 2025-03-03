<?php
/*
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 3/2/2025
    Revisions:
Purpose: Cancel Appointments in the SQL, Shows User Afterwards
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
    echo json_encode(["success" => "Canceled!"]);
} else {
    echo json_encode(["error" => "Failed to cancel appointment: " . $deleteStmt->error]);
}



$stmt->close();
$deleteStmt->close();
$mysqli->close();
// Sends you back to home once done
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
    <h1>Cancellation Status</h1>
    <p>Canceled Correctly! Redirecting to Home Page...</p>

</body>
</html>
