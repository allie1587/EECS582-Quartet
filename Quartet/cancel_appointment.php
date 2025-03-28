<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointment</title>
    <style>
        body {
            margin: 0;
            padding-top: 70px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color: rgba(50, 50, 50, 0.86); 
            color: white;
        }
        .info_form {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        input[type="number"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            background-color: #444;
            color: white;
            border: 1px solid #666;
            border-radius: 5px;
        }
        button {
            background-color: #008000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background-color: #00A000;
        }
        #appointmentDetails {
            margin-top: 15px;
            font-size: 16px;
        }
        #cancelButton {
            display: none; /* Initially hidden */
            margin: 20px auto; /* Center the button */
            text-align: center;
            width: fit-content;
        }
    </style>
<<<<<<< HEAD
    <script>
        function fetchAppointment() {
            let appointmentID = document.getElementById("appointmentID").value;
            
            fetch("fetch_appointment.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ appointmentID: appointmentID })
            })
            .then(response => response.json())
            .then(data => {
                let appointmentDiv = document.getElementById("appointment_info");

                if (data.success) {
                    appointmentDiv.innerHTML = `
                        <p><strong>Service:</strong> ${data.service}</p>
                        <p><strong>Date:</strong> ${data.date}</p>
                        <p><strong>Time:</strong> ${data.time}</p>
                        <p><strong>Barber:</strong> ${data.barber}</p>
                        <form action="cancel.php" method="POST">
                            <input type="hidden" name="appointmentID" value="${appointmentID}">
                            <button type="submit">Cancel Appointment</button>
                        </form>
                    `;
                } else {
                    appointmentDiv.innerHTML = "<p style='color: red;'>No appointment found!</p>";
                }
            });
=======
    <script> 
        function fetchAppointment() { //used to fetch appointments when user enters an appointment id
            let appointmentID = document.getElementById("appointmentID").value;
            if (appointmentID === "") {
                document.getElementById("appointmentDetails").innerHTML = "<p style='color: red;'>❌ Please enter an Appointment ID.</p>";
                document.getElementById("cancelButton").style.display = "none"; // Hide cancel button if no ID
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_appointment.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("appointmentDetails").innerHTML = xhr.responseText;
                    if (!xhr.responseText.includes("❌ No appointment found")) {
                        document.getElementById("cancelButton").style.display = "block"; // Show cancel button if appointment found
                        document.getElementById("hiddenAppointmentID").value = appointmentID; // Pass ID to cancel form
                    } else {
                        document.getElementById("cancelButton").style.display = "none"; // Hide cancel button if no appointment found
                    }
                }
            };
            xhr.send("appointmentID=" + appointmentID);
>>>>>>> 15bc41f3937c3da23e289e5c5e298a0e73b632ed
        }
    </script>
</head>
<body>

    <h1>Cancel Appointment</h1>

    <div class="info_form">
        <label for="appointmentID">Appointment ID:</label><br>
        <input type="number" id="appointmentID" name="appointmentID" required><br><br>
        
        <button type="button" onclick="fetchAppointment()">Find Appointment</button>
        
        <div id="appointmentDetails"></div> <!-- Appointment info will appear here -->

<<<<<<< HEAD
    <div class="info_form">
        <label for="appointmentID">Enter Appointment ID:</label>
        <input type="text" id="appointmentID" required>
        <button onclick="fetchAppointment()">Search</button>
    </div>

    <div class="appointment_info">
        <p id="appointment_info"></p>
    </div>
=======
        <form action="cancel.php" method="POST">
            <input type="hidden" id="hiddenAppointmentID" name="appointmentID">
            <button type="submit" id="cancelButton">Cancel Appointment</button>
        </form>
    </div>

>>>>>>> 15bc41f3937c3da23e289e5c5e298a0e73b632ed
</body>
</html>
