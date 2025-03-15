<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
Purpose: Allow barbers to remove a product from the store

-->
<?php
require 'db_connection.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);

    if ($stmt->execute()) {
        header('Location: product.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>