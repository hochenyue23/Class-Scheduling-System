<?php
include('db_connect.php'); // Include your DB connection file

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the schedule details from the database
    $query = "SELECT * FROM schedule_table WHERE Schedule_ID = '$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $schedule = mysqli_fetch_assoc($result);

    if (!$schedule) {
        echo "Schedule not found.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Update the schedule information
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $schedule_id = $_POST['schedule_id'];
    $class_id = $_POST['class_id'];
    $class_duration = $_POST['class_duration'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $week_day = $_POST['week_day'];

    $update_query = "UPDATE schedule_table SET 
                     Class_ID = '$class_id', 
                     Class_Duration = '$class_duration', 
                     Start_Time = '$start_time', 
                     End_Time = '$end_time', 
                     Week_Day = '$week_day' 
                     WHERE Schedule_ID = '$schedule_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "Schedule updated successfully.";
        header("Location: view_schedule.php");
        exit;
    } else {
        echo "Error updating schedule: " . mysqli_error($conn);
    }
}

// Delete the schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $schedule_id = $_POST['schedule_id'];

    $delete_query = "DELETE FROM schedule_table WHERE Schedule_ID = '$schedule_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo "Schedule deleted successfully.";
        header("Location: view_schedule.php");
        exit;
    } else {
        echo "Error deleting schedule: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
</head>
<body>

<h1>Edit Schedule</h1>

<form method="POST" action="">
    <input type="hidden" name="schedule_id" value="<?php echo $schedule['Schedule_ID']; ?>">

    <label for="class_id">Class:</label>
    <select name="class_id" required>
        <?php
        $class_query = "SELECT Class_ID, Class_Name FROM class_table";
        $class_result = mysqli_query($conn, $class_query);

        while ($class = mysqli_fetch_assoc($class_result)) {
            $selected = ($class['Class_ID'] == $schedule['Class_ID']) ? 'selected' : '';
            echo "<option value='{$class['Class_ID']}' $selected>{$class['Class_Name']}</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="class_duration">Duration (minutes):</label>
    <input type="number" name="class_duration" value="<?php echo $schedule['Class_Duration']; ?>" required>
    <br><br>

    <label for="start_time">Start Time:</label>
    <input type="time" name="start_time" value="<?php echo $schedule['Start_Time']; ?>" required>
    <br><br>

    <label for="end_time">End Time:</label>
    <input type="time" name="end_time" value="<?php echo $schedule['End_Time']; ?>" required>
    <br><br>

    <label for="week_day">Week Day:</label>
    <select name="week_day" required>
        <option value="Monday" <?php if ($schedule['Week_Day'] == 'Monday') echo 'selected'; ?>>Monday</option>
        <option value="Tuesday" <?php if ($schedule['Week_Day'] == 'Tuesday') echo 'selected'; ?>>Tuesday</option>
        <option value="Wednesday" <?php if ($schedule['Week_Day'] == 'Wednesday') echo 'selected'; ?>>Wednesday</option>
        <option value="Thursday" <?php if ($schedule['Week_Day'] == 'Thursday') echo 'selected'; ?>>Thursday</option>
        <option value="Friday" <?php if ($schedule['Week_Day'] == 'Friday') echo 'selected'; ?>>Friday</option>
        <option value="Saturday" <?php if ($schedule['Week_Day'] == 'Saturday') echo 'selected'; ?>>Saturday</option>
        <option value="Sunday" <?php if ($schedule['Week_Day'] == 'Sunday') echo 'selected'; ?>>Sunday</option>
    </select>
    <br><br>

    <input type="submit" name="update" value="Update">
    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this schedule?');">
    <button type="button" onclick="location.href='view_schedule.php'">Cancel</button>
</form>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
