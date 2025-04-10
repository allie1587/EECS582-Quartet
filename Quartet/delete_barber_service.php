<!--
delete_barber_service.php
Allow barbers to remove a service from their personal offered services
Authors: Alexandra Stratton, Jose Leyba, Brinley Hull, Ben Renner, Kyle Moore
Creation date: 4/10/2025
Revisions:
-->
<?php
//Connects to the database
require 'db_connection.php';
session_start();

if (isset($_GET['Service_ID'])) {
    //Gets the service_id
    $service_id = $_GET['Service_ID'];
    // Delete this product from everyone's shopping cart
    $sql_delete_cart = "DELETE FROM Barber_Services WHERE Barber_ID = ? AND Service_ID = ?";
    $stmt_delete_cart = $conn->prepare($sql_delete_cart);
    $stmt_delete_cart->bind_param("si", $_SESSION['username'], $service_id);
    header('Location: services.php');
    exit();
}
?>