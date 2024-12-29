<?php
include('db_connect.php');

// Get the schedule ID from the query string
$schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : '';

if (!$schedule_id) {
    die("Schedule ID is required.");
}

// Fetch students attending the selected schedule
$query = "
    SELECT 
        e.Student_ID, 
        s.Student_Name, 
        s.Gender, 
        s.Academic_Status, 
        s.Contact_no
    FROM enrollment_table e
    JOIN student_table s ON e.Student_ID = s.Student_ID
    WHERE e.Schedule_ID = '$schedule_id'
    ORDER BY s.Student_ID";

$students_result = mysqli_query($conn, $query);

if (!$students_result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<br></br>
<button onclick="location.href='view_schedule_students.php'">Back to Schedule List</button>

<body>
    <h1>Students in Schedule ID: <?php echo htmlspecialchars($schedule_id); ?></h1>

    <table border="1">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Academic Status</th>
                <th>Contact Number</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($students_result)): ?>
                <tr>
                    <td><?php echo $row['Student_ID']; ?></td>
                    <td><?php echo $row['Student_Name']; ?></td>
                    <td><?php echo $row['Gender']; ?></td>
                    <td><?php echo $row['Academic_Status']; ?></td>
                    <td><?php echo $row['Contact_no']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    
</body>
</html>

<?php
mysqli_close($conn);
?>
