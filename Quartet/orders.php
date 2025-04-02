<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/32/2025
Revisions:
     03/31/2025 -- Alexandra Stratton -- created employee_list.php
 Purpose: Allow the manager to see all the employees

 -->
 <?php
// Connects to the database
require 'db_connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$error = "";
$success = "";

$sql = "SELECT * FROM Orders";
$result = $conn->query($sql);
$orders = [];
if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        $client_id = $order['Client_ID'];
        $clients= [];
        $stmt = $conn->prepare("SELECT First_Name, Last_Name FROM Client WHERE Client_ID = ?");
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $client_result = $stmt->get_result();

        // Fetch client data
        if ($client_result->num_rows > 0) {
            $order['client'] = $client_result->fetch_assoc();
        } else {
            $order['client'] = null;
        }

        $stmt->close(); 
        $orders[] = $order;
    }
}
?>

<?php 
//check if manager or barber
include('barber_header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title for Page -->
    <title>Orders</title>
    <!-- Internal CSS for styling the page -->
    <link rel="stylesheet" href="style1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .order-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .add-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .add-btn {
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
        .add-btn:hover {
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
        
        .dropdown-container {
            display: none;
            background-color: #262626;
            padding-left: 8px;
        }
    </style>
</head>
<body>
    <h1>Order</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <div class="order-container">
        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Client Name</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['Order_ID']; ?></td>
                        <td> <?php echo $order['client']['First_Name']  . ' '  . $order['client']['Last_Name']; ?> </td>
                        <td><?php echo $order['Created_At']; ?></td>
                        <td>$<?php echo $order['Total_Price']; ?></td>
                        <td>  
                             <?php echo $order['Status']; ?>
                        </td>
                        <td>
                            <a href="manage_orders.php?Order_ID=<?php echo $order['Order_ID']; ?>"><button class="btn view-btn">View</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
    </script>
</body>
</html>