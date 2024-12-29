<?php
ob_start(); // Start output buffering
include('db_connect.php');

// Check if export request is made
if (isset($_POST['export_csv'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student's name
    $student_query = "SELECT Student_Name FROM student_table WHERE Student_ID = '$student_id'";
    $student_result = mysqli_query($conn, $student_query);
    $student_name = mysqli_fetch_assoc($student_result)['Student_Name'];

    // Fetch the enrolled schedules for the selected student
    $query = "SELECT e.Enrollment_ID, s.Schedule_ID, s.Class_Duration, s.Start_Time, s.End_Time, s.Week_Day, 
                     c.Class_Name, c.Room_no
              FROM enrollment_table e
              JOIN schedule_table s ON e.Schedule_ID = s.Schedule_ID
              JOIN class_table c ON s.Class_ID = c.Class_ID
              WHERE e.Student_ID = '$student_id'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Set headers to force the download of CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="timetable_' . $student_id . '.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add student ID and name at the top of the CSV
    fputcsv($output, ['Student ID:', $student_id]);
    fputcsv($output, ['Student Name:', $student_name]);
    fputcsv($output, []); // Empty row for spacing

    // Add headers for the timetable
    fputcsv($output, ['Enrollment ID', 'Schedule ID', 'Class Name', 'Room Number', 'Duration', 'Start Time', 'End Time', 'Week Day']);
    
    // Add data rows for the timetable
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['Enrollment_ID'],
            $row['Schedule_ID'],
            $row['Class_Name'],
            $row['Room_no'],
            formatDuration($row['Class_Duration']),
            formatTime($row['Start_Time']),
            formatTime($row['End_Time']),
            $row['Week_Day']
        ]);
    }

    fclose($output);  // Close the CSV file output
    exit();  // Stop further processing and force the download
}

// Function to format duration into hours and minutes
function formatDuration($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    $formatted = [];
    if ($hours > 0) $formatted[] = "$hours hour" . ($hours > 1 ? "s" : "");
    if ($remaining_minutes > 0) $formatted[] = "$remaining_minutes minute" . ($remaining_minutes > 1 ? "s" : "");
    return implode(" ", $formatted);
}

// Function to format time into 12-hour format with AM/PM
function formatTime($time) {
    $time = strtotime($time); // Convert to Unix timestamp
    return date("g:i A", $time); // Format as 12-hour with AM/PM
}

mysqli_close($conn);  // Close the database connection

ob_end_flush(); // Flush the output buffer and send the content to the browser
?>
