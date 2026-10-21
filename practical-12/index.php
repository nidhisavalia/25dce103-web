
<?php
require "auth.php";
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["add_event"])) {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $event_date = $_POST["event_date"];
    $venue = trim($_POST["venue"]);
    $poster = "";

    $date = DateTime::createFromFormat("!Y-m-d", $event_date);

    if ($title === "" || $description === "" || $venue === "") {
        $message = "Please fill all fields.";
    } elseif (!$date || $date->format("Y-m-d") !== $event_date) {
        $message = "Invalid event date.";
    } elseif (!isset($_FILES["poster"]) ||
              $_FILES["poster"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Please select a poster.";
    } elseif ($_FILES["poster"]["size"] > 2 * 1024 * 1024) {
        $message = "Poster size must be 2 MB or less.";
    } else {
        $tmp = $_FILES["poster"]["tmp_name"];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

        $allowed = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if (!isset($allowed[$mime]) || getimagesize($tmp) === false) {
            $message = "Only JPG, PNG, and WEBP images are allowed.";
        } else {
            $poster = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
            $target = __DIR__ . "/uploads/" . $poster;

            if (!move_uploaded_file($tmp, $target)) {
                $message = "Poster upload failed.";
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO events
                    (title, description, event_date, venue, poster)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss",
                    $title,
                    $description,
                    $event_date,
                    $venue,
                    $poster
                );

                if ($stmt->execute()) {
                    $message = "Event added successfully!";
                } else {
                    unlink($target);
                    $message = "Failed to add event.";
                }

                $stmt->close();
            }
        }
    }
}

$result = $conn->query("SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
</head>
<body>

<h2>Event Management System</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<h3>Add Event</h3>

<form method="POST" enctype="multipart/form-data">

    <label>Event Title:</label><br>
    <input type="text" name="title" required>
    <br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea>
    <br><br>

    <label>Event Date:</label><br>
    <input type="date" name="event_date" required>
    <br><br>

    <label>Venue:</label><br>
    <input type="text" name="venue" required>
    <br><br>

    <label>Poster (JPG, PNG, WEBP; maximum 2 MB):</label><br>
    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp" required>
    <br><br>

    <button type="submit" name="add_event">Add Event</button>

</form>

<hr>

<h3>All Events</h3>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Venue</th>
    <th>Poster</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo htmlspecialchars($row["title"]); ?></td>
    <td><?php echo htmlspecialchars($row["description"]); ?></td>
    <td><?php echo htmlspecialchars($row["event_date"]); ?></td>
    <td><?php echo htmlspecialchars($row["venue"]); ?></td>

    <td>
        <?php if ($row["poster"] !== "") { ?>
            <img
                src="uploads/<?php echo rawurlencode($row["poster"]); ?>"
                width="100"
                alt="Event poster">
        <?php } else { ?>
            No poster
        <?php } ?>
    </td>

    <td>
        <a href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a>

        <form action="delete.php" method="POST"
              onsubmit="return confirm('Delete this event?')">
            <input type="hidden" name="id"
                   value="<?php echo $row["id"]; ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
