<!-- 
    set_hours.php
    A page for the barber to set their hours.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/27/2025 -- Alexandra Stratton, add weekly calendar
        3/10/2025 -- Brinley, start revamp to be the barber set hours page. Change top "week of" format
        3/11/2025 -- Brinley, add searchable week
        3/29/2025 - Brinley, retrieve current availability
        4/2/2025 - Brinley, refactoring, fix Sunday start of week bug
        4/5/2025 - Brinley, fix weeks with mixed months
        4/6/2025 - Brinley, automatically set barber id based on who is logged in
    Creation date:
-->
<?php
session_start();
$dt = new DateTime;
if (isset($_GET['year']) && isset($_GET['week'])) {
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$week = $dt->format('W');
$dt->modify('-1 day');
$year = $dt->format('o');

$_SESSION["year"] = $year;
$_SESSION["week"] = $week;
$_SESSION["month"] = $dt->format("m");
$_SESSION["startDate"] = $dt->format("d");

$monthYear = $dt->format('m/d/y'); // Get the numerical date

include("barber_header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Barber Calendar</title>
    <style>
        /* Times grid styling */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 5px;
            margin: 20px;
            justify-items: center;
            align-items: center;
        }
        .time-label {
            justify-self: end;
            margin-right: 10px;
        }
        .calendar-table th {
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    
    <!-- calendar navigation with back and forward arrows, week of calendar date select. on edit/click, they reload the page with the new set date/week -->
    <div class="calendar-navigation">
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>">&#9664;</a>
        
        <!-- Week of --- clickable/editable date showing current week we're looking at on the calendar-->
        <h2>
            Week of <input type="text" id="dateInput" placeholder="<?php echo $monthYear?>" onkeypress="if(event.key==='Enter') jumpToDate();">
        </h2>
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>">&#9654;</a>
    </div>

    <form method="POST" id="calendarForm">
        <input type="text" value="<?php echo $_SESSION['username']?>" name="barber" id="barber_username">
        <button type="button" name="retrieve" onclick="retrieveAvailability()">Retrieve</button>
        <table class="calendar-table">
            <tr>
                <?php
                $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; // initialize days of the week list
                $startDayOfWeek = $dt->format('N'); 
                $startDate = clone $dt; 

                foreach ($daysOfWeek as $day) { //show the date on the head of each calendar week
                    echo '<th>' . $day . ', ' . $startDate->format('n/j') . '</th>';
                    $startDate->modify('+1 day');
                }
                ?>
            </tr>
            <?php
            /* Checkbox time grid */
            $times = range(8, 17); // make range of valid times
            foreach ($times as $hour) { // create each row of times and checkboxees
                $timeLabel = ($hour < 12) ? $hour . ' AM' : (($hour === 12) ? '12 PM' : ($hour - 12) . ' PM'); 
                echo '<tr><td class="time-label">' . $timeLabel . '</td>'; // show the time
                foreach (range(0, 6) as $day) { //create 7 checkboxes in line with the name of the day and hour for ease of database manipulation
                    echo '<td><input type="checkbox" name="' . $day . '-' . $hour . '" id="' . $day . '-' . $hour . '"></td>';
                }
                echo '</tr>';
            }
            ?>
        </table>
        <button type="submit" name="update" onclick="setFormAction('set_hours_db.php')">Update</button>
        <button type="submit" name="updateall" onclick="setFormAction('set_hours_db_all.php')">Update Reccurring</button>
    </form>
</body>
<script>
        function retrieveAvailability() {
            // get the barber's current availability
            

            // get barber
            let barber = document.getElementById("barber_username").value;

            // get the current available appointments for the barber and week
            fetch('retrieve_appointments.php?barber=' + encodeURIComponent(barber) + "&year=" + encodeURIComponent(<?php echo json_encode($year); ?>) + "&week=" + encodeURIComponent(<?php echo json_encode($week); ?>))
            .then(response => response.json())
            .then(data => {
                console.log("Appointments data:", data);
                document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                    checkbox.checked = false; // Uncheck all first
                });

                // check the checkboxes for corresponding found appointments
                data.forEach(appointment => {
                    //find checkbox who has the same name as the appointment weekday
                    let checkbox = document.getElementById(`${appointment.Weekday}-${appointment.Time}`);

                    // if weekday not set, find the weekday by using the month day and year
                    if (appointment.Weekday == -1) {
                        let date = new Date(appointment.Year, appointment.Month, appointment.Day); 
                        checkbox = document.getElementById(`${date.getDay()}-${appointment.Time}`);
                    }

                    // if checkbox with said name exists, check it
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            })
            .catch(error => console.error("Error fetching appointments:", error));
        }
        function jumpToDate() {
            const dateInput = document.getElementById("dateInput").value; // get the value the user input
            const split = dateInput.split("/");// split the elements by the slash
            let month, day, year;

            // set the month, day, and year
            if (split.length === 3) {
                // If input contains month, day, and year (m/d/yyyy)
                [month, day, year] = split.map(Number);
            } else if (split.length === 2) {
                // If input contains only month and day (m/d), use current year
                [month, day] = split.map(Number);
                year = new Date().getFullYear();
            } else {
                alert("Invalid date format. Please use m/d or m/d/yyyy.");
                return;
            }
            
            const date = new Date(year, month-1, day+1); //create a new date object
            const weekNumber = getISOWeekNumber(date); // calculate the week
            window.location.href = `?week=${weekNumber}&year=${year}`; // reset the calender
        }
        function setFormAction(action) {
            const form = document.getElementById("calendarForm");
            form.action = action;
            form.submit(); // Manually submit the form after setting the action
        }
        // calculate week accurately
        function getISOWeekNumber(date) {
            const tempDate = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNumber = (tempDate.getUTCDay() + 6) % 7; // Monday = 0, Sunday = 6
            tempDate.setUTCDate(tempDate.getUTCDate() - dayNumber + 3);
            const firstThursday = new Date(Date.UTC(tempDate.getUTCFullYear(), 0, 4));
            const weekNumber = 1 + Math.round(((tempDate - firstThursday) / 86400000 - 3 + ((firstThursday.getUTCDay() + 6) % 7)) / 7);
            return weekNumber;
        }
        
        retrieveAvailability();
    </script>
</html>
