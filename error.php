<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/app/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "<h2 style='color: green;'>✅ Database connection successful!</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database connection failed!</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>