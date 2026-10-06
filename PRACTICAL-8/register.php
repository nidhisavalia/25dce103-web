<?php

include "db.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password)
        VALUES ('$name', '$email', '$password')";

if (mysqli_query($conn, $sql)) {
    echo "Registration successful!";
} else {
    echo "Registration failed!";
    mysqli_error($conn);
}

mysqli_close($conn);

?>