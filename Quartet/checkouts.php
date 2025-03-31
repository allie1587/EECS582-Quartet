<!-- 
    checkouts.php
    A page for the barber to view their recent checkouts for the day
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons
    Creation date: 3/2/2025
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ("db_connection.php");

$query = "
    SELECT first_name, last_name, month, day, time, checkout_time
    FROM Checkout_History
    ORDER BY checkout_time DESC;
";
$result = $conn->query($query);
$checkouts = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barber Checkouts</title>
    <link rel="stylesheet" href="style1.css">
  </head>
  
  <body>
    <p>See history of checkouts here</p>
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

    <h1>Checkout History</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Month</th>
                <th>Day</th>
                <th>Time</th>
                <th>Checkout Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checkouts as $checkout): ?>
                <tr>
                    <td><?php echo htmlspecialchars($checkout['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($checkout['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($checkout['month']); ?></td>
                    <td><?php echo htmlspecialchars($checkout['day']); ?></td>
                    <td><?php echo htmlspecialchars($checkout['time']); ?></td>
                    <td><?php echo htmlspecialchars($checkout['checkout_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </body>
</html>