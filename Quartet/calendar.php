<!-- 
    calendar.php
    A page for the barber to view their calendar of scheduled appointments
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons
    Creation date: 3/2/2025
-->

<?php
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barber Calendar</title>
    <link rel="stylesheet" href="styles/style.css">
  </head>
  <body>
    <p>See upcoming schedule</p>
    <div class="menu">
    <button onclick="location.href='dashboard.php'">Dashboard</button>
    <button onclick="location.href='checkouts.php'">Checkouts</button>
    <button onclick="location.href='calendar.php'">Calendar</button>
    <button onclick="location.href='clients.php'">Clients</button>
    <button onclick="location.href='customize.php'">Customize</button>
    <button onclick="location.href='see_feedback.php'">Feedback</button>

    </div>
    <button onclick="location.href='index.php'">Back to Customer Site</button>
    <form method="post" action="logout.php">
    <button type="submit" name="logout">Logout</button>
    </form>
  </body>
</html>