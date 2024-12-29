<?php
include('db_connect.php');

// Initialize variables for error and success messages
$error = $success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $instructor_id = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);

    // Validation
    if (empty($class_id) || empty($class_name) || empty($instructor_id) || empty($room_no)) {
        $error = "All fields are required.";
    } else {
        // Check if Class_ID already exists
        $check_query = "SELECT * FROM class_table WHERE Class_ID = '$class_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Class ID already exists. Please choose a unique ID.";
        } else {
            // Insert data into the class_table
            $query = "INSERT INTO class_table (Class_ID, Class_Name, Instructor_ID, Room_no) 
                      VALUES ('$class_id', '$class_name', '$instructor_id', '$room_no')";

            if (mysqli_query($conn, $query)) {
                $success = "Class added successfully.";
            } else {
                $error = "Failed to add class: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch instructors for dropdown
$instructors_query = "SELECT Instructor_ID, Instructor_Name FROM instructor_table";
$instructors_result = mysqli_query($conn, $instructors_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
</head>
<body>
<h1>Add Class</h1>

<!-- Error or Success Message -->
<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>

<!-- Add Class Form -->
<form method="POST" action="add_class.php">
    <label for="class_id">Class ID:</label>
    <input type="text" id="class_id" name="class_id" required>
    <br><br>

    <label for="class_name">Class Name:</label>
    <input type="text" id="class_name" name="class_name" required>
    <br><br>

    <label for="instructor_id">Instructor:</label>
    <select id="instructor_id" name="instructor_id" required>
        <option value="">--Select an Instructor--</option>
        <?php while ($row = mysqli_fetch_assoc($instructors_result)): ?>
            <option value="<?php echo $row['Instructor_ID']; ?>">
                <?php echo $row['Instructor_Name']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label for="room_no">Room Number:</label>
    <input type="text" id="room_no" name="room_no" required>
    <br><br>

    <button type="submit">Add Class</button>
</form>

<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>

</body>
</html>

<?php
mysqli_close($conn);
?>
