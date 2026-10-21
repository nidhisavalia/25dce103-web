
<?php
require "auth.php";
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid event ID.");
}

$stmt = $conn->prepare("SELECT poster FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    exit("Event not found.");
}

$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows === 1) {
    if (!empty($event["poster"])) {
        $path = __DIR__ . "/uploads/" . basename($event["poster"]);

        if (is_file($path)) {
            unlink($path);
        }
    }

    echo "Event deleted successfully!";
} else {
    echo "Failed to delete event.";
}

$stmt->close();
?>

<p><a href="index.php">Back to Events</a></p>
