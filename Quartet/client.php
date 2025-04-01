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


$sql = "SELECT * FROM Client";
$result = $conn->query($sql);
$clients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}
?>
<?php include('barber_header.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title for Page -->
    <title>Client List</title>
    <!-- Internal CSS for styling the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .client-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        
    </style>
</head>
<body>
    <h1>Client List</h1>
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search by client...">
    </div>
    <div class="client-container" id="clientTable">
        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['Client_ID']; ?></td>
                        <td><?php echo $client['First_Name']; ?></td>
                        <td><?php echo $client['Last_Name']; ?></td>
                        <td><?php echo $client['Email']; ?></td>
                        <td><?php echo $client['Phone']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
      // JavaScript for filtering the table
      document.getElementById("searchInput").addEventListener("input", function() {
        const filter = this.value.toLowerCase(); // Get the search term and convert to lowercase
        const rows = document.querySelectorAll("#clientTable tbody tr"); // Get all table rows

        rows.forEach(row => {
          const cells = row.querySelectorAll("td"); // Get all cells in the row
          let match = false;

          cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(filter)) {
              match = true; // If any cell matches the search term, mark the row as a match
            }
          });

          // Show or hide the row based on whether it matches the search term
          row.style.display = match ? "" : "none";
        });
      });
    </script>
</body>
</html>