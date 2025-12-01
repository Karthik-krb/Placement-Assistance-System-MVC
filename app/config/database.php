<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct(array $config) {
        $dbConfig = $config['db'] ?? [];
        $host = $dbConfig['host'] ?? '127.0.0.1';
        $port = $dbConfig['port'] ?? 3306;
        $dbname = $dbConfig['dbname'] ?? 'placement_data';
        $user = $dbConfig['user'] ?? 'root';
        $pass = $dbConfig['pass'] ?? '';

        try {
            $this->conn = new PDO(
                "mysql:host={$host};port={$port};dbname={$dbname}", 
                $user, 
                $pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(array $config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>