<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../api/db.php';
$db = new Database();
$sqlite = $db->getDb();

// Handle delete actions
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = (int)$_GET['delete'];
    $type = $_GET['type'];
    $tableMap = ['booking' => 'bookings', 'contact' => 'contacts', 'subscriber' => 'subscribers'];
    if (array_key_exists($type, $tableMap)) {
        $sqlite->exec("DELETE FROM {$tableMap[$type]} WHERE id = $id");
    }
    header('Location: dashboard.php');
    exit;
}

// Fetch data
$bookings = $sqlite->query("SELECT * FROM bookings ORDER BY created_at DESC");
$contacts = $sqlite->query("SELECT * FROM contacts ORDER BY created_at DESC");
$subscribers = $sqlite->query("SELECT * FROM subscribers ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | DriveLux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; }
        .header { background: #1a1a2e; padding: 20px; color: white; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin:0; font-family: 'Playfair Display', serif; }
        .header a { color: #c9a84c; text-decoration: none; font-weight: 600; }
        .section { background: white; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        h2 { color: #1a1a2e; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #1a1a2e; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #dee2e6; }
        tr:hover { background: #f1f3f5; }
        .delete-btn { color: #dc3545; text-decoration: none; font-weight: bold; }
        .delete-btn:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DriveLux Admin</h1>
        <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['admin_username']) ?>)</a>
    </div>

    <div class="section">
        <h2>📋 Car Bookings</h2>
        <table>
            <tr><th>ID</th><th>Name</th><th>Car Type</th><th>Pick-up</th><th>Return</th><th>Location</th><th>Date</th><th>Action</th></tr>
            <?php while ($row = $bookings->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['car_type']) ?></td>
                <td><?= $row['pickup_date'] ?></td>
                <td><?= $row['return_date'] ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><a class="delete-btn" href="?delete=<?= $row['id'] ?>&type=booking" onclick="return confirm('Delete this booking?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($bookings->numColumns() == 0): ?><tr><td colspan="8">No bookings yet.</td></tr><?php endif; ?>
        </table>
    </div>

    <div class="section">
        <h2>✉️ Contact Messages</h2>
        <table>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th></tr>
            <?php while ($row = $contacts->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars(substr($row['message'], 0, 50)) ?>...</td>
                <td><?= $row['created_at'] ?></td>
                <td><a class="delete-btn" href="?delete=<?= $row['id'] ?>&type=contact" onclick="return confirm('Delete this message?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($contacts->numColumns() == 0): ?><tr><td colspan="7">No messages.</td></tr><?php endif; ?>
        </table>
    </div>

    <div class="section">
        <h2>📧 Newsletter Subscribers</h2>
        <table>
            <tr><th>ID</th><th>Email</th><th>Subscribed On</th><th>Action</th></tr>
            <?php while ($row = $subscribers->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><a class="delete-btn" href="?delete=<?= $row['id'] ?>&type=subscriber" onclick="return confirm('Delete subscriber?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($subscribers->numColumns() == 0): ?><tr><td colspan="4">No subscribers.</td></tr><?php endif; ?>
        </table>
    </div>
</body>
</html>