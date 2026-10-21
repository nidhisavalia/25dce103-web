<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["last_activity"]) ||
    time() - $_SESSION["last_activity"] > 300) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$_SESSION["last_activity"] = time();

if ($_SESSION["role"] !== "admin") {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome to Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>