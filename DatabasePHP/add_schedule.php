<?php
include('db_connect.php'); // Include your DB connection file

// Fetch classes for dropdown
$class_query = "SELECT Class_ID, Class_Name FROM class_table";
$class_result = mysqli_query($conn, $class_query);

if (!$class_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schedule_id = $_POST['schedule_id'];
    $class_id = $_POST['class_id'];
    $class_duration = $_POST['class_duration'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $week_day = $_POST['week_day'];

    // Insert query
    $insert_query = "INSERT INTO schedule_table (Schedule_ID, Class_ID, Class_Duration, Start_Time, End_Time, Week_Day)
                     VALUES ('$schedule_id', '$class_id', '$class_duration', '$start_time', '$end_time', '$week_day')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Schedule added successfully.";
        header("Location: view_schedule.php");
        exit;
    } else {
        echo "Error adding schedule: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
</head>
<body>

<h1>Add Schedule</h1>

<form method="POST" action="">
    <label for="schedule_id">Schedule ID:</label>
    <input type="text" name="schedule_id" placeholder="Enter Schedule ID" required>
    <br><br>

    <label for="class_id">Class:</label>
    <select name="class_id" required>
        <option value="">Select Class</option>
        <?php
        while ($class = mysqli_fetch_assoc($class_result)) {
            echo "<option value='{$class['Class_ID']}'>{$class['Class_Name']}</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="class_duration">Duration (minutes):</label>
    <input type="number" name="class_duration" min="1" required>
    <br><br>

    <label for="start_time">Start Time:</label>
    <input type="time" name="start_time" required>
    <br><br>

    <label for="end_time">End Time:</label>
    <input type="time" name="end_time" required>
    <br><br>

    <label for="week_day">Week Day:</label>
    <select name="week_day" required>
        <option value="">Select Day</option>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>
        <option value="Sunday">Sunday</option>
    </select>
    <br><br>

    <input type="submit" value="Add Schedule">
    <button type="button" onclick="location.href='index.html'">Cancel</button>
</form>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
