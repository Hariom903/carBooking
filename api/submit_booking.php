<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (
    empty($data['pickup_date']) || empty($data['return_date']) ||
    empty($data['car_type']) || empty($data['location']) ||
    empty($data['full_name'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

$db = new Database();
$stmt = $db->getDb()->prepare("INSERT INTO bookings (pickup_date, return_date, car_type, location, full_name) VALUES (:pickup, :return, :car, :loc, :name)");
$stmt->bindValue(':pickup', $data['pickup_date'], SQLITE3_TEXT);
$stmt->bindValue(':return', $data['return_date'], SQLITE3_TEXT);
$stmt->bindValue(':car', $data['car_type'], SQLITE3_TEXT);
$stmt->bindValue(':loc', $data['location'], SQLITE3_TEXT);
$stmt->bindValue(':name', $data['full_name'], SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Booking saved successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save booking.']);
}
?>