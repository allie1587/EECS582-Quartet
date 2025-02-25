<!-- 
    schedule.php
    A page to hold the appointment calendar and scheduler.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/25/2025 -- Brinley, add calendar
    Creation date:
-->

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
            margin-top: 20px;
        }

        .month-name {
            font-size: 24px;
            font-weight: bold;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
        }

        .day {
            border-style: solid;
            border-width: 1.5px;
            padding: 20px;
            font-size: 16px;
        }

        .dayHead {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 10px;
        }

        .calendar-nav {
            margin-top: 20px;
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
    </div>

    <script>
        let currentMonth = new Date().getMonth(); // Current month (0-11)
        let currentYear = new Date().getFullYear(); // Current year
        let monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Function to render the calendar
        function renderCalendar() {
            // Get the first day of the month and the total number of days in the month
            let firstDay = new Date(currentYear, currentMonth, 1).getDay(); // First day of the month
            let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Number of days in the month

            // Update month name
            document.getElementById('monthName').innerHTML = `${monthNames[currentMonth]} ${currentYear}`;

            // Clear the previous calendar days
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
                dayDiv.textContent = day;
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

        // Initial render
        renderCalendar();
    </script>
</body>
</html>
