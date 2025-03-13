<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Made code for this page
Purpose: This allows for users to edit the products seen on the product page
Issues:
    - NOT adding to the database
    -- There is an image error
    -- But even when getting rid of the image it still won't update the databse
-->
<?php
// Start the session to remember user info
session_start();
$conn = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to database successfully.<br>";
}
// Add a new product
function addProduct($name, $description, $price, $imagePath) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Products (name, description, price, image) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
    if (!$stmt->execute()) {
        die('Execute error: ' . $stmt->error);
    } else {
        echo "Product added successfully!<br>";
    }
    $stmt->close();
}

// Edit a product
function editProduct($id, $name, $description, $price, $imagePath) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Products SET name = ?, description = ?, price = ?, image = ? WHERE product_id = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);
    
    if (!$stmt->execute()) {
        die('Execute error: ' . $stmt->error);
    }
    $stmt->close();
}
// Remove a product
function removeProduct($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
// Fetch all products
function getProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM Products");
    return $result;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);
        var_dump($_FILES);
        if (isset($_POST['add'])) {
            echo "Add button clicked";
            $name = $_POST['product_name'];
            $description = $_POST['product_description'];
            $price_dollar = $_POST['product_price_dollars'];
            $price_cent = $_POST['product_price_cents'];
            $price = $price_dollar . '.' . $price_cent;
            addProduct($name, $description, $price, null);
            header("Location: edit_store.php");
            exit();
        }
    
    
    //Editing the product
    elseif (isset($_POST['edit'])) {
        $id = $_POST['product_id'];
        $name = $_POST['product_name'];
        $description = $_POST['product_description'];
        $price = $_POST['product_price'];
        $image = $_FILES['product_image']['name'];
        $imageTmp = $_FILES['product_image']['tmp_name'];
        move_uploaded_file($imageTmp, "images/" . $image); 

        editProduct($id, $name, $description, $price, "images/" . $image);
        header("Location: edit_store.php"); 
        exit();
    } 
    //Removing the product
    elseif (isset($_POST['remove'])) {
        // Handle removing product
        $id = $_POST['product_id'];
        removeProduct($id);
        header("Location: edit_store.php"); 
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Define character encoding-->
    <meta charset="UTF-8">
    <!--Ensure proper rendering and touch zooming on mobile devices-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Name of Page-->
    <title>Edit Store</title>
    <!--Style choices for page, they include font used, margins, alignation, background color, display types, and some others-->
    <style>
        /* ChatGTP helped with the style for professional look */

        /* Applies styles to the entire body */
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color:rgba(36, 35, 35);
            color:white; 
        }
        /* Top Bar at Top with Pages and Login */
        .top-bar {
            background-color: #d32f2f; 
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 70px; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        /* Size of Letters on it's header */
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
        }
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
            padding: 15px;
            background: #d32f2f;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #b71c1c;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: left;
        }
        .modal h2 {
            margin-top: 0;
            color: black;
            text-align: center;
        }
        .close-btn {
            cursor: pointer;
            font-size: 24px;
            position: absolute;
            right: 15px;
            top: 10px;
            color: black;
            font-weight: bold;
        }
        .close-btn:hover {
            color: black;
        }
        .modal label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: black;
        }
        .modal input {
            width: 75%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        /* Price Input Layout */
        .price-input {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 5px;
        }
        .price-input span {
            font-size: 20px;
            font-weight: bold;
        }
        .price-input input {
            width: 50px;
            text-align: center;
        }
        /* Button Styling */
        .modal button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #d32f2f;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .modal button:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>
    <h1>Product Management</h1>
    <div class="top-bar">
        <h1>Quartet's Barbershop</h1>
        <div class="menu">
            <button onclick="location.href='store.php'">Store</button>
        </div>
    </div>
    <div id="currentProduct">
        <table>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
                // ChatGPT helped
                $products = getProducts();
                while ($product = $products->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $product['product_id'] . "</td>"; 
                    echo "<td>" . $product['name'] . "</td>";
                    echo "<td><img src='" . $product['image'] . "' width='50' height='50'></td>";
                    echo "<td><button onclick='openEditModal(" . $product['product_id'] . ")'>Edit</button></td>";
                    echo "<td><form method='POST'><input type='hidden' name='product_id' value='" . $product['product_id'] . "'><button type='submit' name='remove'>Delete</button></form></td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
    <div id="addProduct">
        <button class="add-btn" onclick="openAddModal()">Add Product</button>
    </div>

    <div id="add-modal" class="modal">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <label>Name: <br><input type="text" id="product_name" required></label><br>
            <label>Description:<br><input type="text" id="product_description" required></label><br>
            <label>Price:<br>
                <div class="price-input">
                    <span>$</span>
                    <input type="text" id="product_price_dollars" oninput="validateDollars()" placeholder="0">
                    <span>.</span>
                    <input type="text" id="product_price_cents" maxlength="2" oninput="validateCents()" placeholder="00">
                </div></label><br>
            <label>Image:<br><input type="file" id="product_image" ></label><br>
            <button type="submit" name="add">Add Product</button>
        </form>
    </div>

    <!-- Edit Pop-Up -->
    <div id="edit-modal" class="modal">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <label>Name: <br><input type="text" id="product_name" required></label><br>
            <label>Description:<br><input type="text" id="product_description" required></label><br>
            <label>Price:<br>
                <div class="price-input">
                    <span>$</span>
                    <input type="text" id="product_price_dollars" oninput="validateDollars()" placeholder="0">
                    <span>.</span>
                    <input type="text" id="product_price_cents" maxlength="2" oninput="validateCents()" placeholder="00">
                </div></label><br>
            <label>Image:<br><input type="file" id="product_image" required></label><br>
            <button type="submit">Update</button>
        </form>
    </div>

    <script>
        function validateDollars() {
            let dollarsInput = document.getElementById("product_price_dollars");
            dollarsInput.value = dollarsInput.value.replace(/\D/g, ''); 
        }

        function validateCents() {
            let centsInput = document.getElementById("product_price_cents");
            centsInput.value = centsInput.value.replace(/\D/g, '').slice(0, 2);
        }
        function openAddModal() {
            document.getElementById('add-modal').style.display = 'block';
        }
        function closeAddModal() {
            document.getElementById('add-modal').style.display = 'none';
        }
        function openEditModal(id, name, img){
            fetch(`getProduct.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_product_id').value = data.product_id;
                    document.getElementById('edit_product_name').value = data.name;
                    document.getElementById('edit_product_description').value = data.description;
                    document.getElementById('edit_product_price').value = data.price;
                    document.getElementById('edit-modal').style.display = 'block';
            });
        }
        function closeEditModal(){
            document.getElementById('edit-modal').style.display = 'none';
        }
    </script>
</body>
</html>