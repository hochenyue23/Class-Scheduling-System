<?php
include('db_connect.php');

// Fetch schedules along with class name, instructor info, and student count
$query = "
    SELECT 
        s.Schedule_ID, 
        c.Class_Name, 
        i.Instructor_Name, 
        s.Class_Duration, 
        s.Start_Time, 
        s.End_Time, 
        s.Week_Day,
        COUNT(e.Student_ID) AS Student_Count
    FROM schedule_table s
    LEFT JOIN enrollment_table e ON s.Schedule_ID = e.Schedule_ID
    LEFT JOIN class_table c ON s.Class_ID = c.Class_ID
    LEFT JOIN instructor_table i ON c.Instructor_ID = i.Instructor_ID
    GROUP BY s.Schedule_ID
    ORDER BY s.Schedule_ID";

$schedule_result = mysqli_query($conn, $query);

if (!$schedule_result) {
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
<Br></Br>

<button onclick="location.href='index.html'">Back to Main Menu</button>

<body>
    <h1>Schedule Attendance</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Schedule ID</th>
                <th>Class Name</th>
                <th>Instructor Name</th>
                <th>Class Duration</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Week Day</th>
                <th>Number of Students</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($schedule_result)): ?>
                <tr>
                    <td><?php echo $row['Schedule_ID']; ?></td>
                    <td><?php echo $row['Class_Name']; ?></td>
                    <td><?php echo $row['Instructor_Name']; ?></td>
                    <td><?php echo formatDuration($row['Class_Duration']); ?></td>
                    <td><?php echo formatTime($row['Start_Time']); ?></td>
                    <td><?php echo formatTime($row['End_Time']); ?></td>
                    <td><?php echo $row['Week_Day']; ?></td>
                    <td><?php echo $row['Student_Count']; ?></td>
                    <td>
                        <a href="view_students_in_schedule.php?schedule_id=<?php echo $row['Schedule_ID']; ?>">View Students</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

  
</body>
</html>

<?php
mysqli_close($conn);

// Helper function to format duration
function formatDuration($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    return ($hours > 0 ? "$hours hour(s) " : "") . ($remaining_minutes > 0 ? "$remaining_minutes minute(s)" : "");
}

// Helper function to format time
function formatTime($time) {
    return date("g:i A", strtotime($time));
}
?>
