<?php
/*
    confirm_appointment.php
    A page where clients can enter their information and confirm their appointments.
    Authors: Ben Renner, Jose Leyba, Brinley Hull, Kyle Moore, Alexandra Stratton
    Other sources of code: ChatGPT
    Creation date: 3/1/2025
    Revisions:
        3/2/2025 - Brinley, commenting and deleting unnecessary code
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
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* Applies styles to the entire body */
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color:rgba(50, 50, 50, 0.86); 
            color: white;
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
        
        /*Dark Format for Fillable Fields */
        .info_form {
            background-color: #333; /* Dark background */
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        /* Form inputs */
        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            background-color: #444;
            color: white;
            border: 1px solid #666;
            border-radius: 5px;
        }

        /* Submit button */
        button[type="submit"] {
            background-color: #008000; /* Green */
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
            background-color: #00A000; /* Brighter green */
        }

    </style>
</head>
<body>
    <!--The green Bar at the top that has the name and button that takes you to the login page-->
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
            <input type="text" id="appointment_date" name="appointment_date" value="<?php echo $_SESSION['month']?> <?php echo $_SESSION['day']?>, <?php echo $_SESSION['year']?>" readonly><br><br>
            
            <label for="appointment_time">Time:</label><br>
            <input type="text" id="appointment_time" name="appointment_time" value="<?php echo $_SESSION['time']?>" readonly><br><br>
            
            <label for="appointment_barber">Barber:</label><br>
            <input type="text" id="appointment_barber" name="appointment_barber" value="<?php echo $_SESSION['appointment']["BarberID"]?>" readonly><br><br>
            
            <button type="submit">Confirm Appointment</button>
        </form>
   </div>
</body>
</html>
