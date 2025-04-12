<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/32/2025
Revisions:
     03/31/2025 -- Alexandra Stratton -- created employee_list.php
 Purpose: Allow the manager to see all the employees

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
$sql = "SELECT * FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$sql = "SELECT * FROM Barber_Information";
$result = $conn->query($sql);
$barbers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barbers[] = $row;
    }
}
//Remove employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_barber'])) {
    // Retrieve form data
    $barber_id= $_POST['barber_id'];
    //Figure out all tables that contain Barber_ID
    $sql = "DELETE Barber WHERE Barber_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $barber_id);
    $stmt->execute();
    header("Location: employees.php");
    exit();
}
?>
<?php 
echo $user['Role'];
if ($user['Role'] == "Barber") {
    header("Location: login.php");
    exit();
}
else {
    include("manager_header.php");
}
//Otherwise cause an error
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title for Page -->
    <title>Employee List</title>
    <!-- Internal CSS for styling the page -->
    <link rel="stylesheet" href="style/barber_style.css">
</head>
<body>
    
    <div class="container">
        <!-- Add Product Button at the Top Right -->
        <a href="add_employee.php" class="add-btn">Add Employee</a>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Barber ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barbers as $barber): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($barber['Barber_ID']); ?></td>
                            <td><?php echo htmlspecialchars($barber['First_Name']) . ' ' . htmlspecialchars($barber['Last_Name']);; ?></td>
                            <td><?php echo htmlspecialchars($barber['Email']); ?></td>
                            <td><?php echo htmlspecialchars($barber['Phone_Number']); ?></td>
                            <td>
                                <a href="edit_profile.php?Barber_ID=<?php echo $barber['Barber_ID']; ?>"><button class="edit-btn">Edit</button></a>
                                <button class="remove-btn" onclick="confirmDelete('<?php echo $barber['Barber_ID']; ?>', '<?php echo htmlspecialchars($barber['First_Name'] . ' ' . $barber['Last_Name']); ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Delete Confirmation -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Removal</h3>
            <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="form-group">
            <p>Are you sure you want to remove <strong id="displayBarberName"></strong>?</p>
            <p class="warning-text">This action cannot be undone!</p>
        </div>

        <form id="deleteForm" method="POST">
            <input type="hidden" name="barber_id" id="barber_id" value="">
            <input type="hidden" name="remove_barber" value="1">
            
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="yes-btn">Yes, Delete</button>
            </div>
        </form>
    </div>
        </div>
    <!-- Script for confirming deletion -->
    <script>
        function confirmDelete(barberId, barberName) {
            document.getElementById('barber_id').value = barberId;
            document.getElementById('displayBarberName').textContent = barberName;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>
