<?php
include('db_connect.php');

// Handle the form submission to filter by instructor
$instructor_id = isset($_GET['instructor_id']) ? $_GET['instructor_id'] : '';
$instructor_name = ''; // Initialize the instructor name variable

// Fetch instructor name if an instructor is selected
if ($instructor_id) {
    // Fetch the instructor name from the instructor_table
    $instructor_query = "SELECT Instructor_Name FROM instructor_table WHERE Instructor_ID = '$instructor_id'";
    $instructor_result = mysqli_query($conn, $instructor_query);
    
    if ($instructor_result) {
        $instructor_row = mysqli_fetch_assoc($instructor_result);
        $instructor_name = $instructor_row['Instructor_Name'];
    } else {
        die("Instructor query failed: " . mysqli_error($conn));
    }

    // Fetch the scheduled classes for the selected instructor
    $query = "SELECT s.Schedule_ID, s.Class_Duration, s.Start_Time, s.End_Time, s.Week_Day, 
                     c.Class_Name, c.Room_No
              FROM schedule_table s
              JOIN class_table c ON s.Class_ID = c.Class_ID
              WHERE c.Instructor_ID = '$instructor_id'
              ORDER BY s.Week_Day, s.Start_Time";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Group the classes by weekday
    $classes_by_day = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $classes_by_day[$row['Week_Day']][] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Schedule</title>

    <!-- Include select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery (required for select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr); /* 7 columns for each day of the week */
            grid-gap: 10px;
        }
        .day {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 150px;
            vertical-align: top;
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

<h1>Instructor Schedule</h1>

<!-- Dropdown to select instructor -->
<form method="GET" action="">
    <label for="instructor_id">Select Instructor:</label>
    <select name="instructor_id" id="instructor_id" required style="width: 300px;">
        <option value="">--Select an Instructor--</option>
        <?php 
        // Fetch instructors for dropdown
        $instructors_query = "SELECT Instructor_ID, Instructor_Name FROM instructor_table";
        $instructors_result = mysqli_query($conn, $instructors_query);

        while ($row = mysqli_fetch_assoc($instructors_result)) {
            echo "<option value='{$row['Instructor_ID']}' " . ($instructor_id == $row['Instructor_ID'] ? "selected" : "") . ">{$row['Instructor_ID']} - {$row['Instructor_Name']}</option>";
        }
        ?>
    </select>
    <input type="submit" value="View Schedule">
</form>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<!-- Timetable Display -->
<?php if ($instructor_id): ?>
    <h2>Schedule for Instructor ID: <?php echo $instructor_id . " - " . $instructor_name; ?></h2>

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
                    echo "<strong>Room:</strong> " . $class['Room_No'] . "<br>";
                    echo "<strong>Duration:</strong> " . formatDuration($class['Class_Duration']) . "<br>";
                    echo "<strong>Time:</strong> " . formatTime($class['Start_Time']) . " - " . formatTime($class['End_Time']) . "<br>";
                    echo "</div>";
                }
            } else {
                echo "<p>No classes scheduled.</p>";
            }

            echo "</div>";
        }
        ?>
    </div>

<?php endif; ?>

<!-- Include select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Initialize select2 for instructor filter dropdown with typing-enabled filtering
    $(document).ready(function() {
        $('#instructor_id').select2({
            placeholder: "Search for Instructor by ID or Name",
            allowClear: true
        });
    });
</script>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection

// Function to format duration into hours and minutes
function formatDuration($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    $formatted = [];
    if ($hours > 0) $formatted[] = "$hours hour" . ($hours > 1 ? "s" : "");
    if ($remaining_minutes > 0) $formatted[] = "$remaining_minutes minute" . ($remaining_minutes > 1 ? "s" : "");
    return implode(" ", $formatted);
}

// Function to format time into 12-hour format with AM/PM
function formatTime($time) {
    $time = strtotime($time); // Convert to Unix timestamp
    return date("g:i A", $time); // Format as 12-hour with AM/PM
}
?>
