
<?php
require "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid student ID.");
}

$stmt = $conn->prepare(
    "SELECT name, email, course, semester
     FROM students WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    exit("Student not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $course = trim($_POST["course"]);
    $semester = (int)$_POST["semester"];

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $course === "" || $semester < 1 || $semester > 8) {
        $message = "Please enter valid details.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE students SET name=?, email=?, course=?, semester=?
             WHERE id=?"
        );
        $stmt->bind_param(
            "sssii", $name, $email, $course, $semester, $id
        );

        if ($stmt->execute()) {
            $message = $stmt->affected_rows > 0
                ? "Student updated successfully!"
                : "No changes made, or details are unchanged.";

            $student = [
                "name" => $name,
                "email" => $email,
                "course" => $course,
                "semester" => $semester
            ];
        } else {
            $message = $stmt->errno == 1062
                ? "This email already belongs to another student."
                : "Failed to update student.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h2>Edit Student</h2>
<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <input name="name" required
        value="<?php echo htmlspecialchars($student["name"]); ?>">
    <br><br>

    <input type="email" name="email" required
        value="<?php echo htmlspecialchars($student["email"]); ?>">
    <br><br>

    <input name="course" required
        value="<?php echo htmlspecialchars($student["course"]); ?>">
    <br><br>

    <select name="semester" required>
        <?php for ($i = 1; $i <= 8; $i++) { ?>
            <option value="<?php echo $i; ?>"
                <?php echo (int)$student["semester"] === $i ? "selected" : ""; ?>>
                <?php echo $i; ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <button type="submit">Update Student</button>
</form>

<br>
<a href="index.php">Back to Student List</a>

</body>
</html>
