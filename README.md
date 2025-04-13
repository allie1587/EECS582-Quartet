# EECS582-Quartet
Welcome to Quartet

Website: www.quartet.infinityfreeapp.com

Website code can be found in the Quartet folder

Login (Test)
```
Username: kyle5
Password: moore5

you can also create your own account and sign in using the username and passowrd you created
```
require 'db_connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$barber_id = $_SESSION['username'];
$sql = "SELECT Barber_Information.Role FROM Barber_Information WHERE Barber_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barber_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();


<?php 
if ($role == "Barber") {
    include("barber_header.php");
}
else {
    include("manager_header.php");
}
?>

        .content-wrapper {
            transition: margin-left 0.3s ease;
            margin-left: 10px;
        }

        .sidebar-active .content-wrapper {
            margin-left: 300px; 
        }

        .sidebar-deactive .content-wrapper {
            margin-left: 10px; 
        }

    <div class="content-wrapper">
    <br><br>

    </div>
