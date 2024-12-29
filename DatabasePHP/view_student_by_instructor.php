<?php
include('db_connect.php'); // Include your DB connection file

// Query to get class information with student count and student details
$query = "
    SELECT 
        c.Class_ID,
        c.Class_Name,
        i.Instructor_Name,
        COUNT(e.Student_ID) AS student_count
    FROM 
        class_table c
    LEFT JOIN 
        schedule_table sch ON c.Class_ID = sch.Class_ID
    LEFT JOIN 
        enrollment_table e ON sch.Schedule_ID = e.Schedule_ID
    LEFT JOIN 
        instructor_table i ON c.Instructor_ID = i.Instructor_ID
    GROUP BY 
        c.Class_ID, c.Class_Name, i.Instructor_Name
    ORDER BY 
        c.Class_Name";

$result = mysqli_query($conn, $query);

mysqli_close($conn); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Enrollment</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<br><br>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<h1>Class Enrollment Information</h1>

<!-- Display Class and Student Data -->
<table>
    <thead>
        <tr>
            <th>Class Name</th>
            <th>Instructor</th>
            <th>Student Count</th>
            <th>Student Details</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['Class_Name']; ?></td>
                    <td><?php echo $row['Instructor_Name']; ?></td>
                    <td><?php echo $row['student_count']; ?></td>
                    <td>
                        <a href="view_students_in_class.php?class_id=<?php echo $row['Class_ID']; ?>">View Students</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No class information found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
