<?php
/*
set_filter.php
Sets the filter SESSION variables for the calendar filter.
Authors: Ben Renner, Kyle Moore, Alexandra Stratton, Jose Leyba, Brinley Hull
Creation date: 3/16/2025
*/
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input from the fetch request
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['filter'])) {
        $_SESSION["barberFilter"] = $data['barber'];
        $_SESSION['timeFilter'] = $data['time'];
        echo json_encode(["status" => "success"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
