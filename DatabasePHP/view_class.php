<?php
include('db_connect.php');

// Fetch all classes
$query = "SELECT c.Class_ID, c.Class_Name, c.Room_no, i.Instructor_Name
          FROM class_table c
          LEFT JOIN instructor_table i ON c.Instructor_ID = i.Instructor_ID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
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


<br> </br>
<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

<h1>Class Information</h1>

<table border="1">
    <thead>
        <tr>
            <th>Class ID</th>
            <th>Class Name</th>
            <th>Instructor Name</th>
            <th>Room Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['Class_ID']}</td>
                        <td>{$row['Class_Name']}</td>
                        <td>" . ($row['Instructor_Name'] ?? 'No Instructor Assigned') . "</td>
                        <td>{$row['Room_no']}</td>
                        <td>
                            <a href='edit_class.php?class_id={$row['Class_ID']}'>Edit</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No classes found.</td></tr>";
        }
        ?>
    </tbody>
</table>
<br></br>



</body>
</html>

<?php
mysqli_close($conn);
?>
