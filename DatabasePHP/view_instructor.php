<?php
include('db_connect.php'); // Include your DB connection file

// Initialize filter variable
$filter = '';
if (isset($_POST['filter'])) {
    $filter = mysqli_real_escape_string($conn, $_POST['filter']); // Sanitize input
}

// Fetch instructor from the database
$sql = "SELECT * FROM `instructor_table` WHERE 1 ORDER BY `Instructor_Name` ASC";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Instructors</title>
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

<h1>Instructor List</h1>

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
            <th>Instructor ID</th>
            <th>Instructor Name</th>
            <th>Instructor Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['Instructor_ID']}</td>
                        <td>{$row['Instructor_Name']}</td>
                        <td>{$row['Instructor_Email']}</td>
                        <td>{$row['Phone_Number']}</td>
                        <td>{$row['Gender']}</td>
                        <td><a href='edit_instructor.php?id={$row['Instructor_ID']}'>Edit</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No instructors found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
