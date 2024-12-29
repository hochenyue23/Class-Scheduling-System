<?php
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // leave it empty for no password
$dbname = "Data1"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>