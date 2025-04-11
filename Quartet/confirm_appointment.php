<?php
/*
    confirm_appointment.php
    A page where clients can enter their information and confirm their appointments.
    Authors: Ben Renner, Jose Leyba, Brinley Hull, Kyle Moore, Alexandra Stratton
    Other sources of code: ChatGPT
    Creation date: 3/1/2025
    Revisions:
        3/2/2025 - Brinley, commenting and deleting unnecessary code
        4/2/2025 - Brinley, refactoring
        4/10/2025 - Brinley, add services
    Preconditions:
        Acceptable inputs: 
            Previously set session information for month, day, year, time, and appointment
            Appointment session info is in the form of an array with a BarberID element
            All variables are in string form
        Unacceptable inputs:
            Null values
    Postconditions:
        None
    Error/exceptions:
        None
    Side effects:
        None
    Invariants: 
        None
    Known faults:
        None
*/

// Start the session to remember user info
session_start();
$monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June', 
    'July', 'August', 'September', 'October', 'November', 'December'
];
include('header.php');

require 'db_connection.php';
session_start();


$sql = "SELECT * FROM Barber_Services, Services WHERE Barber_ID = ? AND Barber_Services.Service_ID = Services.Service_ID";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$stmt->bind_param("s", $_SESSION['appointment']["Barber_ID"]);
$stmt->execute();
$result = $stmt->get_result();
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Name of Page-->
    <title>Home Page</title>
    <link rel="stylesheet" href="style1.css">
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* Applies styles to the entire body */
        
        /*Dark Format for Fillable Fields */


        /* Form inputs */
        input[type="text"] {
            width: 30%;
            padding: 10px;
            margin-top: 5px;
            color: black;
            border: 1px solid #666;
            border-radius: 5px;
        }

        /* Submit button */
        button[type="submit"] {
            background-color: #c4454d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Submit button hover */
        button[type="submit"]:hover {
            background-color:rgb(143, 48, 55);
        }

    </style>
</head>
<body>
    <!--let's user know the current page they are on-->
    <h1>Confirm Appointment</h1>
    
    <!-- Form for user to put in their information  -->
   <div class="info_form">
        <!-- Set form action to post and redirect to confirm.php on submit -->
        <form action="confirm.php" method="POST">

            <!-- User information (required)-->
            <label for="fname">First name:</label><br>
            <input type="text" id="fname" name="fname" required><br><br>
            <label for="lname">Last name:</label><br>
            <input type="text" id="lname" name="lname" required><br><br>
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" required><br><br>
            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" required><br><br>
            
            <!-- Appointment information (readonly) that uses the session variables-->
            <label for="appointment_date">Date:</label><br>
            <input type="text" id="appointment_date" name="appointment_date" value="<?php echo $monthNames[$_SESSION['month']]?> <?php echo $_SESSION['day']?>, <?php echo $_SESSION['year']?>" readonly><br><br>
            
            <label for="appointment_time">Time:</label><br>
            <input type="text" id="appointment_time" name="appointment_time" value="<?php echo $_SESSION['time'] . ":" . $_SESSION['minute']?>" readonly><br><br>
            
            <label for="appointment_barber">Barber:</label><br>
            <input type="text" id="appointment_barber" name="appointment_barber" value="<?php echo $_SESSION['appointment']["Barber_ID"]?>" readonly><br><br>
            
            <label for="service">Select Service:</label>
            <select id="service" name="service">
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service['Service_ID']?>"><?php echo $service['Name']?></option>
                <?php endforeach; ?>
            </select>

            <br>
            <br>
            <button type="submit">Confirm Appointment</button>
        </form>
   </div>
</body>
</html>
