<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/15/2025  -- Alexandra Stratton  -- Commenting and fixing format
Purpose: Allow barbers to remove a product from the store

-->
<?php
//Connects to the database
require 'db_connection.php';

if (isset($_GET['product_id'])) {
    //Gets the product_id
    $product_id = $_GET['product_id'];
    // Prepares the SQL to delete the product with that id from the database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);
    // Execute the statement and check if the insertion was successful
    if ($stmt->execute()) {
        header('Location: product.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>