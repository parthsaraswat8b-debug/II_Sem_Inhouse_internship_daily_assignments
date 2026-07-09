<?php
$host = "localhost";
$username = "root";
$password = "laude999";  // update this
$database = "skit";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
