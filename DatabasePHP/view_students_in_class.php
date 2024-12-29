<?php
include('db_connect.php'); // Include your DB connection file

// Check if the class ID is set
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Query to get all student details for a specific class
    $query = "
        SELECT 
            s.Student_ID, 
            s.Student_Name, 
            s.Gender, 
            s.Academic_Status, 
            s.Contact_no, 
            e.Enrollment_Date
        FROM 
            enrollment_table e
        JOIN 
            student_table s ON e.Student_ID = s.Student_ID
        JOIN 
            schedule_table sch ON e.Schedule_ID = sch.Schedule_ID
        WHERE 
            sch.Class_ID = '$class_id'
        ORDER BY 
            e.Enrollment_Date DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
} else {
    echo "No class ID provided.";
    exit;
}

mysqli_close($conn); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students in Class</title>
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
<button onclick="location.href='view_student_by_instructor.php'">Back to Previous Page</button>


<h1>Students Enrolled in Class</h1>

<!-- Display Student Data -->
<table>
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Academic Status</th>
            <th>Contact No</th>
            <th>Enrollment Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['Student_ID']; ?></td>
                    <td><?php echo $row['Student_Name']; ?></td>
                    <td><?php echo $row['Gender']; ?></td>
                    <td><?php echo $row['Academic_Status']; ?></td>
                    <td><?php echo $row['Contact_no']; ?></td>
                    <td><?php echo $row['Enrollment_Date']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No students found for this class.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
