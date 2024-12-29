<?php
include('db_connect.php'); // Include your DB connection file

// Query to get the classes for each weekday, including Room Number
$query = "
    SELECT 
        c.Class_Name, 
        sch.Start_Time, 
        sch.End_Time, 
        sch.Week_Day, 
        c.Room_No,
        i.Instructor_Name
    FROM 
        schedule_table sch
    JOIN 
        class_table c ON sch.Class_ID = c.Class_ID
    JOIN 
        instructor_table i ON c.Instructor_ID = i.Instructor_ID
    ORDER BY 
        sch.Week_Day, sch.Start_Time";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Group the classes by weekday
$classes_by_day = [];
while ($row = mysqli_fetch_assoc($result)) {
    $classes_by_day[$row['Week_Day']][] = $row;
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule Calendar</title>
    <style>
        /* Simple CSS styling for the calendar */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 10px;
        }
        .day {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 150px;
        }
        .day h3 {
            text-align: center;
            margin: 0;
        }
        .class-item {
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Class Schedule Calendar</h1>

<!-- Calendar Section -->
<div class="calendar">
    <!-- Loop through the days of the week -->
    <?php
    $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($days_of_week as $day) {
        echo "<div class='day'>";
        echo "<h3>$day</h3>";

        // Check if there are any classes for this day
        if (isset($classes_by_day[$day])) {
            foreach ($classes_by_day[$day] as $class) {
                echo "<div class='class-item'>";
                echo "<strong>Class:</strong> " . $class['Class_Name'] . "<br>";
                echo "<strong>Instructor:</strong> " . $class['Instructor_Name'] . "<br>";
                echo "<strong>Room:</strong> " . $class['Room_No'] . "<br>";
                echo "<strong>Time:</strong> " . date("g:i A", strtotime($class['Start_Time'])) . " - " . date("g:i A", strtotime($class['End_Time'])) . "<br>";
                echo "</div>";
            }
        } else {
            echo "<p>No classes scheduled.</p>";
        }

        echo "</div>";
    }
    ?>
</div>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>

</body>
</html>
