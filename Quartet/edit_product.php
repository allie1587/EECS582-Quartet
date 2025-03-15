<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
Purpose: Allow barbers to edit the products seen in the store

-->
<?php
require 'db_connection.php'; 
//ChatGPT helped debugg image issues
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            $product_image = $product['image'];
        }

        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $product_name, $product_description, $product_price, $product_image, $product_id);

        if ($stmt->execute()) {
            header('Location: product.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "Product not found.";
    exit();
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
        .update-btn {
            width: 200px;

            background: #c4454d;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .update-btn:hover {
            background: rgb(143, 48, 55);
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .container {
            background: white;
            color: black;
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
    
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Product</h1>
        <form action="edit_product.php?product_id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" value="<?php echo $product['name']; ?>" required>
                <br>
                <label for="product_description">Product Description:</label>
                <textarea name="product_description" required><?php echo $product['description']; ?></textarea>
                <br>
                <label for="product_price">Product Price:</label>
                <input type="number" name="product_price" step="0.01" value="<?php echo $product['price']; ?>" required>
                <br>
                <label for="product_image">Product Image:</label>
                <input type="file" name="product_image" accept="image/*">
                <br> 
                <button type="submit" class="update-btn">Update Product</button>
        </form>
    </div>
    <a href="product.php"><button>Back to Product List</button></a>
</body>
</html>