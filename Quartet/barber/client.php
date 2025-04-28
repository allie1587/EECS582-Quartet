<!--
client.php
Page displays a list of all clients with search functionality
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/30/2025
Revisions:
    03/31/2025 -- Alexandra Stratton -- created employee_list.php
    04/10/2025 -- Alexandra Stratton -- removed the styling, added
    04/26/2025 -- Alexandra Stratton -- Error Checking
Preconditions
    Acceptable inputs: All
    Unacceptable inputs: None
    Required Access: User must be logged in and have appropriate role permissions
Postconditions:
    None
Error conditions:
    Database issues
Side effects
    None
Invariants
    None
Known faults:
    None
 -->

 <?php
session_start();
require 'db_connection.php';
require 'login_check.php';
require 'role_check.php';
require 'config.php';

if (!isset($conn)) {
    die("No database connection");
}

// Check if search parameter exists
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchTerm) {
    // For searching encrypted fields
    $searchTermEncrypted = DataEncryptor::encrypt($searchTerm);
    
    // Search in encrypted fields only (excluding phone)
    $sql = "SELECT * FROM Client WHERE 
            First_Name LIKE CONCAT('%', ?, '%') OR
            Last_Name LIKE CONCAT('%', ?, '%') OR
            Email LIKE CONCAT('%', ?, '%') OR
            Phone LIKE CONCAT('%', ?, '%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", 
        $searchTermEncrypted, 
        $searchTermEncrypted, 
        $searchTermEncrypted, 
        $searchTerm); // Note: phone search uses unencrypted term
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Get all clients if no search term
    $sql = "SELECT * FROM Client";
    $result = $conn->query($sql);
}

if (!$result) {
    die("Database error");
}

$clients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['First_Name'] = DataEncryptor::decrypt($row['First_Name']);
        $row['Last_Name'] = DataEncryptor::decrypt($row['Last_Name']);
        $row['Email'] = DataEncryptor::decrypt($row['Email']);
        // Phone remains as-is not decrypted to avoid bugs
        $clients[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client List</title>
    <link rel="stylesheet" href="style/barber_style.css">
</head>
<body>
    <div class="content-wrapper">
        <br><br>
        <h1>Client List</h1>
        <div class="search-bar">
            <form method="GET" action="client.php">
                <input type="text" name="search" id="search-input" placeholder="Search by..." 
                       value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="container">
            <div class="card">
                <table id="clientTable"> 
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($client['Client_ID']); ?></td>
                                <td><?php echo htmlspecialchars($client['First_Name']) . ' ' . htmlspecialchars($client['Last_Name']); ?></td>
                                <td><?php echo htmlspecialchars($client['Email']); ?></td>
                                <td><?php echo htmlspecialchars($client['Phone']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>