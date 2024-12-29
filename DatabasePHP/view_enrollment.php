<?php
include('db_connect.php'); // Include DB connection file

// Initialize filter variables
$filter_student = $_GET['filter_student'] ?? '';
$filter_class = $_GET['filter_class'] ?? '';
$filter_instructor = $_GET['filter_instructor'] ?? '';
$manual_filter = $_GET['manual_filter'] ?? '';

// Modify query to include filters and manual search
$query = "
    SELECT 
        e.Enrollment_ID,
        s.Student_Name,
        s.Student_ID,
        c.Class_Name,
        i.Instructor_Name,
        sch.Week_Day,
        sch.Start_Time,
        sch.End_Time,
        e.Enrollment_Date
    FROM 
        enrollment_table e
    JOIN 
        student_table s ON e.Student_ID = s.Student_ID
    JOIN 
        schedule_table sch ON e.Schedule_ID = sch.Schedule_ID
    JOIN 
        class_table c ON sch.Class_ID = c.Class_ID
    JOIN 
        instructor_table i ON c.Instructor_ID = i.Instructor_ID
    WHERE 
        ('$filter_student' = '' OR s.Student_ID = '$filter_student')
        AND ('$filter_class' = '' OR c.Class_Name = '$filter_class')
        AND ('$filter_instructor' = '' OR i.Instructor_Name = '$filter_instructor')
        AND ('$manual_filter' = '' OR 
            s.Student_Name LIKE '%$manual_filter%' OR 
            c.Class_Name LIKE '%$manual_filter%' OR 
            i.Instructor_Name LIKE '%$manual_filter%' OR 
            e.Enrollment_ID LIKE '%$manual_filter%' OR 
            sch.Week_Day LIKE '%$manual_filter%' OR
            sch.Start_Time LIKE '%$manual_filter%' OR
            sch.End_Time LIKE '%$manual_filter%')
    ORDER BY 
        e.Enrollment_Date DESC";

$result = mysqli_query($conn, $query);

// Fetch filter options
$students_query = "SELECT DISTINCT Student_ID, Student_Name FROM student_table";
$students_result = mysqli_query($conn, $students_query);

$classes_query = "SELECT DISTINCT Class_Name FROM class_table";
$classes_result = mysqli_query($conn, $classes_query);

$instructors_query = "SELECT DISTINCT Instructor_Name FROM instructor_table";
$instructors_result = mysqli_query($conn, $instructors_query);

mysqli_close($conn); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Enrollments</title>
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

<br> </br>
<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<h1>All Enrollments</h1>

<!-- Filter Form -->
<form method="GET" action="">
    <label for="manual_filter">Search:</label>
    <input type="text" id="manual_filter" name="manual_filter" placeholder="Enter keyword..." 
        value="<?php echo htmlspecialchars($manual_filter); ?>" />

    <label for="filter_student">Filter by Student:</label>
    <select name="filter_student" id="filter_student">
        <option value="">All Students</option>
        <?php while ($row = mysqli_fetch_assoc($students_result)): ?>
            <option value="<?php echo $row['Student_ID']; ?>" 
                <?php echo $filter_student === $row['Student_ID'] ? 'selected' : ''; ?>>
                <?php echo $row['Student_Name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="filter_class">Filter by Class:</label>
    <select name="filter_class" id="filter_class">
        <option value="">All Classes</option>
        <?php while ($row = mysqli_fetch_assoc($classes_result)): ?>
            <option value="<?php echo $row['Class_Name']; ?>" 
                <?php echo $filter_class === $row['Class_Name'] ? 'selected' : ''; ?>>
                <?php echo $row['Class_Name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="filter_instructor">Filter by Instructor:</label>
    <select name="filter_instructor" id="filter_instructor">
        <option value="">All Instructors</option>
        <?php while ($row = mysqli_fetch_assoc($instructors_result)): ?>
            <option value="<?php echo $row['Instructor_Name']; ?>" 
                <?php echo $filter_instructor === $row['Instructor_Name'] ? 'selected' : ''; ?>>
                <?php echo $row['Instructor_Name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Filter</button>
    <button type="button" onclick="window.location.href='view_enrollments.php';">Reset</button>
</form>

<!-- Display Enrollment Data -->
<table>
    <thead>
        <tr>
            <th>Enrollment ID</th>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Class Name</th>
            <th>Instructor Name</th>
            <th>Schedule</th>
            <th>Enrollment Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['Enrollment_ID']; ?></td>
                    <td><?php echo $row['Student_Name']; ?></td>
                    <td><?php echo $row['Student_ID']; ?></td>
                    <td><?php echo $row['Class_Name']; ?></td>
                    <td><?php echo $row['Instructor_Name']; ?></td>
                    <td>
                        <?php echo $row['Week_Day'] . ": " . 
                            date("g:i A", strtotime($row['Start_Time'])) . " - " . 
                            date("g:i A", strtotime($row['End_Time'])); ?>
                    </td>
                    <td><?php echo $row['Enrollment_Date']; ?></td>
                    <td>
                        <a href="edit_enrollment.php?enrollment_id=<?php echo $row['Enrollment_ID']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No enrollments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
