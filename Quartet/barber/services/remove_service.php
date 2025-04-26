<!--
remove_service.php
Allow barbers to remove a service from the database
Authors: Alexandra Stratton, Jose Leyba, Brinley Hull, Ben Renner, Kyle Moore
Creation date: 4/10/2025
Revisions:
    4/26/2025 - Brinley, actually delete the thing
-->
<?php
//Connects to the database
session_start();
require 'db_connection.php';
require 'login_check.php';

if (isset($_GET['Service_ID'])) {
    //Gets the service_id
    $service_id = $_GET['Service_ID'];
    // Delete this product from everyone's shopping cart
    $sql_delete_cart = "DELETE FROM Services WHERE Service_ID = ?";
    $stmt_delete_cart = $conn->prepare($sql_delete_cart);
    $stmt_delete_cart->bind_param("i", $service_id);
    $stmt_delete_cart->execute();
    header('Location: services_manager.php?barber=' . $_SESSION['username']);
    exit();
}
?>