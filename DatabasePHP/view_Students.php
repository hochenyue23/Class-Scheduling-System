<?php
include('db_connect.php'); // Include your DB connection file

// Initialize filter variable
$filter = '';
if (isset($_POST['filter'])) {
    $filter = mysqli_real_escape_string($conn, $_POST['filter']); // Sanitize input
}

// Fetch students from the database, including Student_Email and DOB
$query = "SELECT * FROM student_table WHERE 
          Student_Name LIKE '%$filter%' OR 
          Gender LIKE '%$filter%' OR 
          Academic_Status LIKE '%$filter%' OR 
          Contact_No LIKE '%$filter%' OR 
          Student_Email LIKE '%$filter%' OR 
          DOB LIKE '%$filter%'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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

<h1>Student List</h1>

<!-- Filter Form -->
<form method="POST" action="">
    <input type="text" name="filter" placeholder="Filter..." value="<?php echo htmlspecialchars($filter); ?>">
    <input type="submit" value="Filter">
</form>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<table>
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Academic Status</th>
            <th>Contact No</th>
            <th>Student Email</th>
            <th>Date of Birth</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['Student_ID']}</td>
                        <td>{$row['Student_Name']}</td>
                        <td>{$row['Gender']}</td>
                        <td>{$row['Academic_Status']}</td>
                        <td>{$row['Contact_No']}</td>
                        <td>{$row['Student_Email']}</td>
                        <td>{$row['DOB']}</td>
                        <td><a href='edit_student.php?id={$row['Student_ID']}'>Edit</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No students found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
