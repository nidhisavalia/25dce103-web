
<?php
require_once "auth.php";
require_once "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Practical 14 - Admin Dashboard</h2>

<p>Welcome, Admin!</p>

<?php
$studentResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$studentCount = mysqli_fetch_assoc($studentResult)['total'];

$eventResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
$eventCount = mysqli_fetch_assoc($eventResult)['total'];
?>

<p>Total Students: <?php echo $studentCount; ?></p>
<p>Total Events: <?php echo $eventCount; ?></p>

<h3>Latest Student Registrations</h3>

<?php
$latestResult = mysqli_query(
    $conn,
    "SELECT id, username, email, created_at
     FROM users
     ORDER BY id DESC
     LIMIT 5"
);

if ($latestResult && mysqli_num_rows($latestResult) > 0) {
    while ($row = mysqli_fetch_assoc($latestResult)) {
        echo htmlspecialchars($row['username']) . " - ";
        echo htmlspecialchars($row['email']) . " - ";
        echo htmlspecialchars($row['created_at']) . "<br>";
    }
} else {
    echo "No registrations found.";
}
?>



<ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="audit_log.php">Audit Log</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
