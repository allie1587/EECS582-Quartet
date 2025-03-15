<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
Purpose: Allow barbers to see the products seen in the store

-->
<?php
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
<?php
include('header.php');
?>
<head>
    <style>
         
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            color: black;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .add-btn {
            width: 200px;

            background: #c4454d;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .add-btn:hover {
            background: rgb(143, 48, 55);
        }
        .edit-btn {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .edit-btn:hover {
            background: #0056b3;
        }.delete-btn {
            background: #FF6A13;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .delet-btn:hover {
            background: #FF8A3D;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <br><br>
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
                        <div class="edit-btn">
                            <a href="edit_product.php?product_id=<?php echo $product['id']; ?>" ><button class="edit-btn">Edit</button></a>
                        </div>
                    </td>
                    <td>
                        <div class="delete-btn">
                            <a href="remove_product.php?product_id=<?php echo $product['id']; ?>"><button class="delete-btn">Delete</button></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="add-btn">
        <a href="add_product.php"><button class="add-btn">Add Product</button></a>
    </div>
</body>
</html>