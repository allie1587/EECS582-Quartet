<!-- 
    schedule.php
    A page to hold the appointment calendar and scheduler.
    Authors: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/25/2025 -- Brinley, add calendar
        2/27/2025 -- Brinley, add appointment button popups
    Creation date:
    Other sources: ChatGPT
-->
<?php
// Start the session to remember user info
session_start();
$conn = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Defines character encoding for proper text rendering -->
    <meta charset="UTF-8">
    <!-- Ensures proper rendering and touch zooming on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the page displayed in the browser tab -->
    <title>Schedule</title>

    <!-- Internal CSS for styling the page -->
    <style>
        /* Applies styles to the entire body */
        body {
            text-align: center; /* Centers text content */
            font-family: Arial, sans-serif; /* Sets the font */
        }

        /* Styles the top navigation bar */
        .top-bar {
            background-color: green; /* Sets background color to green */
            padding: 10px; /* Adds padding around the content */
            display: flex; /* Uses flexbox for layout */
            justify-content: space-between; /* Spaces elements evenly */
            align-items: center; /* Centers elements vertically */
            color: white; /* Sets text color to white */
            height: 50px; /* Sets the height of the bar */
        }

        /* Styles the heading inside the top bar */
        .top-bar h1 {
            margin: 0; /* Removes default margin */
            padding-left: 20px; /* Adds left padding */
            font-size: 24px; /* Sets font size */
        }

        /* Styles the container for the login button */
        .login-container {
            display: flex; /* Uses flexbox for layout */
            align-items: center; /* Aligns items in the center */
            padding-right: 20px; /* Adds right padding */
        }

        /* Styles the login button */
        .login-button {
            width: 40px; /* Sets button width */
            height: 40px; /* Sets button height */
            border-radius: 50%; /* Makes the button circular */
            background-color: #007BFF; /* Sets button background color */
            color: white; /* Sets button text color */
            border: none; /* Removes border */
            font-size: 16px; /* Sets font size */
            cursor: pointer; /* Changes cursor to pointer on hover */
            margin-left: 10px; /* Adds margin to the left */
            display: flex; /* Uses flexbox for layout */
            align-items: center; /* Centers content vertically */
            justify-content: center; /* Centers content horizontally */
        }

        /* Styles the menu section */
        .menu {
            margin-top: 20px; /* Adds space above the menu */
        }

        /* Styles buttons inside the menu */
        .menu button {
            margin: 5px; /* Adds space between buttons */
            padding: 10px 20px; /* Adds padding inside buttons */
            font-size: 16px; /* Sets font size */
            cursor: pointer; /* Changes cursor to pointer on hover */
        }

        /* Calendar styles */
        .calendar-container {
            margin-top: 30px;
            position: relative;
        }

        .month-name {
            font-size: 24px;
            font-weight: bold;
        }

        .calendar {
            margin: 15px;
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0px;
            text-align: center;
        }

        .day {
            position: relative; /* Ensures child elements are positioned relative to this */
            aspect-ratio: 1/.75;
            border: 1px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .day span {
            position: absolute;
            top: 5px;
            left: 5px;
            font-size: 14px;
            font-weight: bold;
            display: grid;
        }


        .dayHead {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 10px;
        }

        .calendar-nav {
            margin-top: 20px;
        }

        /* appointment button styling */
        .day-button {
            margin: 5px;
            cursor: pointer;
            width: 90%;
            padding: 5px;
        }

        /* Popup styling */
        .popup {
            display: none; /* Hidden by default */
            position: fixed;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1000;
        }

        /* Close button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Grid container inside the popup */
        .appointment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Adjust columns dynamically */
            gap: 10px;
            margin-top: 10px;
        }

        /* Individual appointment items */
        .appointment-item {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }

    </style>
