<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>

<body>

<h2>Student Registration</h2>

<form action="register.php" method="POST">

    <label>Name:</label>
    <input type="text" name="name" required>
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>