<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
    03/15/2025  -- Alexandra Stratton  -- Commenting and fixing format
    03/15/2025 -- Alexandra Stratton -- Confirmation page prior to deleting
Other Sources: ChatGTP
Purpose: Allow barbers to see the products seen in the store

-->
<?php
//Connects to the database
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
    <!-- Title for Page --> 
    <title>Product</title>
    <!-- Internal CSS for styling the page -->
    <style>
        /* Table styling */
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
        /* Button styling */
        .edit-btn {
        background: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-family: 'Georgia', serif;
        transition: 0.3s;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .delete-btn {
            background: #FF6A13;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-family: 'Georgia', serif;
            transition: 0.3s;
        }

        .delete-btn:hover {
            background: #FF8A3D;
        }

        /* Add button container */
        .add-btn-container {
            width: 80%; /* Match the table width */
            margin: 20px auto; /* Center the container */
            text-align: right; /* Align the button to the right */
        }

        /* Add button styling */
        .add-btn {
            color: white;
            background: #c4454d;
            padding: 10px 20px;
            font-size: 16px;
            font-family: 'Georgia', serif;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: rgb(143, 48, 55);
        }

        /* Image styling */
        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <br><br>
     <!-- Allows barbers to add a new product to the store -->
     <div class="add-btn-container">
        <a href="add_product.php" class="add-btn"><button class="add-btn">Add Product</button></a>
    </div>
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
                <th>Add</th> <!-- temporary -->
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
                    <td>
                        <form action="add_item.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn">Add to Cart</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>