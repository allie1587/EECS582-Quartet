<!-- 
    dashboard.php
    A page for the barber to have an overview for the day which includes revenue and schedule
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons
        4/23/2025 - Brinley, refactoring
    Creation date: 3/2/2025
-->

<?php
session_start();
require 'db_connection.php';
require 'login_check.php';
require 'role_check.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle checkout action
if (isset($_POST['checkout'])) {
    $client_id = $_POST['client_id'];
    $appointment_id = $_POST['appointment_id'];
    
    $stmt = $conn->prepare("INSERT INTO Checkout_History (Client_ID, Appointment_ID) VALUES (?, ?)");
    $stmt->bind_param("is", $client_id, $appointment_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM Confirmed_Appointments WHERE Appointment_ID = ?");
    $stmt->bind_param("s", $appointment_id);
    $stmt->execute();
    $stmt->close();
    
    // Refresh the page to reflect changes
    header("Location: dashboard.php");
    exit();
}
// Get today's appointments
$today_day = date('j'); // Current day of the month
$today_month = date('n') - 1; // Current month as a number
$today_year = date('Y'); // Current year

$sql = "SELECT Confirmed_Appointments.*, Client.First_Name, Client.Last_Name, Client.Email, Client.Phone 
          FROM Confirmed_Appointments
          JOIN Client ON Confirmed_Appointments.Client_ID = Client.Client_ID
          WHERE Confirmed_Appointments.Barber_ID = ? 
          AND Confirmed_Appointments.Day = ? 
          AND Confirmed_Appointments.Month = ? 
          AND Confirmed_Appointments.Year = ?
          ORDER BY Confirmed_Appointments.Time ASC, Confirmed_Appointments.Minute ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siii", $barber_id, $today_day, $today_month, $today_year);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="style/barber_style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barber Dashboard</title>
  </head>
  <body>
    <div class="content-wrapper">
        <h1>Today's Schedule (<?php echo date(' F j, Y'); ?>)</h1>
        <?php if (empty($appointments)): ?>
            <div class="no-appointments">
                <p>No appointments scheduled for today.</p>
            </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Time</th>
                            <th>Service</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): 
                            $time = $appt['Time'] . ':' . str_pad($appt['Minute'], 2, '0', STR_PAD_LEFT);
                            ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($appt['First_Name'] . ' ' . $appt['Last_Name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($appt['Phone']); ?><br>
                                    <?php echo htmlspecialchars($appt['Email']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($time); ?></td>
                                <td><?php echo htmlspecialchars($appt['Service_ID']); ?></td>
                                <td>
                                    <form method="post" action="dashboard.php">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appt['Appointment_ID']); ?>">
                                        <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($appt['Client_ID']); ?>">
                                        <button type="submit" name="checkout">Checkout</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
    </div>
  </body>
</html>
