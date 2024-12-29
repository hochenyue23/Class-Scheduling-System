<?php
include('db_connect.php'); // Include DB connection file

// Check if Enrollment_ID is passed as a GET parameter
if (isset($_GET['enrollment_id'])) {
    $enrollment_id = mysqli_real_escape_string($conn, $_GET['enrollment_id']);

    // Delete query
    $delete_query = "DELETE FROM enrollment_table WHERE Enrollment_ID = '$enrollment_id'";

    if (mysqli_query($conn, $delete_query)) {
        // Redirect back to view_enrollments.php after deletion
        header("Location: view_enrollments.php");
        exit;
    } else {
        echo "Error deleting enrollment: " . mysqli_error($conn);
    }
} else {
    echo "No Enrollment ID provided.";
}

mysqli_close($conn); // Close the connection
?>
