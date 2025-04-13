<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 04/13/2025
Revisions:
    04/13/2025 -- Alexandra Stratton -- created appointments.php
 Purpose: Allows everyone to see all the appointments
-->
<?php
//Connects to the database
session_start();
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$barber_id = $_SESSION['username'];
$sql = "SELECT Role FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT Confirmed_Appointments.*, Client.First_Name, Client.Last_Name, Client.Email, Client.Phone 
          FROM Confirmed_Appointments
          JOIN Client ON Confirmed_Appointments.Client_ID = Client.Client_ID
          WHERE Confirmed_Appointments.Barber_ID = ? 
          ORDER BY Confirmed_Appointments.Time ASC, Confirmed_Appointments.Minute ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php
if ($user['Role'] == "Barber") {
    include("barber_header.php");
} else {
    include("manager_header.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title for Page -->
    <title>Appointment List</title>
    <!-- Internal CSS for styling the page -->
    <link rel="stylesheet" href="style/barber_style.css">
</head>

<body>
    <div class="content-wrapper">
    <br><br>
        <h1>Appointment List</h1>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search by appointment...">
        </div>
        <div class="container">
            <div class="card">
            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    <p>No appointments scheduled for today.</p>
                </div>
                <?php else: ?>
                    <table  id="appointmentTable">
                        <thead>
                            <tr>
                                <th>Appointment ID</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Time</th>
                                <th>Service</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appt): 
                                $time = $appt['Time'] . ':' . str_pad($appt['Minute'], 2, '0', STR_PAD_LEFT);
                                ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($appt['Appointment_ID']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($appt['First_Name'] . ' ' . $appt['Last_Name']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($appt['Phone']); ?><br>
                                        <?php echo htmlspecialchars($appt['Email']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($time); ?></td>
                                    <td><?php echo htmlspecialchars($appt['Service_ID']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <script>
            // JavaScript for filtering the table - fixed version
            document.getElementById("search-input").addEventListener("input", function() {
                const filter = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll("#appointmentTable tbody tr");

                rows.forEach(row => {
                    let match = false;
                    const cells = row.querySelectorAll("td");

                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(filter)) {
                            match = true;
                        }
                    });

                    row.style.display = match ? "" : "none";
                });
            });
        </script>
    </div>
</body>

</html>