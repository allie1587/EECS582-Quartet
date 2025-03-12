<!-- 
    set_hours
    A page for the barber to set their hours.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/27/2025 -- Alexandra Stratton, add weekly calendar
        3/10/2025 -- Brinley, start revamp to be the barber set hours page. Change top "week of" format
        3/11/2025 -- Brinley, add searchable week
    Creation date:
-->
<?php
$dt = new DateTime;
if (isset($_GET['year']) && isset($_GET['week'])) {
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$year = $dt->format('o');
$week = $dt->format('W');


$monthYear = $dt->format('m/d/y'); // Get the numerical date
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber Calendar</title>
    <style>
      
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

    <table class="calendar-table">
        <tr>
            <?php
            $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; // initialize days of the week list
            $startDayOfWeek = $dt->format('N'); 
            $startDate = clone $dt; 
            $startDate->modify('-' . ($startDayOfWeek - 1) . ' days');

            foreach ($daysOfWeek as $day) { //show the date on the head of each calendar week
                echo '<th>' . $day . ', ' . $startDate->format('n/j') . '</th>';
                $startDate->modify('+1 day');
            }
            ?>
        </tr>
    </table>
</body>
<script>
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
            
            const date = new Date(year, month-1, day); //create a new date object
            const weekNumber = Math.round(((date - new Date(date.getFullYear(), 0, 1)) / 86400000 + date.getDay() + 1) / 7); // calculate the week
            window.location.href = `?week=${weekNumber}&year=${year}`; // reset the calender
        }
    </script>
</html>
