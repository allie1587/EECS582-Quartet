<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Revisions:
     03/12/2025 -- Alexandra Stratton -- Created the edit product page
     03/14/2025 -- Alexandra Stratton -- Implemented header.php
     03/15/2025  -- Alexandra Stratton  -- Commenting and fixing format
     03/16/2025 -- Alexandra Stratton -- Got rid of the testing add to cart functionality
     03/16/2025 -- Alexandra Stratton -- Confirmation prior to deleting
 Purpose: Allow barbers to see the products seen in the store

 -->
<?php
// Connects to the database
require 'db_connection.php';


$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title for Page -->
    <title>Product List</title>
    <link rel="stylesheet" href="style1.css">
    <!-- Internal CSS for styling the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .product-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .add-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .add-btn {
            background: #c4454d;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .add-btn:hover {
            background: rgb(143, 48, 55);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #c4454d;
            color: white;
        }
        td {
            color: black;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        img {
            max-width: 80px;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .edit-btn {
            background: #007BFF;
            color: white;
        }
        .edit-btn:hover {
            background: #0056b3;
        }
        .delete-btn {
            background: #FF6A13;
            color: white;
        }
        .delete-btn:hover {
            background: #FF8A3D;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            color: black;
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
            border-radius: 10px;
            color: black;
        }
        .close {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover {
            color: black;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <div class="product-container">
        <!-- Add Product Button at the Top Right -->
        <div class="add-btn-container">
            <a href="add_product.php" class="add-btn">Add Product</a>
        </div>

        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
                        <td>
                            <a href="edit_product.php?product_id=<?php echo $product['id']; ?>"><button class="btn edit-btn">Edit</button></a>
                        </td>
                        <td>
                            <button class="btn delete-btn" onclick="confirmDelete('<?php echo $product['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Delete Confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Are you sure you want to remove this product?</h2>
            <button class="btn delete-btn" id="confirmDeleteBtn">Yes</button>
            <button class="btn" onclick="closeModal()">No</button>
        </div>
    <!-- Script for confirming deletion -->
    <script>
        function confirmDelete(productId) {
            document.getElementById('confirmDeleteBtn').setAttribute('onclick', `window.location.href='remove_product.php?product_id=${productId}'`);
            document.getElementById('deleteModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>