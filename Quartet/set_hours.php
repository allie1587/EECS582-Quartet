<!-- 
    set_hours
    A page for the barber to set their hours.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/27/2025 -- Alexandra Stratton, add weekly calendar
        3/10/2025 -- Brinley, start revamp to be the barber set hours page. Change top "week of" format
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
    <title>Booking Calendar</title>
    <style>
        
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .top-bar {
            background-color: green;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 50px;
        }
        .top-bar h1 {
            margin: 0;
            padding-left: 20px;
            font-size: 24px;
            color: white;
        }
        .login-container {
            display: flex;
            align-items: center;
            padding-right: 20px;
        }
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
        .menu {
            margin-top: 20px;
        }
        .menu button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        h2 {
            font-size: 28px;
            color: #4b5563;
            margin-bottom: 24px;
            text-align: center;
        }
        .calendar-navigation {
            display: flex;
            justify-content: center;
            position: relative;
            position: absolute;
            
        }
        .calendar-navigation a {
            font-size: 50px;
            text-decoration: none;
            color: #1e40af;
            padding: 10px;
            margin: 5px 0;
        }
        
    
        /* Table Styles */
        .calendar-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 8px #0000001a;
        }

        .calendar-table th {
            padding: 15px;
            text-align: center;
            background-color: green;
            color: white;
        }

        .calendar-table td {
            padding: 15px;
            text-align: center;
            background-color: #fafafa;
            font-size: 16px;
            color: #333;
        }
        .day {
            position: relative;
            text-align: center; 
            vertical-align: top;
        }
        .day span {
            font-size: 20px; 
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="calendar-navigation">
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>" class="arrow arrow-left">&#9664;</a>
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>" class="arrow arrow-right">&#9654;</a>
   
        
    </div>

    <h2>Week of <?php echo $monthYear; ?></h2> <!--Show the currently displayed week-->

    <table class="calendar-table">
        <tr>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
            <th>Sun</th>
        </tr>
        <tr>
            <?php
            $startDayOfWeek = $dt->format('N');
            $startDate = clone $dt;
            $startDate->modify('-' . ($startDayOfWeek - 1) . ' days'); 
            ?>
        </tr>
    </table>
</body>
</html>
