<?php
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "student_management"
);

if ($conn->connect_error) {
    die("Database connection failed");
}

$conn->set_charset("utf8mb4");
?>