<?php
// db.php - Database connection for LexConnect
// Include this file at the top of every page that needs the database

$host     = "localhost";
$dbname   = "lexconnect_db";
$username = "root";      // Change to your MySQL username
$password = "";          // Change to your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
