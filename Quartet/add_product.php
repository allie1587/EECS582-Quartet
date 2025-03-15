<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
Purpose: Allow barbers to add the products seen in the store

-->
<?php
//ChatGPT helped with image
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = uniqid();  
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $upload_dir = 'images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = basename($_FILES['product_image']['name']);
        $file_path = $upload_dir . $file_name;


        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 10 * 1024 * 1024;

        if (!in_array($_FILES['product_image']['type'], $allowed_types)) {
            echo "Error: Only JPEG, PNG, and GIF images are allowed.";
            exit();
        }

        if ($_FILES['product_image']['size'] > $max_size) {
            echo "Error: File size must be less than 10MB.";
            exit();
        }

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_path)) {
            $product_image = $file_path; 
        } else {
            echo "Error: Failed to move uploaded file.";
            exit();
        }
    } else {
        echo "Error: No image uploaded or there was an error. Error code: " . $_FILES['product_image']['error'];
        exit();
    }

  
    $sql = "INSERT INTO products (id, name, description, price, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sssds", $product_id, $product_name, $product_description, $product_price, $product_image);

    if ($stmt->execute()) {
        echo "Product added successfully!";
        header('Location: product.php');
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
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
        color: white;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .add-btn {
        width: 25%;
        text-align: center;
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
    img {
        max-width: 100px;
        height: auto;
    }
</style>
</head>
<body>
    <h1>Add Product</h1>
    <div class="container">
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <br>
            <input type="text" name="product_name" required>
            <br>
            <label for="product_description">Product Description:</label>
            <br>
            <textarea name="product_description" required></textarea>
            <br>
            <label for="product_price">Product Price:</label>
            <br>
            <input type="number" name="product_price" step="0.01" required>
            <br>
            <label for="product_image">Product Image:</label>
            <br>
            <input type="file" name="product_image" accept="image/*" required>
            <br>
            <button type="submit" class="add-btn">Add Product</button>
        </form>
    </div>
    <div class="return">
        <a href="product.php"><button>Back to Product List</button></a>
    </div>
</body>
</html>