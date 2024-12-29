<?php
include('db_connect.php'); // Include your DB connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $enrollment_id = mysqli_real_escape_string($conn, $_POST['enrollment_id']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $schedule_id = mysqli_real_escape_string($conn, $_POST['schedule_id']);
    $enrollment_date = date('Y-m-d'); // Current date for enrollment

    // Check for time conflicts in the schedule for the same student
    $conflict_query = "
        SELECT s.Start_Time, s.End_Time, s.Week_Day
        FROM enrollment_table e
        JOIN schedule_table s ON e.Schedule_ID = s.Schedule_ID
        WHERE e.Student_ID = '$student_id'
        AND s.Week_Day = (SELECT Week_Day FROM schedule_table WHERE Schedule_ID = '$schedule_id')
        AND (
            (s.Start_Time < (SELECT End_Time FROM schedule_table WHERE Schedule_ID = '$schedule_id') 
            AND s.End_Time > (SELECT Start_Time FROM schedule_table WHERE Schedule_ID = '$schedule_id'))
        )";
    
    $conflict_result = mysqli_query($conn, $conflict_query);

    if (mysqli_num_rows($conflict_result) > 0) {
        echo "<script>alert('Error: Student is already enrolled in another class during the selected timeslot.');</script>";
    } else {
        // Prepare the SQL query to insert data into the enrollment table
        $query = "INSERT INTO enrollment_table (Enrollment_ID, Student_ID, Schedule_ID, Enrollment_Date) 
                  VALUES ('$enrollment_id', '$student_id', '$schedule_id', '$enrollment_date')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo "Enrollment added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all students with Student ID and Name
$students_query = "SELECT Student_ID, Student_Name FROM student_table";
$students_result = mysqli_query($conn, $students_query);

// Fetch class names
$class_names_query = "SELECT DISTINCT Class_Name FROM class_table";
$class_names_result = mysqli_query($conn, $class_names_query);

// Fetch all class schedules
$classes_query = "SELECT s.Schedule_ID, c.Class_Name, s.Start_Time, s.End_Time, i.Instructor_Name, s.Week_Day 
                  FROM schedule_table s
                  JOIN class_table c ON s.Class_ID = c.Class_ID
                  JOIN instructor_table i ON c.Instructor_ID = i.Instructor_ID";
$class_options_result = mysqli_query($conn, $classes_query);

$class_options = [];
while ($row = mysqli_fetch_assoc($class_options_result)) {
    // Convert start and end time to 12-hour format
    $row['Start_Time'] = date("g:i A", strtotime($row['Start_Time']));
    $row['End_Time'] = date("g:i A", strtotime($row['End_Time']));
    $class_options[] = $row;
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student</title>

    <!-- Include jQuery and Select2 CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        #student_id {
            width: 100%;
            max-width: 500px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#student_id').select2({
                placeholder: "Search for a student",
                allowClear: true
            });

            $('#class_name').change(function() {
                var className = $(this).val();

                $('#schedule_id').find('option').each(function() {
                    var option = $(this);
                    if (className && option.data('class-name') !== className) {
                        option.hide();
                    } else {
                        option.show();
                    }
                });

                $('#schedule_id').val(null).trigger('change');
            });
        });
    </script>
</head>
<body>

<h1>Enroll Student for a Class</h1>

<form method="POST" action="">
    <label for="enrollment_id">Enrollment ID:</label>
    <input type="text" name="enrollment_id" id="enrollment_id" required><br><br>

    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="">Select a Student</option>
        <?php while ($row = mysqli_fetch_assoc($students_result)) { ?>
            <option value="<?php echo $row['Student_ID']; ?>">
                <?php echo $row['Student_ID'] . ' - ' . $row['Student_Name']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label for="class_name">Select Class:</label>
    <select name="class_name" id="class_name">
        <option value="">All Classes</option>
        <?php while ($row = mysqli_fetch_assoc($class_names_result)) { ?>
            <option value="<?php echo $row['Class_Name']; ?>"><?php echo $row['Class_Name']; ?></option>
        <?php } ?>
    </select><br><br>

    <label for="schedule_id">Select Class Schedule:</label>
    <select name="schedule_id" id="schedule_id" required>
        <option value="">Select a Schedule</option>
        <?php foreach ($class_options as $class) { ?>
            <option value="<?php echo $class['Schedule_ID']; ?>" data-class-name="<?php echo $class['Class_Name']; ?>">
                <?php echo $class['Class_Name'] . " - " . $class['Instructor_Name'] . " - " . $class['Week_Day'] . " - " . $class['Start_Time'] . " to " . $class['End_Time']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <input type="submit" name="submit" value="Enroll">
    <input type="button" value="Cancel" onclick="window.location.href='index.html'">
</form>

</body>
</html>
