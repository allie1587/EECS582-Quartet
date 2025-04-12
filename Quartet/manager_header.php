<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/28/2025
Revisions:
     03/38/2025 -- Alexandra Stratton -- created manager_header.php
 Purpose: the header for the manager side.

 -->
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  font-family: "Lato", sans-serif;
  transition: margin-left 0.5s;
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}
.sidenav a, .dropdown-btn {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  color: white;
  display: block;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
}

.sidenav p {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  color: white;
  display: block;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
}

.sidenav a:hover, .dropdown-btn:hover {
  background-color: red;
}

.active {
  background-color: red;
  color: white;
}

.dropdown-container {
  display: none;
  background-color: #262626;
  padding-left: 8px;
}

.fa-caret-down {
  float: right;
  padding-right: 8px;
}

.closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
  background-color: #111;
  color: white;
  cursor: pointer;
  border: none;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

/* Some media queries for responsiveness */
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
</style>
</head>
<body>
<div class="barber" id="barber">
    <div class="sidenav" id="sideNav">
        <button class="closebtn" onclick="closeNav()">&times;</button>
        <a href="dashboard.php">Dashboard</a>
        <a href="calendar.php">Appointments</a>
        <a href="product.php">Products</a>
        <a href="orders.php">Orders</a>
        <button class="dropdown-btn">Clients 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="client.php">Client List</a>
            <a href="see_feedback.php">Feedback</a>
            <a href="#">Reviews</a>

        </div>
        <button class="dropdown-btn">Employees 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="employees.php">Employee List</a>
            <a href="#">Employee Services</a>
            <a href="#">Employee Hours</a> 
        </div>
        <a href="logout.php"><i class="fa fa-sign-out"></i> Log Out</a>
    </div>
</div>
<div id="content">
  <button class="openbtn" onclick="openNav()">☰</button>  
</div>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}

function openNav() {
  document.getElementById("sideNav").style.width = "250px";
  document.getElementById("content").style.marginLeft = "250px";
  document.querySelector(".openbtn").style.display = "none";
  

}

function closeNav() {
  document.getElementById("sideNav").style.width = "0";
  document.getElementById("content").style.marginLeft = "0";
  document.querySelector(".openbtn").style.display = "block";
}
</script>
</body>
</html>