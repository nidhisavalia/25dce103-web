
<?php
require "auth.php";
require "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid event ID.");
}

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    exit("Event not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $event_date = $_POST["event_date"];
    $venue = trim($_POST["venue"]);
    $poster = $event["poster"];
    $newFile = null;

    $date = DateTime::createFromFormat("!Y-m-d", $event_date);

    if ($title === "" || $description === "" || $venue === "") {
        $message = "Please fill all fields.";
    } elseif (!$date || $date->format("Y-m-d") !== $event_date) {
        $message = "Invalid event date.";
    } else {
        if (isset($_FILES["poster"]) &&
            $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES["poster"]["error"] !== UPLOAD_ERR_OK) {
                $message = "Poster upload failed.";
            } elseif ($_FILES["poster"]["size"] > 2 * 1024 * 1024) {
                $message = "Poster must be 2 MB or less.";
            } else {
                $tmp = $_FILES["poster"]["tmp_name"];
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

                $allowed = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($allowed[$mime]) ||
                    getimagesize($tmp) === false) {
                    $message = "Only JPG, PNG, and WEBP images are allowed.";
                } else {
                    $newFile = bin2hex(random_bytes(16)) .
                               "." . $allowed[$mime];

                    if (move_uploaded_file(
                        $tmp, __DIR__ . "/uploads/" . $newFile
                    )) {
                        $poster = $newFile;
                    } else {
                        $message = "Could not save new poster.";
                    }
                }
            }
        }

        if ($message === "") {
            $stmt = $conn->prepare(
                "UPDATE events SET title=?, description=?,
                 event_date=?, venue=?, poster=? WHERE id=?"
            );

            $stmt->bind_param(
                "sssssi", $title, $description,
                $event_date, $venue, $poster, $id
            );

            if ($stmt->execute()) {
                if ($newFile && $event["poster"]) {
                    $oldPath = __DIR__ . "/uploads/" . $event["poster"];
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $message = "Event updated successfully!";

                $event["title"] = $title;
                $event["description"] = $description;
                $event["event_date"] = $event_date;
                $event["venue"] = $venue;
                $event["poster"] = $poster;
            } else {
                if ($newFile && is_file(__DIR__ . "/uploads/" . $newFile)) {
                    unlink(__DIR__ . "/uploads/" . $newFile);
                }

                $message = "Failed to update event.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
</head>
<body>

<h2>Edit Event</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST" enctype="multipart/form-data">

    <label>Event Title:</label><br>
    <input type="text" name="title" required
        value="<?php echo htmlspecialchars($event["title"]); ?>">
    <br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?php
        echo htmlspecialchars($event["description"]);
    ?></textarea>
    <br><br>

    <label>Event Date:</label><br>
    <input type="date" name="event_date" required
        value="<?php echo htmlspecialchars($event["event_date"]); ?>">
    <br><br>

    <label>Venue:</label><br>
    <input type="text" name="venue" required
        value="<?php echo htmlspecialchars($event["venue"]); ?>">
    <br><br>

    <p>Choose a new poster only if you want to replace the current one.</p>

    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp">
    <br><br>

    <button type="submit">Update Event</button>
</form>

<br>
<a href="index.php">Back to Events</a>

</body>
</html>
