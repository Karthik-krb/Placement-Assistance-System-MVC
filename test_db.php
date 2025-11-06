<?php
// Quick database connection test
// Access this at: http://localhost/PAS/test_db.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>Database Connection Test</h2>";

// Load config
$config = require __DIR__ . '/config/config.php';

echo "<p><strong>Config loaded:</strong></p>";
echo "<pre>";
print_r($config['db']);
echo "</pre>";

// Test connection
try {
    $dbConfig = $config['db'];
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}", 
        $dbConfig['user'], 
        $dbConfig['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'><strong>✓ Database connection successful!</strong></p>";
    
    // Check if tables exist
    $tables = ['admin', 'candidate', 'company'];
    echo "<h3>Checking Tables:</h3>";
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "<p style='color: green;'>✓ Table '$table' exists - $count records found</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Table '$table' not found or error: " . $e->getMessage() . "</p>";
        }
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'><strong>✗ Database connection failed:</strong><br>";
    echo $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='/PAS/public/'>Go to Home Page</a></p>";
?>
