<?php
include('db_connect.php'); // Include DB connection file

// Check if the enrollment ID is provided
if (!isset($_GET['enrollment_id'])) {
    echo "No Enrollment ID provided.";
    exit;
}

$enrollment_id = mysqli_real_escape_string($conn, $_GET['enrollment_id']);

// Fetch enrollment details
$query = "
    SELECT 
        e.Enrollment_ID, 
        e.Student_ID, 
        e.Schedule_ID, 
        e.Enrollment_Date 
    FROM 
        enrollment_table e 
    WHERE 
        e.Enrollment_ID = '$enrollment_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Enrollment not found.";
    exit;
}

$enrollment = mysqli_fetch_assoc($result);

// Fetch all students
$students_query = "SELECT Student_ID, Student_Name FROM student_table";
$students_result = mysqli_query($conn, $students_query);

// Fetch all class schedules
$classes_query = "
    SELECT 
        sch.Schedule_ID, 
        c.Class_Name, 
        i.Instructor_Name, 
        sch.Week_Day, 
        sch.Start_Time, 
        sch.End_Time 
    FROM 
        schedule_table sch 
    JOIN 
        class_table c ON sch.Class_ID = c.Class_ID 
    JOIN 
        instructor_table i ON c.Instructor_ID = i.Instructor_ID";
$class_options_result = mysqli_query($conn, $classes_query);

$class_options = [];
while ($row = mysqli_fetch_assoc($class_options_result)) {
    // Format the schedule details for display
    $row['Start_Time'] = date("g:i A", strtotime($row['Start_Time']));
    $row['End_Time'] = date("g:i A", strtotime($row['End_Time']));
    $class_options[] = $row;
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $schedule_id = mysqli_real_escape_string($conn, $_POST['schedule_id']);

    $update_query = "
        UPDATE enrollment_table 
        SET 
            Student_ID = '$student_id', 
            Schedule_ID = '$schedule_id' 
        WHERE 
            Enrollment_ID = '$enrollment_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "Enrollment updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle deletion
if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM enrollment_table WHERE Enrollment_ID = '$enrollment_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "Enrollment deleted successfully!";
        header("Location: view_enrollment.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enrollment</title>
</head>
<body>

<h1>Edit Enrollment</h1>

<form method="POST" action="">
    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="">Select a Student</option>
        <?php while ($row = mysqli_fetch_assoc($students_result)) { ?>
            <option value="<?php echo $row['Student_ID']; ?>" 
                <?php echo $enrollment['Student_ID'] == $row['Student_ID'] ? 'selected' : ''; ?>>
                <?php echo $row['Student_ID'] . ' - ' . $row['Student_Name']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label for="schedule_id">Select Class Schedule:</label>
    <select name="schedule_id" id="schedule_id" required>
        <option value="">Select a Schedule</option>
        <?php foreach ($class_options as $class) { ?>
            <option value="<?php echo $class['Schedule_ID']; ?>" 
                <?php echo $enrollment['Schedule_ID'] == $class['Schedule_ID'] ? 'selected' : ''; ?>>
                <?php echo $class['Class_Name'] . " - " . $class['Instructor_Name'] . " - " . 
                    $class['Week_Day'] . " - " . $class['Start_Time'] . " to " . $class['End_Time']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <input type="submit" name="update" value="Update">
    <input type="submit" name="delete" value="Delete" 
        onclick="return confirm('Are you sure you want to delete this enrollment?');">
    <input type="button" value="Cancel" onclick="window.location.href='view_enrollment.php'">
</form>

</body>
</html>
