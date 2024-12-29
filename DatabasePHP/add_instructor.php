<?php
include('db_connect.php'); // Include your database connection file

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $instructor_id = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $instructor_name = mysqli_real_escape_string($conn, $_POST['instructor_name']);
    $instructor_email = mysqli_real_escape_string($conn, $_POST['instructor_email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // SQL query to insert a new instructor
    $query = "INSERT INTO instructor_table (Instructor_ID, Instructor_Name, Instructor_Email, Phone_Number, Gender)
              VALUES ('$instructor_id', '$instructor_name', '$instructor_email', '$phone_number', '$gender')";

    // Execute the query and check for success
    if (mysqli_query($conn, $query)) {
        echo "New instructor added successfully.";
        header("Location: view_instructor.php"); // Redirect to the instructor list page after successful insertion
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Instructor</title>
</head>
<body>

<h1>Add New Instructor</h1>

<form method="POST" action="">
    <label for="instructor_id">Instructor ID:</label>
    <input type="text" name="instructor_id" required>
    <br>

    <label for="instructor_name">Instructor Name:</label>
    <input type="text" name="instructor_name" required>
    <br>

    <label for="instructor_email">Instructor Email:</label>
    <input type="email" name="instructor_email" required>
    <br>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" required>
    <br>

    <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="M">Male</option>
        <option value="F">Female</option>
    </select>
    <br>

    <input type="submit" value="Add Instructor">
    <button type="button" onclick="location.href='index.html'">Cancel</button>
</form>

</body>
</html>
