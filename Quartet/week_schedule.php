<!-- 
    week_schedule.php
    A page to hold the appointment calendar and scheduler.
    Author: Alexandra Stratton, Ben Renner, Brinley Hull, Jose Leyba, Kyle Moore
    Revisions:
        2/27/2025 -- Alexandra Stratton, add weekly calendar
    Creation date:
-->
<?php
$barbers = [
    [
        "name" => "Danny DeVito",
        "color" => "#ffcccc",
        "hover_color" => "red",
        "appointments" => [
            "2025-03-01" => ["09:00", "11:00", "13:00"]
        ]
    ],
    [
        "name" => "Pitbull",
        "color" => "#a3c9f0",
        "hover_color" => "blue",
        "appointments" => [
            "2025-03-01" => ["10:00", "12:00", "14:00"]
        ]
    ],
    [
        "name" => "Guy Fieri",
        "color" => "#f4b189",
        "hover_color" => "darkorange",
        "appointments" => [
            "2025-03-01" => ["08:00", "10:30", "13:30"]
        ]
    ]
];

$availability = [
    "2025-03-01" => [
        "Danny DeVito" => ["09:00", "11:00", "13:00"],
        "Pitbull" => ["10:00", "12:00", "14:00"],
        "Guy Fieri" => ["08:00", "10:30", "13:30"]
    ]
];


function getAvailableSlots($day) {
    global $availability;
    return isset($availability[$day]) ? $availability[$day] : [];
}


$dt = new DateTime;
if (isset($_GET['year']) && isset($_GET['week'])) {
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$year = $dt->format('o');
$week = $dt->format('W');


$monthYear = $dt->format('F Y');
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
    <!--The green Bar at the top that has the name and button that takes you to the login page-->
    <div class="top-bar">
        <h1>Quartet's Amazing Barbershop</h1>
        <!--Stylized Button to be circular, when clicked takes you to login.html-->
        <div class="login-container">
            <span>Login</span>
            <button class="login-button" onclick="location.href='login.php'">&#10132;</button>
        </div>
    </div>
    <!--Menu with all possible pages-->
    <div class="menu">
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='schedule.php'">Schedule</button>
        <button onclick="location.href='store.php'">Store</button>
        <button onclick="location.href='barbers.php'">Barbers</button>
        <button onclick="location.href='page5.html'">Page 5</button>
    </div>
    <div class="calendar-navigation">
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>" class="arrow arrow-left">&#9664;</a>
        <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>" class="arrow arrow-right">&#9654;</a>
   
        
    </div>

    <h2><?php echo $monthYear; ?></h2>

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

            for ($i = 0; $i < 7; $i++) {
                $currentDay = $startDate->format('Y-m-d');
                echo "<td class='day' id='day-" . $currentDay . "'>";
                echo "<span>" . $startDate->format('D, d') . "</span>";
                echo "<ul id='availability-list" . $currentDay . "' class='availability-list'></ul>";  

                $availableSlotsForDay = getAvailableSlots($currentDay); 
                $allSlots = [];
                foreach ($availableSlotsForDay as $barberName => $slots) {
                    foreach ($slots as $slot) {
                        $allSlots[] = [
                            'time' => $slot,
                            'barber' => $barberName
                        ];
                    }
                }

                
                usort($allSlots, function ($a, $b) {
                    return strtotime($a['time']) - strtotime($b['time']);
                });

                foreach ($allSlots as $slotData) {
                    $barberName = $slotData['barber'];
                    $slot = $slotData['time'];
                    
                    $barberColor = '';
                    foreach ($barbers as $barber) {
                        if ($barber['name'] === $barberName) {
                            $barberColor = $barber['color'];
                        }
                    }
                    
                    echo "<button class='availability-btn' 
                            style='background-color: {$barberColor}; 
                            font-size: 18px;
                            padding: 12px;
                            margin: 3px;
                            width: 100%;
                            ' data-day='" . $currentDay . "' data-time='" . $slot . "' data-barber='" . $barberName . "'>" . $slot . "</button><br>";
                }

                echo "</td>";
                $startDate->modify('+1 day');
            }
            ?>
        </tr>
    </table>

    

</body>
</html>
