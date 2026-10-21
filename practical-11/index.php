
<?php
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["add_student"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $course = trim($_POST["course"]);
    $semester = (int)$_POST["semester"];

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $course === "" || $semester < 1 || $semester > 8) {
        $message = "Please enter valid student details.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course, semester)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("sssi", $name, $email, $course, $semester);

        if ($stmt->execute()) {
            $message = "Student added successfully!";
        } else {
            $message = $stmt->errno == 1062
                ? "This email already exists."
                : "Failed to add student.";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["delete_student"])) {

    $id = (int)$_POST["delete_student"];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    $message = $stmt->execute() && $stmt->affected_rows > 0
        ? "Student deleted successfully!"
        : "Student not found or deletion failed.";

    $stmt->close();
}

$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$sql = "SELECT id, name, email, course, semester FROM students
        WHERE (name LIKE CONCAT('%', ?, '%')
        OR email LIKE CONCAT('%', ?, '%'))
        AND (? = '' OR course = ?)
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $search, $search, $courseFilter, $courseFilter);
$stmt->execute();
$result = $stmt->get_result();

$courses = $conn->query(
    "SELECT DISTINCT course FROM students ORDER BY course"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
</head>
<body>

<h2>Student Management System</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<h3>Add Student</h3>

<form method="POST">
    <input type="text" name="name" placeholder="Student Name" required>
    <br><br>

    <input type="email" name="email" placeholder="Email" required>
    <br><br>

    <input type="text" name="course" placeholder="Course" required>
    <br><br>

    <label>Semester:</label>
    <select name="semester" required>
        <?php for ($i = 1; $i <= 8; $i++) { ?>
            <option value="<?php echo $i; ?>">
                <?php echo $i; ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <button type="submit" name="add_student">Add Student</button>
</form>

<hr>

<h3>Search and Filter</h3>

<form method="GET">
    <input type="text" name="search"
           placeholder="Search name or email"
           value="<?php echo htmlspecialchars($search); ?>">

    <select name="course">
        <option value="">All Courses</option>

        <?php while ($c = $courses->fetch_assoc()) { ?>
            <option value="<?php echo htmlspecialchars($c["course"]); ?>"
                <?php echo $courseFilter === $c["course"] ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($c["course"]); ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Search / Filter</button>
    <a href="index.php">Reset</a>
</form>

<h3>Student Records</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Semester</th>
        <th>Actions</th>
    </tr>

    <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["name"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
                <td><?php echo htmlspecialchars($row["course"]); ?></td>
                <td><?php echo $row["semester"]; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a>

                    <form method="POST" style="display:inline"
                          onsubmit="return confirm('Delete this student?')">
                        <button type="submit" name="delete_student"
                                value="<?php echo $row["id"]; ?>">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6">No student records found.</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
