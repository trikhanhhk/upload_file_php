<?php
$servername = "localhost";
$database = "upload_file";
$username = "administrator";
$password = "ubuntu@123";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>