<?php
include('db_connect.php'); // Include your DB connection file

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch instructor details from the database
    $query = "SELECT * FROM instructor_table WHERE Instructor_ID = '$id'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $instructor = mysqli_fetch_assoc($result);

    // Check if the instructor exists
    if (!$instructor) {
        echo "Instructor not found.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Update or delete the instructor information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update logic
        $instructor_name = $_POST['instructor_name'];
        $instructor_email = $_POST['instructor_email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];

        // Update query
        $update_query = "UPDATE instructor_table SET 
                         Instructor_Name = '$instructor_name', 
                         Instructor_Email = '$instructor_email', 
                         Phone_Number = '$phone_number', 
                         Gender = '$gender' 
                         WHERE Instructor_ID = '$id'";
        
        if (mysqli_query($conn, $update_query)) {
            echo "Instructor updated successfully.";
            header("Location: view_instructor.php"); // Redirect to the view page after update
            exit;
        } else {
            echo "Error updating instructor: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['delete'])) {
        // Delete logic
        $delete_query = "DELETE FROM instructor_table WHERE Instructor_ID = '$id'";
        
        if (mysqli_query($conn, $delete_query)) {
            echo "Instructor deleted successfully.";
            header("Location: view_instructor.php"); // Redirect to the view page after deletion
            exit;
        } else {
            echo "Error deleting instructor: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
</head>
<body>

<h1>Edit Instructor</h1>

<form method="POST" action="">
    <label for="instructor_id">Instructor ID:</label>
    <input type="text" name="instructor_id" value="<?php echo htmlspecialchars($instructor['Instructor_ID']); ?>" readonly>
    <br>

    <label for="instructor_name">Instructor Name:</label>
    <input type="text" name="instructor_name" value="<?php echo htmlspecialchars($instructor['Instructor_Name']); ?>" required>
    <br>
    
    <label for="instructor_email">Instructor Email:</label>
    <input type="email" name="instructor_email" value="<?php echo htmlspecialchars($instructor['Instructor_Email']); ?>" required>
    <br>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" value="<?php echo htmlspecialchars($instructor['Phone_Number']); ?>" required>
    <br>

    <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="M" <?php if ($instructor['Gender'] == 'M') echo 'selected'; ?>>Male</option>
        <option value="F" <?php if ($instructor['Gender'] == 'F') echo 'selected'; ?>>Female</option>
    </select>
    <br>

    <!-- Update Button -->
    <input type="submit" name="update" value="Update Instructor">
    
    <!-- Delete Button -->
    <input type="submit" name="delete" value="Delete Instructor" onclick="return confirm('Are you sure you want to delete this instructor?');">

    <button type="button" onclick="location.href='view_instructor.php'">Cancel</button>
</form>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
