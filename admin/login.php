<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../api/db.php';
    $db = new Database();
    $stmt = $db->getDb()->prepare("SELECT * FROM admin WHERE username = :user");
    $stmt->bindValue(':user', $_POST['username'], SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($result && password_verify($_POST['password'], $result['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $result['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | DriveLux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #1a1a2e; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 400px; }
        h2 { color: #1a1a2e; margin-bottom: 24px; }
        input { width: 100%; padding: 12px; margin-bottom: 16px; border: 2px solid #dee2e6; border-radius: 8px; font-family: inherit; }
        button { background: #c9a84c; color: #0a0a0a; border: none; padding: 12px; width: 100%; border-radius: 50px; font-weight: 700; cursor: pointer; }
        .error { color: #dc3545; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <p style="text-align:center; margin-top:12px; color:#6c757d; font-size:13px;">Default: admin / admin123</p>
    </div>
</body>
</html>