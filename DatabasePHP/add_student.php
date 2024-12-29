<?php
include('db_connect.php'); // Include your DB connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data and sanitize inputs
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $academic_status = mysqli_real_escape_string($conn, $_POST['academic_status']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $student_email = mysqli_real_escape_string($conn, $_POST['Student_Email']);
    $dob = mysqli_real_escape_string($conn, $_POST['DOB']);

    // Insert query
    $query = "INSERT INTO student_table (Student_ID, Student_Name, Gender, Academic_Status, Contact_No, Student_Email, DOB)
              VALUES ('$student_id', '$student_name', '$gender', '$academic_status', '$contact_no', '$student_email', '$dob')";
    
    // Execute the query and check for success
    if (mysqli_query($conn, $query)) {
        echo "New student added successfully.";
        header("Location: view_students.php"); // Redirect to the view page after successful addition
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
</head>
<body>

<h1>Add New Student</h1>

<form method="POST" action="">
    <label for="student_id">Student ID:</label>
    <input type="text" name="student_id" required>
    <br>

    <label for="student_name">Student Name:</label>
    <input type="text" name="student_name" required>
    <br>

    <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="M">Male</option>
        <option value="F">Female</option>
    </select>
    <br>

    <label for="academic_status">Academic Status:</label>
    <select name="academic_status" required>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>
    <br>

    <label for="contact_no">Contact No:</label>
    <input type="text" name="contact_no" required>
    <br>

    <label for="Student_Email">Student Email:</label>
    <input type="email" name="Student_Email" required>
    <br>

    <label for="DOB">Date of Birth:</label>
    <input type="date" name="DOB" required>
    <br>

    <input type="submit" value="Add Student">
</form>
<!-- Back Button -->
<button onclick="location.href='index.html'">Back to Main Menu</button>
<br><br>
</body>
</html>