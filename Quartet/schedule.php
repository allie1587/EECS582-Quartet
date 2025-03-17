<!-- 
    schedule.php
    A page to hold the appointment calendar and scheduler.
    Authors: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/25/2025 -- Brinley, add calendar
        2/27/2025 -- Brinley, add appointment button popups
        2/28/2025 -- Brinley, add timeslots and populate appointment details
        3/1/2025  -- Jose, Stylizing Choices to page
        3/1/2025 -- Brinley, add confirm appointment abilities
        3/2/2025 -- Ben, added 'search' feature
        3/2/2025 -- Allie upcoming and past appointments
        03/02/2025 -- Jose Leyba, Changed Style of Calendar + Cancelation button
        3/14/2025 -- Brinley, Add week view
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
            margin: 0;
            padding-top: -5px;
            text-align: center;
            font-family: 'Georgia', serif; 
            background-color:rgba(36, 35, 35, 0.97);
            color: white;
        }
        /* Top Bar at Top with Pages and Login */
        .top-bar {
            background-color: #006400; /* Darker green */
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 70px; /* Increased height */
            position: relative; /* Changed from fixed to relative */
        }
        /* Size of Letters on it's header */
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
            font-size: 28px;
        }
        /* Space for the login button on the right */
        .login-container {
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
        /* Login Button Format*/
        .login-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Style for the Menu taht will have the navigation buttons */
        .menu {
            display: flex;
            flex-grow: 1;
            justify-content: center;
            height: 100%;
        }
        /* Style of Navigation Buttons */
        .menu button {
            background-color: #006400; 
            color: white;
            border: none;
            padding: 20px 25px; 
            font-size: 18px;
            cursor: pointer;
            flex-grow: 1;
            text-align: center;
            font-family: 'Georgia', serif; 
        }
        /* Color gets darker when hovering the buttons */
        .menu button:hover {
            background-color: #004d00; 
        }
        /* Calendar styles */
        .calendar-container {
            background: rgba(36, 35, 35, 0.97);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }

        .month-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .calendar {
            margin: 15px;
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
            background:rgb(42, 39, 39);
        }

        .day {
            position: relative; /* Ensures child elements are positioned relative to this */
            aspect-ratio: 1/.75;
            background:rgb(56, 51, 51);
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
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
            background-color:rgb(1, 77, 1);
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
        }

        .calendar-nav {
            margin-top: 15px;
        }

        /* appointment button styling */
        .day-button {
            background-color:rgba(40, 167, 70, 0.74);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        /* Popup styling */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #333;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            display: none; /* Start hidden */
        }

        .popup-content {
            background: #333; /* Dark background */
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            text-align: center;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: white;
        }


        /* Grid container inside the popup */
        .appointment-grid {
            background-color: rgba(50, 50, 50, 0.9);;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Adjust columns dynamically */
            gap: 10px;
            margin-top: 10px;
        }

        /* Individual appointment items */
        .appointment-item {
            background:rgb(39, 37, 37);
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .search-container input,
        .search-container select,
        .search-container button {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
        }

        .search-container button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }
        .cancel-alert {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color:rgb(220, 94, 90);
            background-color:rgb(111, 39, 45);
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #d9534f;
            display: inline-block;
        }

    </style>
</head>
<body>
    <!-- The top navigation bar containing the barbershop name and login button -->
    <div class="top-bar">
        <!--Name of Page followed by Navigation Bar of The pages-->
        <h1>Quartet's Barbershop</h1>
        <div class="menu">
            <button onclick="location.href='index.php'">Home</button>
            <button onclick="location.href='schedule.php'">Schedule</button>
            <button onclick="location.href='store.php'">Store</button>
            <button onclick="location.href='barbers.php'">Barbers</button>
            <button onclick="location.href='about.php'">About Us</button>
            <button onclick="location.href='feedback.php'">Contact us</button>

        </div>

        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>

    <!-- Page title -->
    <h1>Schedule</h1>
    
    <!-- Search Feature -->
    <div class="search-container">
        <input type="text" id="barberSelect" placeholder="Select Barber">

        <select id="timeSelect">
            <option value="">Select Time</option>
            <option value="8">8:00 AM</option>
            <option value="9">9:00 AM</option>
            <option value="10">10:00 AM</option>
            <option value="11">11:00 AM</option>
            <option value="12">12:00 PM</option>
            <option value="13">1:00 PM</option>
            <option value="14">2:00 PM</option>
            <option value="15">3:00 PM</option>
            <option value="16">4:00 PM</option>
            <option value="17">5:00 PM</option>
        </select>

        <button onclick="search()">Search</button>
    </div>
    <!-- End Search Feature -->

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
                <h2><span id="appointmentDay"></span></h2>
                <div id="appointmentGrid" class="appointment-grid"></div>
            </div>
        </div>
    </div>
    <br>
    <div class="cancel-alert">Need to cancel an appointment? </div>
        <br>
        <a href="cancel_appointment.php">Cancel here</a>
        <br><br>

    </div>

<script>

    let monthView = true;

        function search() {
            let barber = document.getElementById("barberSelect").value ? document.getElementById("barberSelect").value : null;

            let time = document.getElementById("timeSelect").value ? document.getElementById("timeSelect").value : null;

            fetch('set_filter.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ filter: true,
                                    barber: barber,
                                    time: time
                })
            }).then(response => response.text())
            .then(data => {
                renderCalendar();
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        // ChatGPT help start
        let currentMonth = new Date().getMonth(); // Current month (0-11)
        let currentYear = new Date().getFullYear(); // Current year
        let currentDay = new Date().getDate();
        let currentWeekday = new Date().getDate();
        let monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        let dayNames = ['Monday', "Tuesday", 'Wednesday', 'Thursday', 'Friday', "Saturday", 'Sunday'];
        let appointmentsData = [];

    // Function to render the calendar
    function renderCalendar(day=0, weekday=0) {
        // Get the first day of the month and the total number of days in the month
        let firstDay = new Date(currentYear, currentMonth, 1).getDay(); // First day of the month
        let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Number of days in the month

        // Update month name
        document.getElementById('monthName').innerHTML = `${monthNames[currentMonth]} ${currentYear}`;

        // Clear the previous calendar month
        let calendar = document.querySelector('.calendar');
        calendar.querySelectorAll('.day').forEach(day => day.remove());

        if (monthView) {

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
                fetch(`get_appointments.php?year=${currentYear}&month=${currentMonth}&day=${day}&weekday=${weekday}`)
                    .then(response => response.json())
                    .then(data => {
                        appointmentsData = data;
                        button.textContent = `${appointmentsData.length} Appointment(s) Found`;
                    })
                    .catch(error => {
                        console.error("Error fetching appointment count:", error);
                        button.textContent = "Error";
                    });
                
                button.addEventListener('click', () => {
                    monthView = false;
                    renderCalendar(day, weekday);
                });
                button.classList.add('day-button');

                // Append elements
                dayDiv.appendChild(dayNumber);
                dayDiv.appendChild(button);
                calendar.appendChild(dayDiv);
            }

        } else {
            if (day-weekday < 0) {
                // Add empty divs for days before the 1st day of the month
                // CHANGE THIS TO ACTUALLY SHOW THE LAST DAYS OF THE PREVIOUS MONTH
                for (let i = 0; i < weekday; i++) {
                    // let daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
                    let emptyDay = document.createElement('div');
                    emptyDay.classList.add('day');
                    calendar.appendChild(emptyDay);
                }
            }
            

            // Add actual days of the WEEK
            for (let offset = 0; offset < 7; offset++) {
                let wday = day - weekday + offset -1; // Correct calculation for the week day
                let dayDiv = document.createElement('div');
                if (offset == weekday+1) { // if we're on the selected day
                    dayDiv.style.backgroundColor = "gray"; // change background color to show it's selected
                }
                dayDiv.classList.add('day');

                // Create a span for the day number
                let dayNumber = document.createElement('span');
                dayNumber.textContent = wday;
                dayDiv.appendChild(dayNumber);

                // Append the dayDiv to the calendar before fetching data
                calendar.appendChild(dayDiv);

                // Fetch appointment count from backend
                fetch(`get_appointments.php?year=${currentYear}&month=${currentMonth}&day=${wday}&weekday=${new Date(currentYear, currentMonth, wday - 1).getDay()}`)
                    .then(response => response.json())
                    .then(data => {
                        // Reset appointments for this day
                        let appointments = data.length ? data : ["No appointments"];

                        // Create grid items dynamically
                        appointments.forEach(appointment => {
                            let item = document.createElement('button');
                            if (appointment === "No appointments") {
                                item.textContent = "No appointments";
                            } else {
                                let time = (appointment.Time <= 12 ? appointment.Time : appointment.Time - 12);
                                let period = (appointment.Time < 12 ? "AM" : "PM");
                                item.textContent = time + period;

                                // Add click event to show appointment details
                                item.addEventListener('click', () => {
                                    openAppointmentInfo(appointment, wday);
                                });
                            }
                            item.classList.add('day-button');
                            dayDiv.appendChild(item);
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching appointment count:", error);
                    });
            }
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

        function openAppointmentInfo(appointment, day) {
            // Appointment information popup for a specific timeslot
            const popup = document.getElementById('appointmentPopup');
            const appointmentGrid = document.getElementById('appointmentGrid');
            const appointmentDay = document.getElementById('appointmentDay');
            const time = (appointment.Time <= 12 ? appointment.Time : appointment.Time-12) + (appointment.Time < 12 ? "AM" : "PM");

            // Clear the popup
            appointmentGrid.innerHTML = "";

            // Currently the title, but has all appointment information populated from sent in appointment. e.g. appointment.Time, appointment.BarberID, appointment.[columnName from database]
            // BarberID needs to be changed in database to actually be the ID and reference the barber information table to get the name.
            appointmentDay.textContent = `Selected Appointment`;
            let appointmentInfoPara = document.createElement('p');
            appointmentInfoPara.innerHTML = `Date: ${dayNames[new Date(currentYear, currentMonth, day-1).getDay()]}, ${monthNames[currentMonth]} ${day}, ${currentYear}
                                    \nTime: ${time}
                                    \nBarber: ${appointment.BarberID}`;
            appointmentGrid.appendChild(appointmentInfoPara);

            let bookButton = document.createElement('button');
            bookButton.textContent = "Book Appointment";
            bookButton.addEventListener('click', () => {
                bookAppointment(appointment, day, monthNames[currentMonth], currentYear, time);
            });

            appointmentGrid.appendChild(bookButton);

            // Show popup
            popup.style.display = 'block';

        }

        function bookAppointment(appointment, day, month, year, time) {
            // Function to show client a space to add their information and confirm their appointment.
            fetch('set_appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ appointment: appointment,
                                        day: day,
                                        month: month,
                                        year: year,
                                        time: time
                 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "confirm_appointment.php"; // Redirect after session is set
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch(error => console.error("Fetch error:", error));
        
        }

        // Close popup when clicking X button
        document.querySelector('.close-btn').addEventListener('click', () => {
            document.getElementById('appointmentPopup').style.display = 'none';
        });

        // Initial render
        renderCalendar();
        //ChatGPT end
    </script>
</body>
</html>
