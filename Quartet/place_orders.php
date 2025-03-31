<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/17/2025
Revisions:
    03/17/2025 -- Alexandra Stratton -- Created the place_order.php
    03/29/2025 -- Alexandra Stratton -- Add Comments
Purpose: Allow customers to place orders from their shopping cart
-->

<?php
session_start();
require 'db_connection.php'; // Ensure this file contains your database connection logic
$error = "";
$success = "";
$session_id = session_id();
$sql = "SELECT cart.*, products.name, products.price, products.image 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $comments = $_POST['comments'];

    // Check if the client already exists
    $check_client_query = "SELECT client_id FROM Client WHERE email = ?";
    $stmt = $conn->prepare($check_client_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Client exists, fetch their client_id
        $row = $result->fetch_assoc();
        $client_id = $row['client_id'];

        // Update client information
        $update_client_query = "UPDATE Client SET first_name = ?, last_name = ?, phone = ? WHERE client_id = ?";
        $stmt = $conn->prepare($update_client_query);
        $stmt->bind_param("ssss", $first_name, $last_name, $phone, $client_id);
        $stmt->execute();
    } else {
        // Insert new client
        $insert_client_query = "INSERT INTO Client (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_client_query);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
        $stmt->execute();
        $client_id = $stmt->insert_id;
    }

    // Insert the order
    $order_query = "INSERT INTO Orders (client_id, total_price, comments) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("ids", $client_id, $total_price, $comments);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $item_total_price = $price * $quantity;

        $order_item_query = "INSERT INTO Order_Items (order_id, product_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($order_item_query);
        $stmt->bind_param("isidd", $order_id, $product_id, $quantity, $price, $item_total_price);
        $stmt->execute();
    }

    // Clear the cart for the current session
    $clear_cart_query = "DELETE FROM cart WHERE session_id = ?";
    $stmt = $conn->prepare($clear_cart_query);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();

}
?>

<?php
//Adds the header to the page reducing redunacny
include("header.php");
?>
<head>
    <!-- Title for Page --> 
    <title>Place Order</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        /* Style for the page */

        .cart-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
            text-align: center;
        }
        .cart-contatiner label {
            text-align: left;
        }
        .basic-details {
            flex: 1;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        .order-details {
            flex: 2;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #c4454d;
            color: white;
        }
        td {
            color: black;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .total-price {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 20px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
        }
        label {
            text-align: left;
        }
        /* Style for input boxes */
        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="tel"],
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
        .confirm-btn {
            color: white;
            background: #c4454d;
            padding: 5px 100px;
            font-size: 18px;
            font-family: 'Georgia', serif;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .confirm-btn:hover {
            background: rgb(143, 48, 55);
        }
    </style>
</head>
<body>
<h1>Place Your Order</h1>
<!-- Form for client information -->
<div class="cart-container">
    <div class="order-details">
        <h2>Basic Details</h2>
        <form action="place_orders.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            
            <label for="first_name"><strong>First Name:</strong></label>
            <input type="text" name="first_name" id="first_name" required onchange="validatefName()">
            <span id="fname-error" style="color: red; display: none;"></span>
            <br>
            <label for="last_name"><strong>Last Name:</strong></label>
            <input type="text" name="last_name" id="last_name" required onchange="validatelName()">
            <span id="lname-error" style="color: red; display: none;"></span>
            <br>
            <label for="email"><strong>Email:</strong></label>
            <input type="email" name="email" id="email" required onchange="validateEmail()">
            <span id="email-error" style="color: red; display: none;"></span>
            <br>
            <label for="phone"><strong>Phone #:</strong></label>
            <input type="tel" name="phone" id="phone" required onchange="validatePhone()">
            <span id="phone-error" style="color: red; display: none;"></span>
            <br>
            <label for="comments"><strong>Comments:</strong></label>
            <textarea name="comments"></textarea>
            <br>
            <br>
            <input type="submit" value="Place Order" class="confirm-btn">
        </form>
    </div>
    <!-- Displays cart seen in shopping cart -->
    <div class="order-details">
        <h2>Order Details</h2>
        <table>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        </td>
                        <td>
                            <?php echo $item['name']; ?>
                        </td>
                        <td>
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </td>
                        <td>
                            x<?php echo $item['quantity']; ?>    
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <div class="total-price">
                        Total: $<?php echo number_format($total_price, 2); ?>
                    </div>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script>
    /* Validates First Name */
    function validatefName() {
        const nameInput = document.getElementById('first_name');
        const nameError = document.getElementById('fname-error');
        const nameRegex = /^[A-Za-z\s'-]+$/;
        if (!nameRegex.test(nameInput.value)) {
            nameError.textContent = "First name can only contain letters, spaces, hyphens, or apostrophes.";
            nameError.style.display = 'inline';
            return false;
        } else if (nameInput.value.length > 50 || nameInput.value.length < 2) {
            nameError.textContent = "First Name must be between 2 and 50 characters";
            nameError.style.display = 'inline';
            return false;
        } else {
            nameError.style.display = 'none';
            return true;
        }
    }
    /* Validates Last Name */
    function validatelName() {
        const nameInput = document.getElementById('last_name');
        const nameError = document.getElementById('lname-error');
        const nameRegex = /^[A-Za-z\s'-]+$/;
        if (!nameRegex.test(nameInput.value)) {
            nameError.textContent = "Last name can only contain letters, spaces, hyphens, or apostrophes.";
            nameError.style.display = 'inline';
            return false;
        }
        if (nameInput.value.length > 50 || nameInput.value.length < 2) {
            nameError.textContent = "Last Name must be between 2 and 50 characters";
            nameError.style.display = 'inline';
            return false;
        } else {
            nameError.style.display = 'none';
            return true;
        }
    }
    /* Validates Email */
    function validateEmail() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailInput.value.trim() === "") {
            emailError.textContent = "Email cannot be empty.";
            emailError.style.display = 'inline';
            return false;
        }
        if (!emailRegex.test(emailInput.value)) {
            emailError.textContent = "Please enter a valid email address (e.g., user@example.com).";
            emailError.style.display = 'inline';
            return false;
        }
        emailError.style.display = 'none';
        return true;
    }
    /* Validates Phone Number */
    function validatePhone() {
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');
        let phoneNumber = phoneInput.value.trim();

        if (phoneNumber === "") {
            phoneError.textContent = "Phone number cannot be empty.";
            phoneError.style.display = 'inline';
            return false;
        }

        // Remove all non-numeric characters
        phoneNumber = phoneNumber.replace(/\D/g, '');

        // Remove country code if it starts with "1" (U.S. numbers)
        if (phoneNumber.length === 11 && phoneNumber.startsWith('1')) {
            phoneNumber = phoneNumber.substring(1);
        }

        // Ensure it's a valid 10-digit U.S. number
        if (phoneNumber.length !== 10) {
            phoneError.textContent = "Please enter a valid phone number";
            phoneError.style.display = 'inline';
            return false;
        }

        // Hide error message if valid
        phoneError.style.display = 'none';

        // Format phone number as ##########
        phoneInput.value = phoneNumber;

        return true;
    }
    
    // Validate the entire form
    function validateForm(event) {
        const isfNameValid = validatefName();
        const islNameValid = validatelName();
        const isEmailValid = validateEmail();
        const isPhoneValid = validatePhone();

        if (!isfNameValid || !islNameValid || !isEmailValid || !isPhoneValid) {
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
