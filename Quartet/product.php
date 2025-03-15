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
        .add-to-cart-btn {
            background: #28a745;
            color: white;
        }
        .add-to-cart-btn:hover {
            background: #218838;
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
                    <th>Add to Cart</th> <!-- Temporary -->
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
                            <a href="edit_product.php?product_id=<?php echo $product['id']; ?>">
                                <button class="btn edit-btn">Edit</button>
                            </a>
                        </td>
                        <td>
                            <a href="remove_product.php?product_id=<?php echo $product['id']; ?>"><button class="btn delete-btn">Delete</button></a>
                        </td>
                        <td>
                            <form action="add_item.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn add-to-cart-btn">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>