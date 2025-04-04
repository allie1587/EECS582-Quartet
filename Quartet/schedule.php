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
        3/16/2025 -- Brinley, add search/filtering
        3/27/2025 -- Brinley, gray out days before current date
        3/28/2025 -- Brinley, gray out timeslots before current time
        4/2/2025 - Brinley, refactoring; fix Sunday button bug
    Creation date:
    Other sources: ChatGPT
-->
<?php
// Start the session to remember user info
session_start();

// connect to the database
require 'db_connection.php';

//get the common header
include('header.php');
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
    <link rel="stylesheet" href="style1.css">
    <!-- Internal CSS for styling the page -->
    <style>
        /* Applies styles to the entire body */

        /* Calendar styles */
        .calendar-container {
            background: rgba(223, 218, 218, 0.97);
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
            background:rgb(210, 207, 207);
        }

        .day {
            position: relative; /* Ensures child elements are positioned relative to this */
            aspect-ratio: 1/.75;
            background: rgb(252, 250, 250);
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
            background-color:rgb(143, 48, 55);
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
            background-color: #c4454d;
            color: black;
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
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(255, 255, 255);
            display: none; /* Start hidden */
        }

        .popup-content {
            background: white; /* Dark background */
            color: black;
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
            color: black;
        }


        /* Grid container inside the popup */
        .appointment-grid {
            background-color: rgba(248, 248, 248, 0.9);
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Adjust columns dynamically */
            gap: 10px;
            color: black;
            margin-top: 10px;
        }

        /* Individual appointment items */
        .appointment-item {
            background:rgb(246, 246, 246);
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
            background-color: #c4454d;;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: rgb(143, 48, 55);
        }

    </style>
