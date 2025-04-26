<?php
/*
form_check.php
A script to check whether forms are empty and redirect if they are.
Authors: Brinley Hull, Allie Stratton, Jose Leyba, Ben Renner, Kyle Moore
Creation date: 4/25/2025
Revisions:
Preconditions: None
Postconditions: None
Error conditions: None
Side effects: None
Invariants: None
Any known faults: None
*/

if (!array_filter($_POST)) {
    header("Location: index.php");
}
?>