<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/17/2025
Revisions:
    03/17/2025 -- Alexandra Stratton -- created barber_profile.php
    03/28/2025 -- Alexandra Stratton -- created the form for updating barber information
Purpose: Allows a barber to update their profile
Sources: 
    -- ChatGPT
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
                $stmt = $conn->prepare("INSERT INTO Barber_Gallery (barber_id, image) VALUES (?, ?)");
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
?>
<?php
// Includes the side navagation bar on barberside
include("barber_header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <script src="validate.js"></script>

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

        /* Gallery Section with help from ChatGPT*/
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
    <div class="container">
        <h1>Profile</h1>
        <form method="POST" enctype="multipart/form-data" id="barber-profile-form" onsubmit="return validateBeforeSubmit();">
            <!-- Barber's personal information -->
            <div class="form-section">
                <h2>Personal Information</h2>

                <div class="form-group">
                    <label for="first_name"><strong>First Name:</strong></label>
                    <input type="text" name="first_name" id="first_name"
                        value="<?php echo htmlspecialchars($barber['First_Name'] ?? ''); ?>" required>
                    <span id="first_name-error" style="color: red; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="last_name"><strong>Last Name:</strong></label>
                    <input type="text" name="last_name" id="last_name"
                        value="<?php echo htmlspecialchars($barber['Last_Name'] ?? ''); ?>" required>
                    <span id="last_name-error" style="color: red; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" name="email" id="email"
                        value="<?php echo htmlspecialchars($barber['Email'] ?? ''); ?>">
                    <span id="email-error" style="color: red; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="phone"><strong>Phone:</strong></label>
                    <input type="tel" name="phone" id="phone"
                        value="<?php echo htmlspecialchars($barber['Phone_Number'] ?? ''); ?>">
                    <span id="phone-error" style="color: red; display: none;"></span>
                </div>
            </div>
            <!-- Barber's Social Media -->
            <div class="form-section">
                <h2>Social Media</h2>

                <div class="form-group">
                    <label for="instagram"><strong>Instagram:</strong></label>
                    <input type="text" name="instagram" id="instagram" placeholder="Enter your Instagram username"
                        value="<?php echo htmlspecialchars($barber['Instagram'] ?? ''); ?>">
                    <span id="instagram-error" style="color: red; display: none;"></span>
                    <a id="instagram-link" href="<?= !empty($barber['Instagram']) ? 'https://www.instagram.com/' . htmlspecialchars($barber['Instagram']) : '#' ?>"
                        target="_blank" style="<?= empty($barber['Instagram']) ? 'display:none' : '' ?>">Visit Profile</a>
                </div>

                <div class="form-group">
                    <label for="facebook"><strong>Facebook:</strong></label>
                    <input type="text" name="facebook" id="facebook" placeholder="Enter your Facebook username"
                        value="<?php echo htmlspecialchars($barber['Facebook'] ?? ''); ?>">
                    <span id="facebook-error" style="color: red; display: none;"></span>
                    <a id="facebook-link" href="<?= !empty($barber['Facebook']) ? htmlspecialchars($barber['Facebook']) : '#' ?>"
                        target="_blank" style="<?= empty($barber['Facebook']) ? 'display:none' : '' ?>">Visit Profile</a>
                </div>

                <div class="form-group">
                    <label for="tiktok"><strong>TikTok:</strong></label>
                    <input type="text" name="tiktok" id="tiktok" placeholder="Enter your TikTok username"
                        value="<?php echo htmlspecialchars($barber['TikTok'] ?? ''); ?>">
                    <span id="tiktok-error" style="color: red; display: none;"></span>
                    <a id="tiktok-link" href="<?= !empty($barber['TikTok']) ? 'https://www.tiktok.com/@' . htmlspecialchars($barber['TikTok']) : '#' ?>"
                        target="_blank" style="<?= empty($barber['TikTok']) ? 'display:none' : '' ?>">Visit Profile</a>
                </div>
            </div>

            <!-- Barber's Professial Photo -->
            <div class="form-section">
                <h2>Profile Photo</h2>
                <div class="form-group">
                    <label for="photo_image"><strong>Upload New Photo:</strong></label>
                    <input type="file" name="photo_image" id="photo_image" accept="image/*">
                    <span id="photo_image-error" style="color: red; display: none;"></span>
                    <?php if (!empty($barber['Photo'])): ?>
                        <div class="current-photo">
                            <p>Current Photo:</p>
                            <img id="preview-image" src="<?= htmlspecialchars($barber['Photo']) ?>" width="150">
                        </div>
                    <?php else: ?>
                        <img id="preview-image" src="" width="150" style="display:none;">
                    <?php endif; ?>
                </div>
            </div>


            <!-- Gallery Section -->
            <div class="form-section">
                <h2>Gallery</h2>
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
                <div id="gallery-errors" class="error-message"></div>

                <!-- Adding New Gallery Images -->
                <template id="galleryItemTemplate">
                    <div class="gallery-item">
                        <input type="file" name="new_gallery_images[]" class="gallery-image-input" accept="image/*">
                        <img class="gallery-image-preview" src="" style="display: none; width: 150px;">
                        <div class="gallery-error error-message"></div>
                        <div class="gallery-controls">
                            <button type="button" class="btn btn-danger remove-gallery-item">Remove</button>
                        </div>
                    </div>
                </template>
            </div>

            <button type="submit" name="update_profile" class="update-btn">Update Profile</button>
        </form>
    </div>
    <script>
        // Initialize validation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize barber profile validation
            initBarberProfileValidation();

            // Gallery management functions
            const galleryContainer = document.getElementById('galleryContainer');
            const addGalleryBtn = document.getElementById('addGalleryBtn');
            const galleryTemplate = document.getElementById('galleryItemTemplate');
            const allowedImageTypes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
                'image/webp', 'image/svg+xml', 'image/tiff',
                'image/heif', 'image/heic'
            ];

            // Add new gallery item
            addGalleryBtn.addEventListener('click', function() {
                const newItem = galleryTemplate.content.cloneNode(true);
                const newInput = newItem.querySelector('.gallery-image-input');

                newInput.addEventListener('change', function() {
                    validateGalleryImage(this);
                });

                galleryContainer.appendChild(newItem);
            });

            // Remove gallery item
            galleryContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-gallery-item')) {
                    const galleryItem = e.target.closest('.gallery-item');
                    const imageId = galleryItem.dataset.imageId;

                    if (imageId) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_gallery[]';
                        input.value = imageId;
                        document.querySelector('form').appendChild(input);
                    }

                    galleryItem.remove();
                }
            });

            // Initialize existing gallery items
            document.querySelectorAll('.gallery-image-input').forEach(input => {
                input.addEventListener('change', function() {
                    validateGalleryImage(this);
                });
            });

            // Preview profile photo
            document.getElementById('photo_image')?.addEventListener('change', function() {
                const preview = document.getElementById('preview-image');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Validate gallery image
            function validateGalleryImage(input) {
                const item = input.closest('.gallery-item');
                const errorElement = item.querySelector('.gallery-error');
                const preview = item.querySelector('.gallery-image-preview');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    if (!allowedImageTypes.includes(file.type)) {
                        errorElement.textContent = 'Invalid image type. Allowed: JPEG, PNG, GIF, BMP, WebP, SVG, TIFF, HEIF, HEIC';
                        input.value = '';
                        preview.style.display = 'none';
                        return false;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        errorElement.textContent = 'File size must be less than 10MB';
                        input.value = '';
                        preview.style.display = 'none';
                        return false;
                    }

                    errorElement.textContent = '';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                    return true;
                }
                return false;
            }
        });

        function validatePhone(input, errorElement) {
            const value = input.value.trim();
            const isRequired = isFieldRequired(input.id);

            if (!isRequired && !value) {
                errorElement.style.display = 'none';
                input.classList.remove('is-invalid', 'is-valid');
                return true;
            }

            if (value || isRequired) {
                let numbers = value.replace(/\D/g, ''); 

                if (!numbers) {
                    showError(input, errorElement, "Phone number cannot be empty");
                    return false;
                }

                if (numbers.length === 11 && numbers.startsWith('1')) {
                    numbers = numbers.substring(1);
                }

                if (numbers.length !== 10) {
                    showError(input, errorElement, "Please enter a valid 10-digit phone number");
                    return false;
                }

                input.value = numbers;
            }

            showSuccess(input, errorElement);
            return true;
        }

        function validateBeforeSubmit() {
            let isValid = true;

          


            // Check if any field has an error message displayed
            document.querySelectorAll('span[id$="-error"]').forEach(errorSpan => {
                if (errorSpan.style.display !== 'none' && errorSpan.textContent.trim() !== '') {
                    isValid = false;
                }
            });

            return isValid;
        }
    </script>
</body>

</html>