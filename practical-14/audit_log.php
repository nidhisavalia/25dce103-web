
<?php
require_once "auth.php";
require_once "db.php";

$result = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Audit Log</title>
</head>
<body>

<h2>Admin Audit Log</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Action</th>
    <th>Date</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo htmlspecialchars($row['id']); ?></td>
    <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['action']); ?></td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
</tr>
<?php } ?>

</table>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
