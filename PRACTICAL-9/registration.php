
<?php
$conn = new mysqli("localhost", "root", "", "practical9");

if ($conn->connect_error) {
    die("Database connection failed!");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username == "" || $email == "" || $password == "") {
        $message = "All fields are required!";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $message = "Username must be 3 to 50 characters!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 100) {
        $message = "Enter a valid email!";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters!";
    } else {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or email already exists!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt2 = $conn->prepare(
                "INSERT INTO users (username, email, password)
                 VALUES (?, ?, ?)"
            );
            $stmt2->bind_param("sss", $username, $email, $hash);

            if ($stmt2->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Registration failed!";
            }

            $stmt2->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure User Registration</title>
</head>
<body>
    <h2>User Registration Form</h2>

    <form method="POST" action="">
        Username:
        <input type="text" name="username"
               minlength="3" maxlength="50" required>
        <br><br>

        Email:
        <input type="email" name="email"
               maxlength="100" required>
        <br><br>

        Password:
        <input type="password" name="password"
               minlength="8" required>
        <br><br>

        <button type="submit">Register</button>
    </form>

    <p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
