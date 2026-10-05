<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $course = trim($_POST["course"]);

    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $mobile = htmlspecialchars($mobile);
    $course = htmlspecialchars($course);

    if (empty($name) || empty($email) || empty($mobile) || empty($course)) {
        echo "Please fill all fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $file = fopen("registrations.csv", "a");

    fputcsv($file, [$name, $email, $mobile, $course]);

    fclose($file);

    echo "<h2>Registration Successful!</h2>";
    echo "<p>Your data has been saved successfully.</p>";

} else {
    echo "Invalid Request.";
}
?>