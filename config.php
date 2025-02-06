<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbHost = 'localhost';
$dbName = 'mydb';
$dbUsername = 'root';
$dbPassword = '';

// Use mysqli_connect instead of ->connect_error (which is for MySQLi object-oriented)
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
