<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../Practical10/login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Access denied. Admin only.");
}

if (!isset($_SESSION["last_activity"]) ||
    time() - $_SESSION["last_activity"] > 300) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../Practical10/login.php");
    exit;
}

$_SESSION["last_activity"] = time();
?>