<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the add product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
    03/15/2025  -- Alexandra Stratton  -- Commenting and fixing format
    03/15/2025 -- Alexandra Stratton -- Added error messaging 
Other Sources: ChatGTP
Purpose: Allow barbers to add the products seen in the store
-->
<?php
// Connects to the database
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate a unique product ID
    $product_id = uniqid();
    // Retrieve product details from the submitted form
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    // Used ChatGPT for debugging
    // Check if a file was uploaded and if there are no errors
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        // Define the directory where images will be uploaded
        $image_dir = 'images/';
        // Create the upload directory if it does not exist
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0755, true);
        }
        // Get the original file name and set the file path for storage
        $file_name = basename($_FILES['product_image']['name']);
        $file_path = $image_dir . $file_name;

        // Define allowed file types and maximum file size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 10 * 1024 * 1024;
        // Validate the file type
        if (!in_array($_FILES['product_image']['type'], $allowed_types)) {
            echo "Error: Only JPEG, PNG, and GIF images are allowed.";
            exit();
        }
        // Validate the file size
        if ($_FILES['product_image']['size'] > $max_size) {
            echo "Error: File size must be less than 10MB.";
            exit();
        }
        // Move the image to the designated directory
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_path)) {
            $product_image = $file_path; 
        } else {
            echo "Error: Failed to move uploaded file.";
            exit();
        }
    } else {
        // Display an error if no file was uploaded or an error occurred
        echo "Error: No image uploaded or there was an error. Error code: " . $_FILES['product_image']['error'];
        exit();
    }

    // Prepares the SQL for inserting the new product into the database
    $sql = "INSERT INTO products (id, name, description, price, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Execute the statement and check if the insertion was successful
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sssds", $product_id, $product_name, $product_description, $product_price, $product_image);
    // Execute the statement and check if the insertion was successful
    if ($stmt->execute()) {
        echo "Product added successfully!";
        // Redirect to the product page after inserting infor into database
        header('Location: product.php');
        exit();
    } else {
        // Display an error message if execution fails
        echo "Error executing statement: " . $stmt->error;
    }
}
?>
<?php
//Adds the header to the page reducing redunacny
include('header.php');
?>
<head>
<!-- Title for Page --> 
<title>Add Product</title>
<!-- Internal CSS for styling the page -->
<link rel="stylesheet" href="style/styles.css">
<style>

    /* Style for inputing a file */
    .file-input-container {
        position: relative;
        margin-top: 10px;
    }
    .file-input-container input[type="file"] {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-label {
        display: inline-block;
        padding: 10px 20px;
        background-color: rgba(36, 35, 35);
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .file-input-label:hover {
        background-color: rgba(36, 35, 35);
    }
    /* Add prodct button style */
    .add-btn {
        color: white;
        background: #c4454d;
        padding: 5px 100px;
        font-size: 18px;
        font-family: 'Georgia', serif;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }
    .add-btn:hover {
        background: rgb(143, 48, 55);
    }
    /* Back button style */
    .back-btn {
        color: white;
        background: #c4454d;
        padding: 15px 50px;
        font-size: 16px;
        font-family: 'Georgia', serif;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        position: fixed;
        bottom: 20px;
        right: 20px;

    }
    .back-btn:hover {
        background: rgb(143, 48, 55);
    }
</style>
</head>
<body>
    <!--let's user know the current page they are on-->
    <h1>Add Product</h1>
    <!-- Allows barber's to add a new item to the store -->
    <div class="add-container">
    <form action="add_product.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required onchange="validateName()">
            <span id="name-error" style="color: red; display: none;"></span>
            <br>
            <label for="product_description">Product Description:</label>
            <textarea name="product_description" required></textarea>
            <br>
            <label for="product_price">Product Price:</label>
            <input type="number" name="product_price" id="product_price" step="0.01" required onchange="validatePrice()">
            <span id="price-error" style="color: red; display: none;"></span>
            <br>
            <label for="product_image">Product Image:</label>
            <div class="file-input-container">
                <input type="file" name="product_image" id="file-input" accept="image/*" required onchange="validateImage()">
                <label for="file-input" class="file-input-label">Choose File</label>
                <span id="file-name" class="file-name"></span>
                <span id="image-error" style="color: red; display: none;"></span>
            </div>
            <br>
            <button type="submit" class="add-btn">Add Product</button>
        </form>
    </div>
    <!-- Redirects to product.php page (Barber's side) -->
    <div class="back-btn">
        <a href="product.php" class="back-btn"><button class="back-btn">Back to Product List</button></a>
    </div>
    <script>
         // Function to display the selected file name
        function displayFileName() {
            const fileInput = document.getElementById('file-input');
            const fileNameDisplay = document.getElementById('file-name');

            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        }

        // Validate product name
        function validateName() {
            const nameInput = document.getElementById('product_name');
            const nameError = document.getElementById('name-error');
            if (nameInput.value.length > 70) {
                nameError.textContent = "Maximum 70 characters allowed";
                nameError.style.display = 'inline';
                return false;
            } else {
                nameError.style.display = 'none';
                return true;
            }
        }

        // Validate product price
        function validatePrice() {
            const priceInput = document.getElementById('product_price');
            const priceError = document.getElementById('price-error');
            if (priceInput.value <= 0 || isNaN(priceInput.value)) {
                priceError.textContent = "Price must be a positive number";
                priceError.style.display = 'inline';
                return false;
            } else {
                priceError.style.display = 'none';
                return true;
            }
        }
        // Validate product image
        function validateImage() {
            const imageInput = document.getElementById('file-input');
            const imageError = document.getElementById('image-error');
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                if (!allowedTypes.includes(file.type)) {
                    imageError.textContent = "Only JPEG, PNG, and GIF images are allowed.";
                    imageError.style.display = 'inline';
                    return false;
                } else if (file.size > maxSize) {
                    imageError.textContent = "File size must be less than 10MB.";
                    imageError.style.display = 'inline';
                    return false;
                } else {
                    imageError.style.display = 'none';
                    displayFileName();
                    return true;
                }
            } else {
                imageError.textContent = "Please upload an image.";
                imageError.style.display = 'inline';
                return false;
            }
        }

        // Validate the entire form
        function validateForm(event) {
            const isNameValid = validateName();
            const isPriceValid = validatePrice();
            const isImageValid = validateImage();

            if (!isNameValid || !isPriceValid || !isImageValid) {
                event.preventDefault(); // Prevent form submission
                return false;
            }
            return true; // Allow form submission
        }

        // Attach the validateForm function to the form's submit event
        document.querySelector('form').addEventListener('submit', validateForm);
    </script>
</body>
</html>