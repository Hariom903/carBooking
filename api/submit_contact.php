<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (
    empty($data['first_name']) || empty($data['last_name']) ||
    empty($data['email']) || empty($data['message'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Required fields: first_name, last_name, email, message.']);
    exit;
}

$db = new Database();
$stmt = $db->getDb()->prepare("INSERT INTO contacts (first_name, last_name, email, phone, subject, message) VALUES (:first, :last, :email, :phone, :subject, :message)");
$stmt->bindValue(':first', $data['first_name'], SQLITE3_TEXT);
$stmt->bindValue(':last', $data['last_name'], SQLITE3_TEXT);
$stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
$stmt->bindValue(':phone', $data['phone'] ?? '', SQLITE3_TEXT);
$stmt->bindValue(':subject', $data['subject'] ?? '', SQLITE3_TEXT);
$stmt->bindValue(':message', $data['message'], SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Message sent.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message.']);
}
?>