<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/12/2025 -- Alexandra Stratton -- Created the edit product page
    03/14/2025 -- Alexandra Stratton -- Implemented header.php
    03/15/2025  -- Alexandra Stratton  -- Commenting and fixing format
    03/15/2025 -- Alexandra Stratton -- Added error messaging 
    04/11/2025 -- Alexandra Stratton -- Implement heading and fix structure
Other Sources: ChatGTP
Purpose: Allow barbers to edit the products seen in the store

-->
<?php
//Connects to the database
session_start();
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$barber_id = $_SESSION['username'];
$sql = "SELECT Barber_Information.Role FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role == "Barber") {
    include("barber_header.php");
}
else {
    include("manager_header.php");
}

if (isset($_GET['Product_ID'])) {
    //Gets the product id
    $product_id = $_GET['Product_ID'];
    //Retrieves all the information from the give product_id
    $sql = "SELECT * FROM Products WHERE Product_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve product details from the submitted form
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $product_price = $_POST['product_price'];
        // Used ChatGPT for debugging
        // Check if a file was uploaded and if there are no errors
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $image_dir = 'images/';
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
            //  Validate the file size
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
            //Otherwise the image stays the same
            $product_image = $product['Image'];
        }
        // Prepares the sql for updating the database
        $sql = "UPDATE Products SET Name = ?, Description = ?, Price = ?, Image = ? WHERE Product_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $product_name, $product_description, $product_price, $product_image, $product_id);
        // Execute the statement and check if the update was successful
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
<head>
    <!-- Title for Page -->
    <title>Edit Product</title>
    <!-- Internal CSS for styling the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Style for the form box */
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            color: black;
        }

        /* Style for the labels */
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Style for input boxes */
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

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

        /* Image preview container */
        .image-preview-wrapper {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }

        /* Individual image preview styling */
        .image-preview-box {
            width: 48%;
            text-align: center;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .current-image-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .new-image-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: none;
            /* Hidden by default */
        }

        /* Update the product button style */
        .update-btn {
            color: white;
            background: #c4454d;
            padding: 5px 100px;
            font-size: 18px;
            font-family: 'Georgia', serif;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .update-btn:hover {
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

        .content-wrapper {
            transition: margin-left 0.3s ease;
            margin-left: 10px;
        }

        .sidebar-active .content-wrapper {
            margin-left: 300px; 
        }

        .sidebar-deactive .content-wrapper {
            margin-left: 10px; 
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
    <br><br>
        <!--let's user know the current page they are on-->
        <h1>Edit Product</h1>
        <!-- Allows barber's to add a new item to the store -->
        <div class="container">
            <form action="edit_product.php?Product_ID=<?php echo $product['Product_ID']; ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="product_name" value="<?php echo $product['Name']; ?>" required onchange="validateName()">
                <span id="name-error" style="color: red; display: none;"></span>
                <br>
                <label for="product_description">Product Description:</label>
                <textarea name="product_description" required><?php echo $product['Description']; ?></textarea>
                <br>
                <label for="product_price">Product Price:</label>
                <input type="number" name="product_price" id="product_price" step="0.01" value="<?php echo $product['Price']; ?>" required onchange="validatePrice()">
                <span id="price-error" style="color: red; display: none;"></span>
                <br>
                <!-- Image preview section -->
                <div class="image-preview-wrapper">
                    <!-- Current image box (left side) -->
                    <div class="image-preview-box">
                        <div class="current-image-label">Current Image:</div>
                        <img id="current-image-preview" class="image-preview" src="<?php echo $product['Image']; ?>" alt="Current Product Image" <?php echo empty($product['Image']) ? 'style="display:none;"' : ''; ?>>
                        <div id="no-image-message" <?php echo !empty($product['Image']) ? 'style="display:none;"' : ''; ?>>No image currently set</div>
                    </div>

                    <!-- New image box (right side) -->
                    <div class="image-preview-box">
                        <div id="new-image-label" class="new-image-label">New Image:</div>
                        <img id="new-image-preview" class="image-preview" src="" alt="New Product Image Preview" style="display:none;">
                    </div>
                </div>

                <label for="product_image">Upload Image:</label>
                <div class="file-input-container">
                    <input type="file" name="product_image" id="file-input" accept="image/*" onchange="validateImage()">
                    <label for="file-input" class="file-input-label">Choose File</label>
                    <span id="file-name" class="file-name"></span>
                    <span id="image-error" style="color: red; display: none;"></span>
                </div>

                <br>


                <button type="submit" class="update-btn">Update Product</button>
            </form>
        </div>
        <!-- Redirects to product.php page (Barber's side) -->
        <div class="back-btn">
            <a href="product.php" class="back-btn"><button class="back-btn">Back to Product List</button></a>
        </div>
        <script>
            // Function to preview the selected image
            function previewSelectedImage(event) {
                const fileInput = event.target;
                const newImagePreview = document.getElementById('new-image-preview');
                const newImageLabel = document.getElementById('new-image-label');
                const noImageMessage = document.getElementById('no-image-message');

                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        newImagePreview.src = e.target.result;
                        newImagePreview.style.display = 'block';
                        newImageLabel.style.display = 'block';
                    }

                    reader.readAsDataURL(fileInput.files[0]);
                } else {
                    newImagePreview.style.display = 'none';
                    newImageLabel.style.display = 'none';
                }
            }
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
                        previewSelectedImage({
                            target: imageInput
                        });
                        return true;
                    }
                } else {
                    imageError.style.display = 'none';
                    displayFileName();
                    document.getElementById('new-image-preview').style.display = 'none';
                    document.getElementById('new-image-label').style.display = 'none';
                    return true;
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
            // Initialize event listeners
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('file-input');
                fileInput.addEventListener('change', validateImage);

                // Initialize the form validation
                document.querySelector('form').addEventListener('submit', validateForm);
            });
        </script>
    </div>
</body>

</html>