</head>
<body>
    <!-- The top navigation bar containing the barbershop name and login button -->
    <div class="top-bar">
        <h1>Quartet's Amazing Barbershop</h1>
        <!-- Login button container -->
        <div class="login-container">
            <span>Login</span>
            <!-- Clicking this button redirects to the login page -->
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>

    <!-- Page title -->
    <h1>Schedule</h1>

    <!-- Navigation menu with buttons linking to different pages -->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='page4.html'">Page 4</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>

    <div class="calendar-container">
        <div class="month-name" id="monthName">
            <!-- Month Name will be displayed here -->
        </div>

        <div class="calendar">
            <!-- Days of the week -->
            <div class="dayHead">Sunday</div>
            <div class="dayHead">Monday</div>
            <div class="dayHead">Tuesday</div>
            <div class="dayHead">Wednesday</div>
            <div class="dayHead">Thursday</div>
            <div class="dayHead">Friday</div>
            <div class="dayHead">Saturday</div>

            <!-- Calendar Days will be dynamically generated here -->
        </div>

        <div class="calendar-nav">
            <button onclick="changeMonth(-1)">Previous</button>
            <button onclick="changeMonth(1)">Next</button>
        </div>

                <!-- Popup Modal -->
        <div id="appointmentPopup" class="popup">
            <div class="popup-content">
                <span class="close-btn">&times;</span>
                <h2>Available appointments for <span id="appointmentDay"></span></h2>
                <div id="appointmentGrid" class="appointment-grid"></div>
            </div>
        </div>

    </div>

    <script>
        // ChatGPT help start
        let currentMonth = new Date().getMonth(); // Current month (0-11)
        let currentYear = new Date().getFullYear(); // Current year
        let monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        let dayNames = ['Monday', "Tuesday", 'Wednesday', 'Thursday', 'Friday', "Saturday", 'Sunday'];

        // Function to render the calendar
        function renderCalendar() {
            // Get the first day of the month and the total number of days in the month
            let firstDay = new Date(currentYear, currentMonth, 1).getDay(); // First day of the month
            let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Number of days in the month

            // Update month name
            document.getElementById('monthName').innerHTML = `${monthNames[currentMonth]} ${currentYear}`;

            // Clear the previous calendar month
            let calendar = document.querySelector('.calendar');
            calendar.querySelectorAll('.day').forEach(day => day.remove());

            // Add empty divs for days before the 1st day of the month
            for (let i = 0; i < firstDay; i++) {
                let emptyDay = document.createElement('div');
                emptyDay.classList.add('day');
                calendar.appendChild(emptyDay);
            }

            // Add actual days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                let dayDiv = document.createElement('div');
                dayDiv.classList.add('day');

                // Create a span for the day number
                let dayNumber = document.createElement('span');
                dayNumber.textContent = day;

                // Create a button
                let button = document.createElement('button');

                // Get the weekday
                let weekday = new Date(currentYear, currentMonth, day-1).getDay();

                // Fetch appointment count from backend
                fetch(`countAppointments.php?year=${currentYear}&month=${currentMonth}&day=${day}&weekday=${weekday}`)
                    .then(response => response.json())
                    .then(data => {
                        button.textContent = `${data.count} Appointment(s) Found`;
                    })
                    .catch(error => {
                        console.error("Error fetching appointment count:", error);
                        button.textContent = "Error";
                    });
                
                button.addEventListener('click', () => {
                    openAppointmentInfo(day);
                });
                button.classList.add('day-button');

                // Append elements
                dayDiv.appendChild(dayNumber);
                dayDiv.appendChild(button);
                calendar.appendChild(dayDiv);
            }
        }

        // Function to change the month (either forward or backward)
        function changeMonth(direction) {
            currentMonth += direction;

            // If the month goes out of bounds, adjust the year and month
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }

            renderCalendar(); // Re-render the calendar for the new month
        }

        function addAppointments() {
            calendar.querySelectorAll('.day').forEach(day => {
                // if sql.has appointment
            });
        }

        // Sample appointment data (replace with SQL or API data)
        const appointmentsData = {
            1: ["Meeting @ 10 AM", "Doctor @ 3 PM"],
            2: ["Lunch with Sarah @ 12 PM"],
            5: ["Gym @ 6 AM", "Work Call @ 2 PM", "Dinner @ 7 PM"],
            // Add more appointments for other days
        };

        function openAppointmentInfo(day) {
            const popup = document.getElementById('appointmentPopup');
            const appointmentText = document.getElementById('appointmentText');
            const appointmentGrid = document.getElementById('appointmentGrid');
            const appointmentDay = document.getElementById('appointmentDay');

            // Update the popup title
            appointmentDay.textContent = `${dayNames[new Date(currentYear, currentMonth, day-1).getDay()]}, ${monthNames[currentMonth]} ${day}, ${currentYear}`;

            // Clear existing appointments
            appointmentGrid.innerHTML = "";

            // Get appointments for the selected day
            const appointments = getAppointments(day) || ["No appointments"];

            // Create grid items dynamically
            appointments.forEach(appointment => {
                let item = document.createElement('div');
                item.classList.add('appointment-item');
                item.textContent = appointment;
                appointmentGrid.appendChild(item);
            });

            // Show popup
            popup.style.display = 'block';
        }

        // Close popup when clicking X button
        document.querySelector('.close-btn').addEventListener('click', () => {
            document.getElementById('appointmentPopup').style.display = 'none';
        });


        function getAppointments(day) {
            appointmentDay.textContent = `${dayNames[new Date(currentYear, currentMonth, day-1).getDay()]}, ${monthNames[currentMonth]} ${day}, ${currentYear}`;
        }

        // Initial render
        renderCalendar();
        //ChatGPT end
    </script>
</body>
</html>
