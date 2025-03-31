<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/12/2025
Revisions:
    03/16/2025 -- Alexandra Stratton -- Created the cart.php
Purpose: Customers shopping carter
Other Sources: ChatGPT
-->
<?php
session_start();
//Connects to the database
require 'db_connection.php'; 
//Gets everything in session_id's cart
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

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>
<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style1.css">
    <title>Shopping Cart</title>
    <style>

        .cart-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .empty-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .empty-btn, .checkout-btn, .continue-btn {
            background: #c4454d;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .empty-btn:hover, .checkout-btn:hover, .continue-btn:hover {
            background: rgb(143, 48, 55);
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
        .quantity-btn {
            padding: 5px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .quantity-btn:hover {
            background-color: #0056b3;
        }
        .remove-btn {
            background-color: #FF6A13;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .remove-btn:hover {
            background-color: #FF8A3D;
        }
        img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    <div class="cart-container">
        <div class="empty-btn-container">
            <a href="empty_cart.php" class="empty-btn">Empty Cart</a>
        </div>
        <!-- List everything in cart -->
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            <?php echo $item['name']; ?>
                        </td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="update_quantity.php" method="POST" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="quantity-btn">-</button>
                            </form>
                            <?php echo $item['quantity']; ?>
                            <form action="update_quantity.php" method="POST" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="quantity-btn">+</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form action="remove_item.php" method="POST" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Gives total price -->
        <div class="total-price">
            Total: $<?php echo number_format($total_price, 2); ?>
        </div>
        <!-- Place order button -->
        <div class="btn-container">
            <a href="store.php" class="continue-btn">Continue Shopping</a>
            <a href="place_orders.php" class="checkout-btn">Check Out</a>
        </div>
    </div>
</body>
</html>
