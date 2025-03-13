<?php
$host = 'localhost';
$dbname = 'wbdv';
$username = 'root';
$password = 'admin123';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>