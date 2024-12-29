<?php
include('db_connect.php'); // Include your DB connection file

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the student details from the database
    $query = "SELECT * FROM student_table WHERE Student_ID = '$id'"; // Add quotes around $id
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $student = mysqli_fetch_assoc($result);

    // Check if the student exists
    if (!$student) {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Update the student information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it was a delete request
    if (isset($_POST['delete'])) {
        // Delete query
        $delete_query = "DELETE FROM student_table WHERE Student_ID = '$id'";
        
        if (mysqli_query($conn, $delete_query)) {
            echo "Student deleted successfully.";
            header("Location: view_students.php"); // Redirect to the view page after deletion
            exit;
        } else {
            echo "Error deleting student: " . mysqli_error($conn);
        }
    } else {
        // Update student information
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $gender = $_POST['gender'];
        $academic_status = $_POST['academic_status'];
        $contact_no = $_POST['contact_no'];
        $student_email = $_POST['Student_Email'];
        $dob = $_POST['DOB'];

        // Update query
        $update_query = "UPDATE student_table SET 
                         Student_Name = '$student_name', 
                         Gender = '$gender', 
                         Academic_Status = '$academic_status', 
                         Contact_No = '$contact_no', 
                         Student_Email = '$student_email', 
                         DOB = '$dob' 
                         WHERE Student_ID = '$student_id'"; // Add quotes around $student_id
        
        if (mysqli_query($conn, $update_query)) {
            echo "Student updated successfully.";
            header("Location: view_students.php"); // Redirect to the view page after update
            exit;
        } else {
            echo "Error updating student: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body>

<h1>Edit Student</h1>

<form method="POST" action="">
    <input type="hidden" name="student_id" value="<?php echo $student['Student_ID']; ?>">
    
    <label for="student_name">Student Name:</label>
    <input type="text" name="student_name" value="<?php echo $student['Student_Name']; ?>" required>
    <br>
    
    <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="M" <?php if ($student['Gender'] == 'M') echo 'selected'; ?>>Male</option>
        <option value="F" <?php if ($student['Gender'] == 'F') echo 'selected'; ?>>Female</option>
    </select>
    <br>

    <label for="academic_status">Academic Status:</label>
    <select name="academic_status" required>
        <option value="Active" <?php if ($student['Academic_Status'] == 'Active') echo 'selected'; ?>>Active</option>
        <option value="Inactive" <?php if ($student['Academic_Status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
    </select>
    <br>

    <label for="contact_no">Contact No:</label>
    <input type="text" name="contact_no" value="<?php echo $student['Contact_No']; ?>" required>
    <br>

    <label for="Student_Email">Student Email:</label>
    <input type="email" name="Student_Email" value="<?php echo $student['Student_Email']; ?>" required>
    <br>

    <label for="DOB">Date of Birth:</label>
    <input type="date" name="DOB" value="<?php echo $student['DOB']; ?>" required>
    <br>

    <input type="submit" value="Update">
    <button type="button" onclick="location.href='view_students.php'">Cancel</button>
    <input type="submit" name="delete" value="Delete" style="color: red;">
</form>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
