<!--
remove_service.php
Allow barbers to remove a service from the database
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
    $sql_delete_cart = "DELETE FROM Services WHERE Service_ID = ?";
    $stmt_delete_cart = $conn->prepare($sql_delete_cart);
    $stmt_delete_cart->bind_param("i", $service_id);
    header('Location: services.php');
    exit();
}
?>