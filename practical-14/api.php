
<?php
header("Content-Type: application/json");

include "db.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    $result = $conn->query("SELECT * FROM events ORDER BY id DESC");
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);
}

elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data["event_name"] ?? "";
    $date = $data["event_date"] ?? "";
    $description = $data["description"] ?? "";

    if ($name == "" || $date == "") {
        http_response_code(400);
        echo json_encode(["message" => "Name and date are required"]);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO events (event_name, event_date, description) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $date, $description);
    $stmt->execute();

    echo json_encode(["message" => "Event added successfully"]);
}

elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = (int)($data["id"] ?? 0);
    $name = $data["event_name"] ?? "";
    $date = $data["event_date"] ?? "";
    $description = $data["description"] ?? "";

    if ($id <= 0 || $name == "" || $date == "") {
        http_response_code(400);
        echo json_encode(["message" => "Valid ID, name and date are required"]);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE events SET event_name=?, event_date=?, description=? WHERE id=?"
    );
    $stmt->bind_param("sssi", $name, $date, $description, $id);
    $stmt->execute();

    echo json_encode(["message" => "Event updated successfully"]);
}

elseif ($method == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)($data["id"] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["message" => "Valid ID is required"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["message" => "Event deleted successfully"]);
}

else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>
