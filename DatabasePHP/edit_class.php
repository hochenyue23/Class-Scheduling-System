<?php
include('db_connect.php');

// Get Class ID from the query string
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';

if (!$class_id) {
    die("Class ID is required.");
}

// Fetch the class details for the given Class ID
$query = "SELECT * FROM class_table WHERE Class_ID = '$class_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$class = mysqli_fetch_assoc($result);
if (!$class) {
    die("Class not found.");
}

// Handle form submission for updating class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_class'])) {
    $class_name = mysqli_real_escape_string($conn, $_POST['Class_Name']);
    $instructor_id = mysqli_real_escape_string($conn, $_POST['Instructor_ID']);
    $room_no = mysqli_real_escape_string($conn, $_POST['Room_No']); // Correct column name

    $update_query = "UPDATE class_table 
                     SET Class_Name = '$class_name', Instructor_ID = '$instructor_id', Room_No = '$room_no' 
                     WHERE Class_ID = '$class_id'"; // Correct column name

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Class updated successfully.'); window.location.href='view_class.php';</script>";
    } else {
        echo "Error updating class: " . mysqli_error($conn);
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_class'])) {
    $delete_query = "DELETE FROM class_table WHERE Class_ID = '$class_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Class deleted successfully.'); window.location.href='view_class.php';</script>";
    } else {
        echo "Error deleting class: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
</head>
<body>

<h1>Edit Class</h1>

<form method="POST" action="">
    <label for="Class_ID">Class ID:</label>
    <input type="text" name="Class_ID" value="<?php echo htmlspecialchars($class['Class_ID']); ?>" disabled /><br><br>

    <label for="Class_Name">Class Name:</label>
    <input type="text" name="Class_Name" value="<?php echo htmlspecialchars($class['Class_Name'] ?? ''); ?>" required /><br><br>

    <label for="Instructor_ID">Instructor:</label>
    <select name="Instructor_ID" required>
        <option value="">--Select an Instructor--</option>
        <?php
        // Fetch all instructors
        $instructors_query = "SELECT Instructor_ID, Instructor_Name FROM instructor_table";
        $instructors_result = mysqli_query($conn, $instructors_query);

        if ($instructors_result) {
            while ($instructor = mysqli_fetch_assoc($instructors_result)) {
                $selected = ($class['Instructor_ID'] == $instructor['Instructor_ID']) ? "selected" : "";
                echo "<option value='{$instructor['Instructor_ID']}' $selected>{$instructor['Instructor_Name']}</option>";
            }
        }
        ?>
    </select><br><br>

    <label for="Room_No">Room Number:</label>
    <input type="text" name="Room_No" value="<?php echo htmlspecialchars($class['Room_No'] ?? ''); ?>" required /><br><br> <!-- Correct column name -->

    <input type="submit" name="update_class" value="Update Class" />
    <button type="submit" name="delete_class" onclick="return confirm('Are you sure you want to delete this class?');">Delete Class</button>
    <button type="button" onclick="window.location.href='view_class.php';">Cancel</button>

    
</form>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
