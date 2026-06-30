<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email required.']);
    exit;
}

$db = new Database();
$stmt = $db->getDb()->prepare("INSERT OR IGNORE INTO subscribers (email) VALUES (:email)");
$stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
if ($stmt->execute()) {
    echo json_encode(['success' => 'Subscribed successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Subscription failed.']);
}
?>