<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/17/2025
Revisions:
    03/17/2025 -- Alexandra Stratton -- Created the order_confirmation.php
    03/18/2025 -- Alexandra Stratton -- Implemented order confirmation
Purpose: Once an order is placed a confirmation screen will show
-->
<?php
//Connects to the database
session_start();
require 'db_connection.php';
if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}

$order_id = $_GET['order_id'];

// Fetch order details
$order_query = "SELECT Orders.*, Client.first_name, Client.last_name, Client.email, Client.phone 
                FROM Orders 
                JOIN Client ON Orders.client_id = Client.client_id 
                WHERE Orders.order_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Fetch order items
$items_query = "SELECT Order_Items.quantity, Order_Items.price, Order_Items.total_price, products.name, products.image 
                FROM Order_Items 
                JOIN products ON Order_Items.product_id = products.id 
                WHERE Order_Items.order_id = ?";
$stmt = $conn->prepare($items_query);
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$store_query = "SELECT * FROM Store LIMIT 1"; 
$store_result = $conn->query($store_query);
$store = $store_result->fetch_assoc();
?>

<?php
//Adds the header to the page reducing redunacny
include("header.php");
?>
<head>
    <!-- Title for Page --> 
    <title>Order Confrimation</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        /* Style for page */

        .order-details {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        .confirmation-info {
            margin-bottom: 20px;
        }
        .confirmation-info h2 {
            margin-bottom: 10px;
        }
        .confirmation-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <br>
    <br>
    <br>
    <!-- Gives instructions to client about pick-up -->
    <h1>Thank you for your order!</h1>
    <div class="confirmation-info">
        <h2><strong>Confirmation Number:</strong> #<?php echo $order['order_id']; ?></h2>
        <p>We've received your order and are preparing it for pickup.</p>
    </div>
    <div class="confirmation-info">
            <h2>Pickup Location:</h2>
            <p><strong>Store Name:</strong> <?php echo $store['name']; ?></p>
            <p><strong>Address:</strong> <?php echo $store['address']; ?>, <?php echo $store['city']; ?>, <?php echo $store['state']; ?> <?php echo $store['zip_code']; ?></p>
            <p><strong>Phone:</strong> <?php echo $store['phone']; ?></p>
        </div>
    <div class="confirmation-info">
        <h2>Pickup Instructions:</h2>
        <p>1. An email will be sent to <strong><?php echo $order['email']; ?></strong> when your order is ready.</p>
        <p>2. Bring your confirmation number (<strong><?php echo $order['order_id']; ?></strong>) and a valid ID when picking up your order.</p>
        <p>3. Please do not come to the store until you receive confirmation that your order is ready.</p>
    </div>
    <!-- Displays the order -->
    <div class="order-details">
        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            <?php echo $item['name']; ?>
                        </td>
                        <td>
                            x<?php echo $item['quantity']; ?>    
                        </td>
                        <td>
                            $<?php echo number_format($item['price'], 2); ?>
                        </td>
                        <td>
                            x<?php echo number_format($item['total_price'], 2);; ?>    
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <div class="total-price">
                        Total: $<?php echo number_format($order['total_price'], 2); ?>
                    </div>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- Shows the user ways to contact the store -->
    <div class="confirmation-info">
        <h2>Contact Us</h2>
        <p>If you have any questions, please contact us at:</p>
        <p><strong>Email:</strong> <?php echo $store['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $store['phone']; ?></p>
    </div>
    
</body>
    