</head>
<body>
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
            <button id="prevButton" onclick="changeMonth(-1)">Previous Month</button>
            <button id="nextButton" onclick="changeMonth(1)">Next Month</button>
            <!-- Week navigation buttons -->
            <button id="prevWeekButton" onclick="changeWeek(-1)" style="display: none;">Previous Week</button>
            <button id="nextWeekButton" onclick="changeWeek(1)" style="display: none;">Next Week</button>
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
        let currentTime = new Date().getHours();
        let selectedDate = new Date(); // Defaults to today
        let monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        let dayNames = ['Sunday', 'Monday', "Tuesday", 'Wednesday', 'Thursday', 'Friday', "Saturday"];
        let appointmentsData = [];

    // Function to render the calendar
    function renderCalendar(day=0, weekday=0) {
        currentTime = new Date().getHours();

        let prevButton = document.getElementById('prevButton'); //identifies button for switching months
        let nextButton = document.getElementById('nextButton'); //identifies button for switching months
        let prevWeekButton = document.getElementById('prevWeekButton'); //identifies button for switching weeks
        let nextWeekButton = document.getElementById('nextWeekButton'); //identifies button for switching weeks

        // Get the first day of the month and the total number of days in the month
        let firstDay = new Date(currentYear, currentMonth, 1).getDay(); // First day of the month
        let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Number of days in the month
        let daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate(); // Number of days in the previous month

        // Update month name
        document.getElementById('monthName').innerHTML = `${monthNames[currentMonth]} ${currentYear}`;

        // Clear the previous calendar month
        let calendar = document.querySelector('.calendar');
        calendar.querySelectorAll('.day').forEach(day => day.remove());

        if (monthView) {
            //show month switch buttons, remove week switch buttons
            prevButton.style.display = 'inline-block';
            nextButton.style.display = 'inline-block';
            prevWeekButton.style.display = 'none';
            nextWeekButton.style.display = 'none';
            // Add empty divs for days before the 1st day of the month
            for (let i = 0; i < firstDay; i++) {
                let emptyDay = document.createElement('div');
                emptyDay.classList.add('day');
                emptyDay.style.background = 'rgb(70, 70, 70)';
                calendar.appendChild(emptyDay);
            }

            // Add actual days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                let dayDiv = document.createElement('div');
                dayDiv.classList.add('day');

                // Create a span for the day number
                let dayNumber = document.createElement('span');
                dayNumber.textContent = day;

                // Gray out days before current date
                if ((day < currentDay && currentMonth <= new Date().getMonth()) || currentMonth < new Date().getMonth()) {
                    dayDiv.style.background = 'rgb(70, 70, 70)';
                    dayNumber.style.color = 'darkgray';
                } else {
                    // Show appointment buttons

                    // Create a button
                    let button = document.createElement('button');

                    // Get the weekday
                    let weekday = new Date(currentYear, currentMonth, day).getDay();

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
                        selectedDate = new Date(currentYear, currentMonth, day);
                        monthView = false;
                        renderCalendar(day, weekday);
                    });
                    button.classList.add('day-button');

                    // Append elements
                    dayDiv.appendChild(button);
                }
                
                dayDiv.appendChild(dayNumber);
                calendar.appendChild(dayDiv);
            }

        } else {
            let firstWeekDay = new Date(currentYear, currentMonth, day - weekday); // First day of the current week
            let lastWeekDay = new Date(currentYear, currentMonth, day - weekday + 6); // Last day of the current week
            //show week switch buttons, remove month switch buttons
            prevButton.style.display = 'none';
            nextButton.style.display = 'none';
            prevWeekButton.style.display = 'inline-block';
            nextWeekButton.style.display = 'inline-block';

            // Add actual days of the WEEK
            for (let offset = 0; offset < 7; offset++) {
                let tempMonth = currentMonth;
                let tempYear = currentYear
                let wday = day - weekday + offset; // Correct calculation for the week day
                let dayDiv = document.createElement('div');
                if (wday < 1){
                    wday += daysInPrevMonth;
                    tempMonth--;
                    if (tempMonth < 0) {
                        tempMonth = 11;
                        tempYear--;
                    }
                } else if (wday > daysInMonth) {
                    wday -= daysInMonth;
                    tempMonth++;
                    if (tempMonth > 11) {
                        tempMonth = 0;
                        tempYear++;
                    }
                }
                if (offset == weekday) { // if we're on the selected day
                    dayDiv.style.backgroundColor = "#c4454d"; // change background color to show it's selected
                }
                dayDiv.classList.add('day');

                // Create a span for the day number
                let dayNumber = document.createElement('span');
                dayNumber.textContent = wday;

                if ((wday < currentDay && tempMonth == new Date().getMonth()) || tempMonth < new Date().getMonth()) { //gray out days before current day
                    dayDiv.style.background = 'rgb(70, 70, 70)';
                    dayNumber.style.color = 'darkgray';
                } else {
                    //otherwise show appointments

                    // Fetch appointment count from backend
                    fetch(`get_appointments.php?year=${tempYear}&month=${tempMonth}&day=${wday}&weekday=${new Date(tempYear, tempMonth, wday).getDay()}`)
                        .then(response => response.json())
                        .then(data => {
                            // Reset appointments for this day
                            let appointments = data.length ? data : ["No appointments"];

                            // Create timeslots dynamically
                            appointments.forEach(appointment => {
                                let item = document.createElement('button');
                                if (appointment === "No appointments") {
                                    item.textContent = "No appointments";
                                } else {
                                    let time = (appointment.Time <= 12 ? appointment.Time : appointment.Time - 12);
                                    let period = (appointment.Time < 12 ? "AM" : "PM");
                                    item.textContent = time + period;
                                    
                                    // Change button color based on barber
                                    if (appointment.Barber_ID === "JL") {
                                        item.style.backgroundColor = "lightblue";
                                    } else if (appointment.Barber_ID === "kyle5") {
                                        item.style.backgroundColor = "lightgreen";
                                    } else if (appointment.Barber_ID === "kyle4") {
                                        item.style.backgroundColor = "lightcoral";
                                    } else {
                                        item.style.backgroundColor = "gray";
                                    }
                                    //only make appointment clickable if time is after current time
                                    if (appointment.Time <= currentTime && appointment.Day == currentDay) {
                                        item.disabled = true;
                                        item.style.backgroundColor = 'rgb(70, 70, 70)';
                                    }  
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

                    
                dayDiv.appendChild(dayNumber);
                // Append the dayDiv to the calendar before fetching data
                calendar.appendChild(dayDiv);
                if (offset == 0){
                    firstWeekDay = `${monthNames[tempMonth]} ${wday} `;
                } else if (offset == 6 ){
                    lastWeekDay = `${monthNames[tempMonth]} ${wday}`;
                }
            }
            document.getElementById('monthName').innerHTML = `${firstWeekDay} - ${lastWeekDay}`;
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
        function changeWeek(direction) { //need to fix
            selectedDate.setDate(selectedDate.getDate() + (7 * direction));

            // Update currentMonth and currentYear based on the new selectedDate
            currentMonth = selectedDate.getMonth();
            currentYear = selectedDate.getFullYear();

            // Re-render the calendar with the new selectedDate
            renderCalendar(selectedDate.getDate(), selectedDate.getDay());
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
            appointmentInfoPara.innerHTML = `Date: ${dayNames[new Date(currentYear, currentMonth, day).getDay()]}, ${monthNames[currentMonth]} ${day}, ${currentYear}
                                    \nTime: ${time}
                                    \nBarber: ${appointment.Barber_ID}`;
            appointmentGrid.appendChild(appointmentInfoPara);

            let bookButton = document.createElement('button');
            bookButton.textContent = "Book Appointment";
            bookButton.addEventListener('click', () => {
                bookAppointment(appointment, day, currentMonth, currentYear, time);
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
