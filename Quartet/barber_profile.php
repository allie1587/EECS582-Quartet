<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/17/2025
Revisions:
    03/17/2025 -- Alexandra Stratton -- created barber_profile.php
    03/28/2025 -- Alexandra Stratton -- created the form for updating barber information
    04/10/2025 -- Alexandra Stratton -- Reduced the complexity
    4/7/2025 - Brinley, update styling
Purpose: Allows a barber to update their profile
-->
<?php
//Connects to the database
session_start();
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Error Messaging
ini_set('display_errors', 1);
$error = "";
$success = "";

// Initializing Variables
$barber_id= '';
$barber= [];
$gallery = [];
$max_size = 10 * 1024 * 1024;
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
// Retrieve Information
if (isset($_SESSION['username'])) {
    $barber_id = $_SESSION['username'];

    // Barber Information
    $sql = "SELECT *
            FROM Barber_Information
            WHERE Barber_ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $barber_id);
        $stmt->execute();
        $barber = $stmt->get_result()->fetch_assoc();
        $photo = $barber['Photo'] ?? '';
    }
    // Barber Gallery
    $sql = "SELECT *
            FROM Barber_Gallery
            WHERE Barber_ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $barber_id);
        $stmt->execute();
        $gallery = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
// Update Profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Update'])){
    // Barber Information
    $first_name = $conn->real_escape_string($_POST['First_Name']);
    $last_name = $conn->real_escape_string($_POST['Last_Name']);
    $email = $conn->real_escape_string($_POST['Email']);
    $phone = $conn->real_escape_string($_POST['Phone']);
    $instagram = isset($_POST['Instagram']) ? $conn->real_escape_string($_POST['Instagram']) : '';
    $facebook = isset($_POST['Facebook']) ? $conn->real_escape_string($_POST['Facebook']) : '';
    $tiktok = isset($_POST['TikTok']) ? $conn->real_escape_string($_POST['TikTok']) : '';
    // Barber's Photo
    if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] == UPLOAD_ERR_OK) {
        $image_dir = 'images/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0755, true);
        }
        $file_name = basename($_FILES['Photo']['name']);
        $file_path = $image_dir . $file_name;
        // Validate the file size
        if ($_FILES['Photo']['size'] > $max_size) {
            echo "Error: File size must be less than 10MB.";
            exit();
        }
        if (!in_array($_FILES['Photo']['type'], $allowed_types)) {
            echo "Error: Only JPEG, PNG, and GIF images are allowed.";
            exit();
        }
        // Move the image to the designated directory
        if (move_uploaded_file($_FILES['Photo']['tmp_name'], $file_path)) {
            $photo = $file_path; 
        } else {
            echo "Error: Failed to move uploaded file.";
            exit();
        }
    }
    $sql = "UPDATE Barber_Information SET First_Name = ?, Last_Name = ?, Email = ?, Phone_Number = ?, Instagram = ?, Facebook = ?, TikTok = ?, Photo = ?
            WHERE Barber_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $first_name, $last_name, $email, $phone, $instagram, $facebook, $tiktok, $photo, $barber_id);
    $stmt->execute();
    $stmt->close();
    // Barber's Portfolio 
    if (isset($_POST['Remove_Photo'])) {
        foreach ($_POST['Remove_Photo'] as $image_id) {
            $image_id = $conn->real_escape_string($image_id);
            $sql = "SELECT Image
                    FROM Barber_Gallery WHERE ID = ? AND Barber_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $image_id, $barber_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($image = $result->fetch_assoc()) {
                // Delete the file from the file system
                if (file_exists($image['Image'])) {
                    unlink($image['Image']);
                }
            }
            $sql = "DELETE FROM Barber_Gallery WHERE ID = ? AND Barber_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $image_id, $barber_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($_FILES['Add_Photos']) && is_array($_FILES['Add_Photos']['name'])) {
        $image_dir = 'images/gallery/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0755, true);
        }
        foreach ($_FILES['Add_Photos']['tmp_name'] as $image => $tmp_name) {
            if ($_FILES['Add_Photos']['error'][$image] !== UPLOAD_ERR_OK) {
                continue;
            }
            $file_name = basename($_FILES['Add_Photos']['name'][$image]);
            $file_path = $image_dir . $file_name;
            if ($_FILES['Add_Photos']['size'][$image] > $max_size) {
                echo "Error: File size must be less than 10MB.";
                exit();
            }
            if (!in_array($_FILES['Add_Photos']['type'][$image], $allowed_types)) {
                echo "Error: Only JPEG, PNG, and GIF images are allowed.";
                exit();
            }
            // Move the image to the designated directory
            if (move_uploaded_file($tmp_name, $file_path)) {
                $gallery_photo = $file_path; 
                $sql = "INSERT INTO Barber_Gallery (Barber_ID, Image) Values (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $barber_id, $file_path);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error: Failed to move uploaded file.";
                exit();
            }
        }
    }
    // Prevent form resubmission
    if (!headers_sent()) {
        header("Location: barber_profile.php");
        exit();
    } else {
        echo '<script>window.location.href = "barber_profile.php";</script>';
        exit();
    }
}
?>
<?php
include("barber_header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/barber_style.css">
</head>
<body>
<h1><?= htmlspecialchars($barber['First_Name'] ?? '') ?> <?= htmlspecialchars($barber['Last_Name'] ?? '') ?>'s Profile</h1>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" id="barber-profile">
            <!-- Personal Information -->
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="form-group">
                    <label for="First_Name">First Name:</label>
                    <input type="text" name="First_Name" id="First_Name" required
                           value="<?= htmlspecialchars($barber['First_Name'] ?? '') ?>">
                    <span class="error" id="First_Name-error"></span>
                </div>
                <div class="form-group">
                    <label for="Last_Name">Last Name:</label>
                    <input type="text" name="Last_Name" id="Last_Name" required
                           value="<?= htmlspecialchars($barber['Last_Name'] ?? '') ?>">
                    <span class="error" id="Last_Name-error"></span>
                </div>
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" name="Email" id="Email"
                           value="<?= htmlspecialchars($barber['Email'] ?? '') ?>">
                    <span class="error" id="Email-error"></span>
                </div>
                <div class="form-group">
                    <label for="Phone">Phone:</label>
                    <input type="tel" name="Phone" id="Phone"
                           value="<?= htmlspecialchars($barber['Phone_Number'] ?? '') ?>">
                    <span class="error" id="Phone-error"></span>
                </div>
            </div>

            <!-- Professional Photo -->
            <div class="form-section">
                <h3>Professional Photo</h3>
                <div class="form-group">
                    <label for="Photo">Upload Photo:</label>
                    <input type="file" name="Photo" id="Photo" accept="image/*">
                    <?php if(!empty($photo)): ?>
                        <img id="preview-image" src="<?= htmlspecialchars($photo) ?>" width="150">
                    <?php else: ?>
                        <img id="preview-image" src="" width="150" style="display:none;">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Social Media -->
            <div class="form-section">
                <h3>Social Media</h3>
                <div class="form-group">
                    <label for="Instagram">Instagram:</label>
                    <input type="text" name="Instagram" id="Instagram"
                           value="<?= htmlspecialchars($barber['Instagram'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="Facebook">Facebook:</label>
                    <input type="text" name="Facebook" id="Facebook"
                           value="<?= htmlspecialchars($barber['Facebook'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="TikTok">TikTok:</label>
                    <input type="text" name="TikTok" id="TikTok"
                           value="<?= htmlspecialchars($barber['TikTok'] ?? '') ?>">
                </div>
            </div>

            <!-- Portfolio -->
            <div class="form-section">
                <h3>Portfolio</h3>
                <div class="gallery-container">
                    <?php foreach($gallery as $image): ?>
                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($image['Image']) ?>" width="100">
                            <button type="button" class="remove-btn" data-id="<?= $image['ID'] ?>">Remove</button>
                            <input type="checkbox" name="Remove_Photo[]" value="<?= $image['ID'] ?>" style="display:none;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-image">Add Image</button>
                <div id="new-images-container"></div>
            </div>

            <button type="submit" name="Update" class="update-btn">Update Profile</button>
        </form>
    </div>

    <!-- Validation Script -->
    <script>
        function validateField(fieldId, validationFn) {
            const field = document.getElementById(fieldId);
            const error = document.getElementById(fieldId + '-error');
            
            field.addEventListener('blur', function() {
                const isValid = validationFn(field.value.trim(), error);
                field.classList.toggle('invalid', !isValid);
            });
            
            field.addEventListener('input', function() {
                error.textContent = '';
                field.classList.remove('invalid');
            });
        }

        // Field Validations
        validateField('First_Name', (value, error) => {
            if (!value) {
                error.textContent = 'First name is required';
                return false;
            }
            if (!/^[A-Za-z\s'-]+$/.test(value)) {
                error.textContent = 'Only letters and basic punctuation allowed';
                return false;
            }
            return true;
        });

        validateField('Last_Name', (value, error) => {
            if (!value) {
                error.textContent = 'Last name is required';
                return false;
            }
            if (!/^[A-Za-z\s'-]+$/.test(value)) {
                error.textContent = 'Only letters and basic punctuation allowed';
                return false;
            }
            return true;
        });

        validateField('Email', (value, error) => {
            if (value && !/^\S+@\S+\.\S+$/.test(value)) {
                error.textContent = 'Invalid email format';
                return false;
            }
            return true;
        });

        validateField('Phone', (value, error) => {
            if (value) {
                const digits = value.replace(/\D/g, '');
                if (digits.length !== 10) {
                    error.textContent = 'Phone must be 10 digits';
                    return false;
                }
            }
            return true;
        });

        // Form Submission
        document.getElementById('barber-profile').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Trigger validation for all fields
            document.querySelectorAll('input[required]').forEach(input => {
                input.dispatchEvent(new Event('blur'));
                if (input.classList.contains('invalid')) isValid = false;
            });
            
            if (!isValid) {
                e.preventDefault();
                document.querySelector('.invalid')?.scrollIntoView({behavior: 'smooth', block: 'center'});
            }
        });
    </script>

    <!-- Gallery and Image Preview Script -->
    <script>
        // Profile Photo Preview
        document.getElementById('Photo').addEventListener('change', function(e) {
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

        // Gallery Management
        document.getElementById('add-image').addEventListener('click', function() {
            const container = document.getElementById('new-images-container');
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'Add_Photos[]';
            input.accept = 'image/*';
            container.appendChild(input);
        });

        // Remove Gallery Items
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const checkbox = this.nextElementSibling;
                checkbox.checked = true;
                this.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>