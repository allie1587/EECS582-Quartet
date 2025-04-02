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


$sql = "SELECT * FROM Barber_Information";
$result = $conn->query($sql);
$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}
?>
<?php include('barber_header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title for Page -->
    <title>Employee List</title>
    <!-- Internal CSS for styling the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .employee-container {
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
        img {
            max-width: 80px;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .edit-btn {
            background: #007BFF;
            color: white;
        }
        .edit-btn:hover {
            background: #0056b3;
        }
        .delete-btn {
            background: #FF6A13;
            color: white;
        }
        .delete-btn:hover {
            background: #FF8A3D;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            color: black;
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
            border-radius: 10px;
            color: black;
        }
        .close {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover {
            color: black;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="employee-container">
        <!-- Add Product Button at the Top Right -->
        <div class="add-btn-container">
            <a href="add_employee.php" class="add-btn">Add Employee</a>
        </div>

        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee['Barber_ID']; ?></td>
                        <td><?php echo $employee['First_Name']; ?></td>
                        <td><?php echo $employee['Last_Name']; ?></td>
                        <td><?php echo $employee['Email']; ?></td>
                        <td><?php echo $employee['Phone_Number']; ?></td>
                        <td>
                            <a href="edit_profile.php?Barber_ID=<?php echo $employee['Barber_ID']; ?>"><button class="btn edit-btn">Edit</button></a>
                            <button class="btn delete-btn" onclick="confirmDelete('<?php echo $employee['Barber_ID']; ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Delete Confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Are you sure you want to remove this employee?</h2>
            <button class="btn delete-btn" id="confirmDeleteBtn">Yes</button>
            <button class="btn" onclick="closeModal()">No</button>
        </div>
    <!-- Script for confirming deletion -->
    <script>
        function confirmDelete(employeeId) {
            document.getElementById('confirmDeleteBtn').setAttribute('onclick', `window.location.href='remove_employee.php?Barber_ID=${employeeId}'`);
            document.getElementById('deleteModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>