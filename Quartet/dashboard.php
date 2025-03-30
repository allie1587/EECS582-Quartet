<!-- 
    dashboard.php
    A page for the barber to have an overview for the day which includes revenue and schedule
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons
    Creation date: 3/2/2025
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ("db_connection.php");

// Handle checkout action
if (isset($_POST['checkout'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $time = $_POST['time'];

    // Insert into Checkout_History
    $stmt = $conn->prepare("INSERT INTO Checkout_History (first_name, last_name, month, day, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $first_name, $last_name, $month, $day, $time);
    $stmt->execute();
    $stmt->close();

    // Delete from Confirmed_Appointments
    $stmt = $conn->prepare("DELETE FROM Confirmed_Appointments WHERE First_name = ? AND Last_name = ? AND Month = ? AND Day = ? AND Time = ?");
    $stmt->bind_param("sssis", $first_name, $last_name, $month, $day, $time);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to reflect changes
    header("Location: dashboard.php");
    exit();
}

$query = "
    SELECT First_name, Last_name, Month, Day, Time
    FROM Confirmed_Appointments
    ORDER BY Day ASC;
";
$result = $conn->query($query);
$clients = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barber Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
  </head>
  <body>
    <p>barber dashboard</p>
    <div class="menu">
    <button onclick="location.href='dashboard.php'">Dashboard</button>
    <button onclick="location.href='checkouts.php'">Checkouts</button>
    <button onclick="location.href='calendar.php'">Calendar</button>
    <button onclick="location.href='clients.php'">Clients</button>
    <button onclick="location.href='customize.php'">Customize</button>
    <button onclick="location.href='testing.html'">TESTING</button>
    </div>
  
    <button onclick="location.href='index.php'">Back to Customer Site</button>
    <form method="post" action="logout.php">
    <button type="submit" name="logout">Logout</button>
    </form>

    <h1>Today's schedule</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Month</th>
                <th>Day</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['First_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['Last_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['Month']); ?></td>
                    <td><?php echo htmlspecialchars($client['Day']); ?></td>
                    <td><?php echo htmlspecialchars($client['Time']); ?></td>
                    <td>
                        <form method="post" action="dashboard.php">
                            <input type="hidden" name="first_name" value="<?php echo $client['First_name']; ?>">
                            <input type="hidden" name="last_name" value="<?php echo $client['Last_name']; ?>">
                            <input type="hidden" name="month" value="<?php echo $client['Month']; ?>">
                            <input type="hidden" name="day" value="<?php echo $client['Day']; ?>">
                            <input type="hidden" name="time" value="<?php echo $client['Time']; ?>">
                            <button type="submit" name="checkout">Checkout</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </body>
</html>