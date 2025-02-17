<!-- 
 readme.php
 Basic readme page for the original node.js project that shows connection to the database.
 Authors: Alexandra Stratton, Brinley Hull, Kyle Moore, Ben Renner, Jose Leyba
 Creation date: 2/13/2025
 Revision date: 2/13/2025 - Brinley, copying the original readme.html and adding database information
 Preconditions:
    None
 Postconditions:
    None
 Error/exception conditions:
    Database doesn't connect -- database timeout
 Side effects:
    None
 Invariants:
    None
 Known faults:
    None
-->

<?php
// setting the database timeout times to a minute
ini_set('mysql.connect_timeout', 60); 
ini_set('default_socket_timeout', 60);
// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to MySQL
    $conn = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
    
    echo "Success! Connected to MySQL.";

    // Query the database for the users
    $query = "SELECT * FROM Users";
    $result = $conn->query($query);

    // Display data
    echo "<table>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

} catch (mysqli_sql_exception $e) {
    die("Database Error: " . $e->getMessage());  // Display the error message
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello Quartet</title>
</head>
<body>
    <h1>Welcome to Quartet</h1>
    <p>istall node.js</p>
    <p>cd to EECS582-QUARTET</p>
    <p>install npm</p>
    <p>in terminal type: node server.js</p>
    <p>to open: http://localhost:3000</p>
    <p>ctrl+c in terminal to terminate</p>
    <p>if install npm is blocked then: Get-ExecutionPolicy<br>
        this should be restricted<br>
        Set-ExecutionPolicy RemoteSigned -Scope CurrentUser<br>
        this should allow you to npm install and everything should work
    </p>
    
    <!--
    used to test saving selections by the user
    <select id="date">
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
    </select>
    <button id="saveButton">Confirm Appointment</button>
    <script src="scripts/save-selections.js"></script>
    -->

</body>
</html>
