<?php
/*
set_appointments.php 
A program to set appointment-related session variables.
Authors: Brinley Hull, Alexandra Stratton, Kyle Moore, Ben Renner, Jose Leyba
Revisions:
    3/16/2025 - Brinley, add filtering
Creation date: 3/1/2025
*/
session_start(); // start the session to set session variables

// check that the server request method was post rather than going to it by manual url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input from the fetch request
    $data = json_decode(file_get_contents("php://input"), true);
    
    // set appointment variables
    if (isset($data['appointment'])) {
        $_SESSION["appointment"] = $data['appointment'];
        $_SESSION["day"] = $data['day'];
        $_SESSION["month"] = $data['month'];
        $_SESSION["year"] = $data['year'];
        $_SESSION["time"] = $data['time'];
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No appointment data received"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
