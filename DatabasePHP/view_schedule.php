<?php
include('db_connect.php'); // Include your DB connection file

// Initialize filter variable
$filter = '';
if (isset($_POST['filter'])) {
    $filter = mysqli_real_escape_string($conn, $_POST['filter']); // Sanitize input
}

// Fetch the schedule data with class and instructor details with filtering applied
$query = "SELECT s.Schedule_ID, s.Class_Duration, s.Start_Time, s.End_Time, s.Week_Day, 
                 c.Class_Name, c.Room_no, i.Instructor_Name 
          FROM schedule_table s
          JOIN class_table c ON s.Class_ID = c.Class_ID
          JOIN instructor_table i ON c.Instructor_ID = i.Instructor_ID
          WHERE c.Class_Name LIKE '%$filter%' 
          OR i.Instructor_Name LIKE '%$filter%' 
          OR s.Week_Day LIKE '%$filter%'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Function to format duration into hours and minutes
function formatDuration($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    $formatted = [];
    if ($hours > 0) $formatted[] = "$hours hour" . ($hours > 1 ? "s" : "");
    if ($remaining_minutes > 0) $formatted[] = "$remaining_minutes minute" . ($remaining_minutes > 1 ? "s" : "");
    return implode(" ", $formatted);
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

<body>

<h1>Schedule List</h1>

<!-- Filter Form -->
<form method="POST" action="">
    <input type="text" name="filter" placeholder="Filter by Class, Instructor, or Weekday..." value="<?php echo htmlspecialchars($filter); ?>">
    <input type="submit" value="Filter">
</form>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<table>
    <thead>
        <tr>
            <th>Schedule ID</th>
            <th>Class Name</th>
            <th>Instructor</th>
            <th>Room Number</th>
            <th>Duration</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Week Day</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['Schedule_ID']}</td>
                        <td>{$row['Class_Name']}</td>
                        <td>{$row['Instructor_Name']}</td>
                        <td>{$row['Room_no']}</td>
                        <td>" . formatDuration($row['Class_Duration']) . "</td>
                        <td>{$row['Start_Time']}</td>
                        <td>{$row['End_Time']}</td>
                        <td>{$row['Week_Day']}</td>
                        <td>
                            <a href='edit_schedule.php?id={$row['Schedule_ID']}'>Edit</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No schedules found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
