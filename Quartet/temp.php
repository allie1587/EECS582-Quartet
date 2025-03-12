<?php

function getWeekDays($weekStart = 'Monday') {
    $start = new DateTime("this week $weekStart");
    $days = [];
    
    for ($i = 0; $i < 7; $i++) {
        $days[] = clone $start;
        $start->modify('+1 day');
    }
    
    return $days;
}

// Create the $hours array with 1-minute intervals from 8:00 AM to 6:00 PM in standard time (AM/PM)
$hours = [];
for ($hour = 8; $hour <= 18; $hour++) {
    for ($minute = 0; $minute < 60; $minute++) {
        $time = sprintf("%02d:%02d", $hour, $minute);
        
        // Convert time to standard format (AM/PM)
        $formattedTime = date("h:i A", strtotime($time));
        $hours[] = $formattedTime;
    }
}

// Example appointments: stored as (day => [time])
$appointments = [
    'Wednesday' => [
        '8:00 AM', '8:30 AM', '11:00 AM', '12:45 PM', '1:15 PM'
    ]
];

// Function to check if a specific time slot is booked
function isBooked($day, $time) {
    global $appointments;
    if (isset($appointments[$day])) {
        // Check if the time is within a 30-minute appointment window
        foreach ($appointments[$day] as $appt) {
            $start = new DateTime($appt);
            $end = clone $start;
            $end->modify('+30 minutes');
            $currentTime = new DateTime($time);
            
            if ($currentTime >= $start && $currentTime < $end) {
                return true; // This time is within the 30-minute appointment block
            }
        }
    }
    return false;
}

$weekDays = getWeekDays();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week View Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .hour-row td {
            height: 30px;
        }
        .booked {
            background-color: #f99;
        }
    </style>
</head>
<body>
    <h2>Week View Calendar</h2>
    <table>
        <tr>
            <th>Time</th>
            <?php foreach ($weekDays as $day): ?>
                <th><?php echo $day->format('l, M j'); ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($hours as $hour): ?>
        <tr class="hour-row">
            <td><?php echo $hour; ?></td>
            <?php foreach ($weekDays as $day): ?>
                <?php
                    $dayName = $day->format('l');
                    // Check if this time slot is booked
                    $isBooked = isBooked($dayName, $hour);
                ?>
                <td class="<?php echo $isBooked ? 'booked' : ''; ?>"></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
