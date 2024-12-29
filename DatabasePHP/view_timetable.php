<?php
include('db_connect.php');

// Handle the form submission to filter by student
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$student_name = ''; // Initialize the student name variable

// Fetch student name if a student is selected
if ($student_id) {
    // Fetch the student name from the student_table
    $student_query = "SELECT Student_Name FROM student_table WHERE Student_ID = '$student_id'";
    $student_result = mysqli_query($conn, $student_query);
    
    if ($student_result) {
        $student_row = mysqli_fetch_assoc($student_result);
        $student_name = $student_row['Student_Name'];
    } else {
        die("Student query failed: " . mysqli_error($conn));
    }

    // Fetch the enrolled schedules for the selected student, including instructor details
    $query = "SELECT e.Enrollment_ID, s.Schedule_ID, s.Class_Duration, s.Start_Time, s.End_Time, s.Week_Day, 
                     c.Class_Name, c.Room_no, i.Instructor_Name
              FROM enrollment_table e
              JOIN schedule_table s ON e.Schedule_ID = s.Schedule_ID
              JOIN class_table c ON s.Class_ID = c.Class_ID
              LEFT JOIN instructor_table i ON c.Instructor_ID = i.Instructor_ID
              WHERE e.Student_ID = '$student_id'";

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

// Handle the "Remove Timetable" functionality
if (isset($_GET['remove_enrollment_id'])) {
    $remove_enrollment_id = $_GET['remove_enrollment_id'];

    // Delete the record from the enrollment table
    $remove_query = "DELETE FROM enrollment_table WHERE Enrollment_ID = '$remove_enrollment_id' AND Student_ID = '$student_id'";
    $remove_result = mysqli_query($conn, $remove_query);

    if ($remove_result) {
        echo "<p>Timetable entry removed successfully!</p>";
        // Redirect to refresh the page and show updated timetable
        header("Location: " . $_SERVER['PHP_SELF'] . "?student_id=$student_id");
        exit();
    } else {
        echo "<p>Error removing timetable entry: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Timetable</title>

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

<h1>Student Timetable</h1>

<!-- Dropdown to select student with filter -->
<form method="GET" action="">
    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required style="width: 300px;">
        <option value="">--Select a Student--</option>
        <?php 
        // Fetch students for dropdown
        $students_query = "SELECT Student_ID, Student_Name FROM student_table";
        $students_result = mysqli_query($conn, $students_query);

        while ($row = mysqli_fetch_assoc($students_result)) {
            echo "<option value='{$row['Student_ID']}' " . ($student_id == $row['Student_ID'] ? "selected" : "") . ">{$row['Student_ID']} - {$row['Student_Name']}</option>";
        }
        ?>
    </select>
    <input type="submit" value="View Timetable">
</form>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<!-- Timetable Display -->
<?php if ($student_id): ?>
    <h2>Timetable for Student ID: <?php echo $student_id . " - " . $student_name; ?></h2>

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
                    echo "<strong>Room:</strong> " . $class['Room_no'] . "<br>";
                    echo "<strong>Duration:</strong> " . formatDuration($class['Class_Duration']) . "<br>";
                    echo "<strong>Time:</strong> " . formatTime($class['Start_Time']) . " - " . formatTime($class['End_Time']) . "<br>";
                    echo "<a href='?student_id={$student_id}&remove_enrollment_id={$class['Enrollment_ID']}'>Remove</a>";
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
    // Initialize select2 for student filter dropdown with typing-enabled filtering
    $(document).ready(function() {
        $('#student_id').select2({
            placeholder: "Search for Student by ID or Name",
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
