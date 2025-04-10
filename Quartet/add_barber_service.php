<!--
add_barber_service.php
Purpose: Allow barbers to add services to their personal list
Authors: Alexandra Stratton, Jose Leyba, Brinley Hull, Ben Renner, Kyle Moore
Date: 4/10/2025
Revisions:
Other Sources: ChatGPT
-->
<?php
// Connects to the database
require 'db_connection.php';
session_start();
$service_id = $_GET['Service_ID'];
// Prepares the SQL for inserting the new service into the database
$sql = "INSERT INTO Barber_Services (Barber_ID, Service_ID)
        SELECT ?, ?
        WHERE NOT EXISTS (
            SELECT 1 FROM Barber_Services WHERE Barber_ID = ? AND Service_ID = ?
        )";
$stmt = $conn->prepare($sql);
// Execute the statement and check if the insertion was successful
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$stmt->bind_param("sisi", $_SESSION['username'], $service_id, $_SESSION['username'], $service_id);
// Execute the statement and check if the insertion was successful
if ($stmt->execute()) {
    echo "Service added successfully!";
    // Redirect to the service page after inserting infor into database
    header('Location: services.php');
    exit();
} else {
    // Display an error message if execution fails
    echo "Error executing statement: " . $stmt->error;
}
?>