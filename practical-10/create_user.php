<?php
require "db.php";

$username = "student1";
$email = "student1@example.com";
$password = password_hash("Student@123", PASSWORD_DEFAULT);
$role = "student";

$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password, role)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssss", $username, $email, $password, $role);

if ($stmt->execute()) {
    echo "User created successfully";
} else {
    echo "Error: " . $stmt->error;
}
?>