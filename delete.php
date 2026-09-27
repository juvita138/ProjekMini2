<?php
session_start();
require_once "../config/db.php";

$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

if (!hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")) {
    http_response_code(403);
    exit("Token CSRF tidak valid.");
}

$id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(["id" => $id]);
}

header("Location: index.php?status=deleted");
exit;
?>