<?php
// api/db.php
class Database {
    private $db;

    public function __construct() {
        $this->db = new SQLite3(__DIR__ . '/../database.sqlite');
        $this->createTables();
    }

    private function createTables() {
        // Bookings table
        $this->db->exec("CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pickup_date TEXT NOT NULL,
            return_date TEXT NOT NULL,
            car_type TEXT NOT NULL,
            location TEXT NOT NULL,
            full_name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Contacts table
        $this->db->exec("CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            subject TEXT,
            message TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Newsletter subscribers
        $this->db->exec("CREATE TABLE IF NOT EXISTS subscribers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Admin credentials (create if not exists)
        $this->db->exec("CREATE TABLE IF NOT EXISTS admin (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )");
        // Insert default admin if table is empty
        $count = $this->db->querySingle("SELECT COUNT(*) FROM admin");
        if ($count == 0) {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $this->db->exec("INSERT INTO admin (username, password) VALUES ('admin', '$hash')");
        }
    }

    public function getDb() {
        return $this->db;
    }
}
?>