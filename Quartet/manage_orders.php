<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/32/2025
Revisions:
    03/31/2025 -- Alexandra Stratton -- created manage_orders.php
 Purpose: Allow the manager/barber to view more information about the order

 -->
<?php
// Error Messaging
ini_set('display_errors', 1);
$error = "";
$success = "";
?>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>


<?php
//Connects to the database
session_start();
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$barber_id = $_SESSION['username'];
$sql = "SELECT Role FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!isset($_GET['Order_ID'])) {
    die("Order ID not provided.");
}

$order_id = $_GET['Order_ID'];

// Fetch order details
$sql = "SELECT Orders.*, Client.First_Name, Client.Last_Name, Client.Email, Client.Phone 
                FROM Orders 
                JOIN Client ON Orders.Client_ID = Client.Client_ID
                WHERE Orders.Order_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Fetch order items
$sql = "SELECT Order_Items.Quantity, Order_Items.Price, Products.Name, Products.Image 
                FROM Order_Items 
                JOIN Products ON Order_Items.Product_ID = Products.Product_ID 
                WHERE Order_Items.Order_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_change'])) {
    // Retrieve form data
    $status = $_POST['new_status'];
    $barber_comments = $_POST['barber_notes'];
    $sql = "UPDATE Orders SET Status = ?, Barber_Comments = ? WHERE Order_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $barber_comments, $order_id);
    $stmt->execute();
    header("Location: manage_orders.php?Order_ID=$order_id");
    exit();
}
?>

<?php
if ($user['Role'] == "Barber") {
    include("barber_header.php");
} else {
    include("manager_header.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Order #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="style/barber_style.css">
</head>

<body>
    <!-- Display error or success messages -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <h1>Order #<?php echo htmlspecialchars($order['Order_ID']) ?></h1>
    <div class="container">
        <div class="back-container">
            <a href="orders.php" class="back-btn">Back</a>
        </div>
        <div class="order-wrapper">
            <div class="card client-info">
                <h2>Client Details</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['First_Name'] . ' ' . $order['Last_Name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['Email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['Phone']); ?></p>
                <?php if (!empty($order['Client_Comments'])): ?>
                    <p><strong>Client Comments:</strong></p><br>
                    <?php echo htmlspecialchars($order['Client_Comments']) ?>
                <?php endif; ?>
                <?php if (!empty($order['Barber_Comments'])): ?>
                    <p><strong>Barber Notes:</strong></p><br>
                    <?php echo htmlspecialchars($order['Barber_Comments']) ?>
                <?php endif; ?>
            </div>
            <div class="card order-details">
                <h2>Order Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <img src="<?php echo $item['Image']; ?>" alt="<?php echo $item['Name']; ?>" class="product-image">
                                        <?php echo htmlspecialchars($item['Name']); ?>
                                    </div>
                                </td>
                                <td>
                                    x<?php echo $item['Quantity']; ?>
                                </td>
                                <td>
                                    $<?php echo number_format($item['Price'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </table>
                <div class="total-price">
                    Total: $<?php echo number_format($order['Total_Price'], 2); ?>
                </div>
                <div class="status-section">
                    <div>
                        <span>Current Status: </span>
                        <span class="current-status"><?php echo ucfirst(htmlspecialchars($order['Status'])); ?></span>
                    </div>
                    <button class="change-btn" onclick="openStatusModal()">Change Status</button>
                </div>
            </div>
        </div>

        <div id="statusModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Change Order Status</h3>
                    <button class="close-btn" onclick="closeStatusModal()">&times;</button>
                </div>
                <form id="statusForm">
                    <div class="form-group">
                        <label for="Select_Status">Select Status</label>
                        <select id="Select_Status" class="form-control" required>
                            <option value="pending" <?php echo ($order['Status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="ready" <?php echo ($order['Status'] == 'ready') ? 'selected' : ''; ?>>Ready</option>
                            <option value="cancelled" <?php echo ($order['Status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            <option value="completed" <?php echo ($order['Status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="barber_comments">Barber Notes</label>
                        <textarea id="barber_comments" class="form-control" placeholder="Add any notes here..."><?php echo htmlspecialchars($order['Barber_Comments'] ?? ''); ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="confirm-btn" onclick="openConfirmModal()">Continue</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirm Status Change</h3>
                </div>
                <div class="form-group">
                    <p>Are you sure you want to change the order status to <strong id="displayStatus"></strong>?</p>
                    <div id="commentsDisplay" style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
                        <strong>Barber Notes:</strong>
                        <p id="displayComments" style="margin: 5px 0 0 0;"></p>
                    </div>
                </div>
                <form id="confirmForm" method="POST" action="">
                    <div class="modal-footer">
                        <input type="hidden" name="new_status" id="new_status" value="">
                        <input type="hidden" name="barber_notes" id="barber_notes" value="">
                        <input type="hidden" name="confirm_change" value="1">

                        <button type="button" class="cancel-btn" onclick="closeConfirmModal()">Cancel</button>
                        <button type="submit" class="yes-btn">Yes</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openStatusModal() {
                document.getElementById('statusModal').style.display = 'block';
            }

            function closeStatusModal() {
                document.getElementById('statusModal').style.display = 'none';
            }

            function openConfirmModal() {
                const status = document.getElementById('Select_Status').value;
                const comments = document.getElementById('barber_comments').value;

                if (!status) {
                    alert("Please select a status.");
                    return;
                }

                // Update display elements
                document.getElementById('displayStatus').textContent =
                    document.querySelector('#Select_Status option:checked').textContent;
                document.getElementById('displayComments').textContent = comments || 'No notes provided';

                // Set form values
                document.getElementById('new_status').value = status;
                document.getElementById('barber_notes').value = comments;

                // Switch modals
                closeStatusModal();
                document.getElementById('confirmModal').style.display = 'block';
            }

            function closeConfirmModal() {
                document.getElementById('confirmModal').style.display = 'none';
                openStatusModal();
            }

            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    if (document.getElementById('statusModal').style.display === 'block') {
                        closeStatusModal();
                    }
                    if (document.getElementById('confirmModal').style.display === 'block') {
                        closeConfirmModal();
                    }
                }
            }
        </script>
</body>

</html>