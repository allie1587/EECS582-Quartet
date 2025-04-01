<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/17/2025
Revisions:
    03/17/2025 -- Alexandra Stratton -- created barber_profile.php
    03/28/2025 -- Alexandra Stratton -- created the form for updating barber information
Purpose: Allows a barber to update their profile
Missing:
    -- Barber_Gallery
-->
<?php
session_start();
include("db_connection.php");
ini_set('display_errors', 1);
$error = "";
$success = "";
// Initialize variables
$barber = [];
$gallery = [];
$availability = [];
$allowed_types = [
    'image/jpeg',  // JPEG
    'image/png',   // PNG
    'image/gif',   // GIF
    'image/bmp',   // BMP
    'image/webp',  // WebP
    'image/svg+xml', // SVG
    'image/tiff',  // TIFF
    'image/heif',  // HEIF
    'image/heic'   // HEIC
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    error_log("Form submission received: " . print_r($_POST, true));
    error_log("Files received: " . print_r($_FILES, true));

    if (!isset($_SESSION['username'])) {
        die("User not logged in");
    }

    $username = $_SESSION['username'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $instagram = isset($_POST['instagram']) ? $conn->real_escape_string($_POST['instagram']) : '';
    $facebook = isset($_POST['facebook']) ? $conn->real_escape_string($_POST['facebook']) : '';
    $tiktok = isset($_POST['tiktok']) ? $conn->real_escape_string($_POST['tiktok']) : '';

    // Update basic profile info
    $stmt = $conn->prepare("UPDATE Barber_Information SET 
                         First_Name=?, Last_Name=?, Email=?, Phone_Number=?, Instagram=?, Facebook=?, TikTok=?
                         WHERE Barber_ID=?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $phone, $instagram, $facebook, $tiktok, $username);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Handle profile photo upload
    if (isset($_FILES['photo_image']) && $_FILES['photo_image']['error'] == UPLOAD_ERR_OK) {
        $image_dir = 'images/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0755, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES['photo_image']['name']);
        $file_path = $image_dir . $file_name;

        $max_size = 10 * 1024 * 1024; // 10MB

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['photo_image']['tmp_name']);
        finfo_close($file_info);
        if (!in_array($mime_type, $allowed_types)) {
            die("Error: Only JPEG, PNG, and GIF images are allowed.");
        }
        if ($_FILES['photo_image']['size'] > $max_size) {
            die("Error: File size must be less than 10MB.");
        }
        if (move_uploaded_file($_FILES['photo_image']['tmp_name'], $file_path)) {
            // Update the photo path in database
            $stmt = $conn->prepare("UPDATE Barber_Information SET Photo=? WHERE Barber_ID=?");
            $stmt->bind_param("ss", $file_path, $username);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error uploading file.");
        }
    }
    if (isset($_POST['delete_gallery'])) {
        foreach ($_POST['delete_gallery'] as $imageId) {
            $imageId = $conn->real_escape_string($imageId);

            // Get image path first
            $stmt = $conn->prepare("SELECT image FROM Barber_Gallery WHERE ID = ? AND Barber_ID = ?");
            $stmt->bind_param("is", $imageId, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($image = $result->fetch_assoc()) {
                // Delete the file from the file system
                if (file_exists($image['image'])) {
                    unlink($image['image']);
                }
            }
            $stmt->close();

            // Delete the record from the database
            $stmt = $conn->prepare("DELETE FROM Barber_Gallery WHERE ID = ? AND Barber_ID = ?");
            $stmt->bind_param("is", $imageId, $username);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($_FILES['new_gallery_images']) && is_array($_FILES['new_gallery_images']['name'])) {
        $image_dir = 'images/gallery/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0755, true);
        }


        $max_size = 10 * 1024 * 1024; // 10MB

        foreach ($_FILES['new_gallery_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['new_gallery_images']['error'][$key] !== UPLOAD_ERR_OK) {
                continue; // Skip files with errors
            }

            // Get MIME type of uploaded file
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $tmp_name);
            finfo_close($file_info);

            if (!in_array($mime_type, $allowed_types)) {
                continue; // Skip unsupported file types
            }
            if ($_FILES['new_gallery_images']['size'][$key] > $max_size) {
                continue; // Skip oversized files
            }

            $file_name = uniqid() . '_' . basename($_FILES['new_gallery_images']['name'][$key]);
            $file_path = $image_dir . $file_name;

            // Move uploaded file to desired directory
            if (move_uploaded_file($tmp_name, $file_path)) {
                // Insert record into the database
                $stmt = $conn->prepare("INSERT INTO Barber_Gallery (Barber_ID, Image) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $file_path);
                $stmt->execute();
                $stmt->close();
            }
        }
    }




    // Redirect to prevent form resubmission
    if (!headers_sent()) {
        header("Location: barber_profile.php");
        exit();
    } else {
        echo '<script>window.location.href = "barber_profile.php";</script>';
        exit();
    }
}

// Load current user's data
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Get barber info
    $stmt = $conn->prepare("SELECT * FROM Barber_Information WHERE Barber_ID = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $barber = $result->fetch_assoc();
        $stmt->close();
    }

    // Get gallery images
    $stmt = $conn->prepare("SELECT * FROM Barber_Gallery WHERE Barber_ID = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $gallery = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
include("barber_header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <script src="validation.js"></script>

    <title>Barber Customize</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* Button Styling */
        button[type="submit"],
        .btn {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
        }

        button[type="submit"]:hover,
        .btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Gallery Section */
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .gallery-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #f9f9f9;
            text-align: center;
        }

        .gallery-image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
            display: block;
        }

        .gallery-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .gallery-error {
            font-size: 0.8em;
            margin: 5px 0;
            min-height: 1em;
        }
    </style>

</head>

<body>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <div class="container">
        <h1>Profile</h1>
        <form method="POST" enctype="multipart/form-data" data-validate-form onsubmit="return validateBeforeSubmit()">
            <label for="first_name"><strong>First Name:</strong></label>
            <input type="text" name="first_name" id="first_name" data-validate value="<?php echo htmlspecialchars($barber['First_Name'] ?? ''); ?>" required>
            <span id="first_name-error" class="error-message"></span>
            <br>

            <label for="last_name"><strong>Last Name:</strong></label>
            <input type="text" name="last_name" id="last_name" data-validate value="<?php echo htmlspecialchars($barber['Last_Name'] ?? ''); ?>" required>
            <span id="last_name-error" class="error-message"></span>
            <br>

            <label for="email"><strong>Email:</strong></label>
            <input type="email" name="email" id="email" data-validate value="<?php echo htmlspecialchars($barber['Email'] ?? ''); ?>">
            <span id="email-error" class="error-message"></span>

            <label for="phone"><strong>Phone:</strong></label>
            <input type="tel" name="phone" id="phone" data-validate value="<?php echo htmlspecialchars($barber['Phone_Number'] ?? ''); ?>">
            <span id="phone-error" class="error-message"></span>

            <label for="instagram"><strong>Instagram:</strong></label>
            <input type="text" name="instagram" id="instagram" data-validate
                onblur="formValidator.validateSocialMedia('instagram', this.value)"
                value="<?php echo htmlspecialchars($barber['Instagram'] ?? ''); ?>">
            <span id="instagram-error" class="error-message"></span>
            <a id="instagram-link" href="<?= !empty($barber['Instagram']) ? 'https://www.instagram.com/' . htmlspecialchars($barber['Instagram']) : '#' ?>" target="_blank" style="<?= empty($barber['Instagram']) ? 'display:none' : '' ?>">Visit</a>

            <label for="facebook"><strong>Facebook:</strong></label>
            <input type="text" name="facebook" id="facebook" data-validate
                onblur="formValidator.validateSocialMedia('facebook', this.value)"
                value="<?php echo htmlspecialchars($barber['Facebook'] ?? ''); ?>">
            <span id="facebook-error" class="error-message"></span>
            <a id="facebook-link" href="<?= !empty($barber['Facebook']) ? htmlspecialchars($barber['Facebook']) : '#' ?>"
                target="_blank" style="<?= empty($barber['Facebook']) ? 'display:none' : '' ?>">Visit</a>
            <label for="tiktok"><strong>TikTok:</strong></label>
            <input type="text" name="tiktok" id="tiktok" data-validate
                onblur="formValidator.validateSocialMedia('tiktok', this.value)"
                value="<?php echo htmlspecialchars($barber['TikTok'] ?? ''); ?>">
            <span id="tiktok-error" class="error-message"></span>
            <a id="tiktok-link" href="<?= !empty($barber['TikTok']) ? 'https://www.tiktok.com/@' . htmlspecialchars($barber['TikTok']) : '#' ?>" target="_blank" style="<?= empty($barber['TikTok']) ? 'display:none' : '' ?>">Visit</a>
            <br>




            <label for="photo_image"><strong>Profile Photo:</strong></label>
            <input type="file" name="photo_image" id="photo_image" data-validate
                onchange="formValidator.previewImage(this, 'preview-image')">
            <span id="photo_image-error" class="error-message"></span>
            <?php if (!empty($barber['Photo'])): ?>
                <img id="preview-image" src="<?= htmlspecialchars($barber['Photo']) ?>" width="150" style="display:block; margin-top:10px;">
            <?php else: ?>
                <img id="preview-image" src="" width="150" style="display:none; margin-top:10px;">
            <?php endif; ?>
            <br>

            <h3>Galley</h3>
            <div class="gallery-container" id="galleryContainer">
                <?php foreach ($gallery as $image): ?>
                    <div class="gallery-item" data-image-id="<?= $image['ID'] ?>">
                        <img class="gallery-image-preview" src="<?= htmlspecialchars($image['Image']) ?>" width="150">
                        <div class="gallery-controls">
                            <button type="button" class="btn btn-danger remove-gallery-item">Remove</button>
                        </div>
                        <input type="hidden" name="keep_gallery[]" value="<?= $image['ID'] ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" id="addGalleryBtn" class="btn btn-primary">+ Add Image</button>

            <!-- Template for new gallery items -->
            <template id="galleryItemTemplate">
                <div class="gallery-item">
                    <input type="file" name="new_gallery_images[]" class="gallery-image-input" accept="image/*">
                    <img class="gallery-image-preview" src="" style="display: none; width: 150px;">
                    <div class="gallery-error text-danger"></div>
                    <div class="gallery-controls">
                        <button type="button" class="btn btn-danger remove-gallery-item">Remove</button>
                    </div>
                </div>
            </template>

            <br>
            <button type="submit" name="update_profile" class="update-btn">Update Profile</button>
        </form>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const galleryContainer = document.getElementById('galleryContainer');
            const addGalleryBtn = document.getElementById('addGalleryBtn');
            const galleryTemplate = document.getElementById('galleryItemTemplate');

            // Add new gallery item
            addGalleryBtn.addEventListener('click', function() {
                addGalleryItem();
            });

            // Initialize existing gallery items with remove functionality
            galleryContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-gallery-item')) {
                    const galleryItem = e.target.closest('.gallery-item');
                    removeGalleryItem(galleryItem);
                }
            });

            // Handle file input change for preview
            galleryContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('gallery-image-input')) {
                    previewGalleryImage(e.target);
                }
            });

            // Initialize existing gallery items
            initGalleryItems();
        });

        // Add gallery item from template
        function addGalleryItem() {
            const galleryContainer = document.getElementById('galleryContainer');
            const galleryTemplate = document.getElementById('galleryItemTemplate');

            // Clone gallery item template
            const newItem = galleryTemplate.content.cloneNode(true);
            galleryContainer.appendChild(newItem);
        }

        // Remove gallery item
        function removeGalleryItem(galleryItem) {
            const imageId = galleryItem.dataset.imageId;

            if (imageId) {
                // If the image exists in the database, mark it for deletion
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_gallery[]';
                input.value = imageId;
                document.querySelector('form').appendChild(input);
            }

            // Remove the gallery item from the DOM
            galleryItem.remove();
        }

        // Preview image before upload
        function previewGalleryImage(input) {
            const item = input.closest('.gallery-item');
            const preview = item.querySelector('.gallery-image-preview');
            const errorMsg = item.querySelector('.gallery-error');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 10 * 1024 * 1024; // 10MB

                if (!validTypes.includes(file.type)) {
                    errorMsg.textContent = 'Only JPEG, PNG, and GIF images are allowed.';
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                if (file.size > maxSize) {
                    errorMsg.textContent = 'File size must be less than 10MB.';
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                errorMsg.textContent = '';

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function initGalleryItems() {
            const removeButtons = document.querySelectorAll('.remove-gallery-item');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const galleryItem = this.closest('.gallery-item');
                    removeGalleryItem(galleryItem);
                });
            });
        }
    </script>
</body>

</html>