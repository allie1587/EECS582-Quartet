<!-- 
    clients.php
    A page for the barber to find any client information
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        3/2/2025 -- Kyle Moore, add menu buttons and list of clients from db
    Creation date: 3/2/2025
-->

<?php
include ("db_connection.php");
$query = "
    SELECT First_name, Last_name, Email, Phone, BarberID
    FROM Confirmed_Appointments
    ORDER BY First_name ASC;
";
// Execute the query
$result = $conn->query($query);
// Fetch all rows as an associative array
$clients = $result->fetch_all(MYSQLI_ASSOC);
// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style1.css">

    <title>All Clients</title>
    <style>
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
  </head>
  <body>
    <div class="menu">
      <button onclick="location.href='dashboard.php'">Dashboard</button>
      <button onclick="location.href='checkouts.php'">Checkouts</button>
      <button onclick="location.href='calendar.php'">Calendar</button>
      <button onclick="location.href='clients.php'">Clients</button>
      <button onclick="location.href='customize.php'">Customize</button>
      <button onclick="location.href='see_feedback.php'">Feedback</button>

    </div>
    <button onclick="location.href='index.php'">Back to Customer Site</button>
    <form method="post" action="logout.php">
      <button type="submit" name="logout">Logout</button>
    </form>

    <h1>Clients (By First Name)</h1>
    <!-- Add the search bar -->
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search by any column...">
    </div>
    <table id="clientTable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Barber</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['First_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['Last_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['Email']); ?></td>
                    <td><?php echo htmlspecialchars($client['Phone']); ?></td>
                    <td><?php echo htmlspecialchars($client['BarberID']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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