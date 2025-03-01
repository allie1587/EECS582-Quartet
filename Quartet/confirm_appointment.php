<?php
// Authors: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
// Creation Date: 03/01/2025
// Revisions: 
// Purpose: A page where clients can enter their information and confirm their appointments (sending info back to database).

// Start the session to remember user info
session_start();
if (!isset($_SESSION['appointment'])) {
    echo "Session variable 'appointment' is not set!";
} else {
    echo "Appointment session: " . $_SESSION['appointment'];
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
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        body { /*Centers text and sets the font */
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .menu { /* Adds spacing for the menu*/
            margin-top: 20px;
        }
        .menu button { /*Styles the menu buttons */
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .top-bar { /*Creates a top navigation bar with a green background, white text, and flexible layout */
            background-color: green;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 50px;
        }
        .top-bar h1 { /*Styles the header text in the top bar */
            margin: 0;
            padding-left: 20px;
            font-size: 24px;
        }
        .login-container { /*Aligns login button and text */
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
        .login-button { /*Styles a circular login button */
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

    </style>
    <script>
        function sendData() { //Sends input data to a PHP backend using
            let inputData = document.getElementById("dbInput").value;
            fetch("server.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ data: inputData })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("dbOutput").innerText = data.message;
            });
        }
    </script>
</head>
<body>
    <!--The green Bar at the top that has the name and button that takes you to the login page-->
    <div class="top-bar">
        <h1>Quartet's Amazing Barbershop</h1>
        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--let's user know the current page they are on-->
    <h1>Confirm Appointment</h1>
    <!--Menu with all possible pages-->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='page4.html'">Page 4</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>

    <div class="appointment_info">
        <p id="appointment_info"></p>
        </div>
    
   <div class="info_form">
        <form action="confirm.php" method="POST">
            <label for="fname">First name:</label><br>
            <input type="text" id="fname" name="fname" required><br><br>
            <label for="lname">Last name:</label><br>
            <input type="text" id="lname" name="lname" required><br><br>
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" required><br><br>
            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" required><br><br>
            
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
    <script>
        // let urlParams = new URLSearchParams(window.location.search);
        // let time = urlParams.get('time');
        // let date = urlParams.get('date');
        // let barber = urlParams.get('barber');
        // document.getElementById("appointment_date").value = date;
        // document.getElementById("appointment_time").value = (time <= 12 ? (time == 12 ? time + "PM" : time + "AM") : time-12 + "PM");
        // document.getElementById("appointment_barber").value = barber;
        
    </script>
</html>
